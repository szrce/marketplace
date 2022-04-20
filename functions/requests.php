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
          'headers'=>array(
            'x-api-key:{api_2}',
            'Content-Type: application/json'
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
    }

    return $storeList_info[$id];
}


function export_store_information($id){

  if(!empty($id) and !$id == NULL ){

    $objConn = dbConn();
    $result = $objConn->query("SELECT api_1,api_2,pazaryeri_adi FROM pazaryeri_magaza_ayarlar WHERE  durum = 1 and id=".$id);
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
            return json_decode($response,true);
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
