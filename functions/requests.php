<?php
/*bu sayfa kendisine verilen id'ye gore sonuc dondurur.*/
//require_once("/modules/menu/internetmagazalari.php");
require_once("../../../inc/functions.php");

function get_store_attribute($id){

    $storeList_info = array(
      '3'=>
        array('name'=>'ciceksepeti','api_1'=>null,'api_2'=>null,
          'get_order'=>
            array(
              'url'=>'https://apis.ciceksepeti.com/api/v1/Order/GetOrders',
              'method'=>'post'

          ),
          'make_package'=>
            array(
              'url'=>"https://apis.ciceksepeti.com/api/v1/Order/readyforcargowithcsintegration",
              'method'=>'put'

          ),
          'headers'=>array(
            'x-api-key:{api_2}',
            'Content-Type: application/json'
          ),
        ),
        '4'=>
        array('name'=>'n11','api_1'=>null,'api_2'=>null,
          'get_order'=>
            array(
              'url'=>'https://api.n11.com/ws/OrderService.wsdl',
              'method'=>'post'

          ),
          'xmloptions'=>array(
            '{api_1}',
            '{api_2}'
          ),
          'headers'=>array(
            "content-type: text/xml"
          ),
        ),
        '2'=>
        array('name'=>'hb','api_1'=>null,'api_2'=>null,'ayar_1'=>null,'ayar_2'=>null,
          'get_order'=>
            array(
              'url'=>"https://oms-external.hepsiburada.com/orders/merchantid/{merchantid}?offset=0&limit=100",
              'method'=>'get'

          ),
          'make_package'=>
            array(
              'url'=>"https://oms-external.hepsiburada.com/packages/merchantid/{merchantid}",
              'method'=>'post'

          ),
          'get_packagelist'=>
            array(
              'url'=>"https://oms-external.hepsiburada.com/packages/merchantid/{merchantid}?",
              'method'=>'get'

          ),
          'headers'=>array(
            'authorization:Basic {ayar_1}{ayar_2}',
            'content-type: application/json'
          ),
        ),
        '10'=>
        array('name'=>'hb_test','api_1'=>null,'api_2'=>null,'ayar_1'=>null,'ayar_2'=>null,
          'get_order'=>
            array(
              'url'=>"https://oms-external-sit.hepsiburada.com/orders/merchantid/{merchantid}?offset=0&limit=10",
              'method'=>'get'

          ),
          'make_package'=>
            array(
              'url'=>"https://oms-external-sit.hepsiburada.com/packages/merchantid/{merchantid}",
              'method'=>'post'

          ),
          'get_packagelist'=>
            array(
              'url'=>"https://oms-external-sit.hepsiburada.com/packages/merchantid/{merchantid}?",
              'method'=>'get'

          ),
          'headers'=>array(
            'authorization:Basic {ayar_1}{ayar_2}',
            'content-type: application/json'
          ),
        ),

    );


    foreach($storeList_info[$id] as $key=>$val){

        if($val == NULL){
            $storeList_info[$id][$key] = export_store_information($id)[$key];
        }
    }

    if(is_array($storeList_info[$id]['headers'])){
        $storeList_info[$id]['headers'][0] = str_replace('{api_2}',$storeList_info[$id]['api_2'],$storeList_info[$id]['headers'][0]);

        if($storeList_info[$id]['name'] == 'hb' || $storeList_info[$id]['name'] == 'hb_test' ){
            $storeList_info[$id]['headers'][0] = str_replace('{ayar_1}{ayar_2}',base64_encode("{$storeList_info[$id]['ayar_1']}:{$storeList_info[$id]['ayar_2']}"),$storeList_info[$id]['headers'][0]);
        }

    }
    if(is_array($storeList_info[$id]['xmloptions'])){
          $storeList_info[$id]['xmloptions'][0] = str_replace('{api_1}',$storeList_info[$id]['api_1'],$storeList_info[$id]['xmloptions'][0]);
          $storeList_info[$id]['xmloptions'][1] = str_replace('{api_2}',$storeList_info[$id]['api_2'],$storeList_info[$id]['xmloptions'][1]);
    }

    return $storeList_info[$id];
}


function export_store_information($id){

  if(!empty($id) and !$id == NULL ){

    $objConn = dbConn();
    $result = $objConn->query("SELECT api_1,api_2,ayar_1,ayar_2,pazaryeri_adi FROM pazaryeri_magaza_ayarlar WHERE  durum = 1 and id=".$id);
    $arrMagaza = mysqli_fetch_array($result);
    return $arrMagaza;
  }

}

function Request($store_id,$request_type,$postfield=null){

  if($store_info = get_store_attribute($store_id)){

      if(!count($store_info) > 0){
          return false;
      }

  }

  if(isset($store_info[$request_type])){

      if(isset($store_info[$request_type]) and !empty($store_info[$request_type])){
          $url = $store_info[$request_type]['url'];
          $type = $store_info[$request_type]['method'];
      }


      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      if($type == 'post'){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield);
      }
      if($type == 'put'){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield);
      }


      curl_setopt($ch, CURLOPT_ENCODING, "");
      curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      curl_setopt($ch, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);

      curl_setopt($ch,CURLOPT_HTTPHEADER,$store_info['headers']);


        $response = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          switch ($store_info['name']) {
              case 'ciceksepeti':
                  return json_decode($response,true);
                  break;
              case 'n11':
                  return $response;
                  break;
              case 'hb':
                  return json_decode($response,true);
                  break;
              case 'hb_test':
                  return json_decode($response,true);
                  break;
            }
            //return json_decode($response,true);
      }

  }

}

function dd($dd){

    echo '<pre>';
      if(is_array($dd)){
        print_r($dd);
      }else{
        echo $dd;
      }
    echo '</pre>';


}

?>
