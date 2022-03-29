<?php

#$res = request("https://api.trendyol.com/sapigw/suppliers/1/v2/products",1,0);
#$get_brand = request("https://api.trendyol.com/sapigw/brands",0,0);
#$get_category = request("https://api.trendyol.com/sapigw/product-categories",0,0);
#$get_categoryid_categoriId = request("https://api.trendyol.com/sapigw/product-categories/4053/attributes",0,0);
#$get_categoryid_categoriId = request("https://api.trendyol.com/sapigw/product-categories/411/attributes",0,0);

$supplierid = '1';
$base_url = 'https://api.trendyol.com/sapigw';
$url_type = array('get_category'=>'/product-categories','to'=>"suppliers/{$supplierid}/v2/products");


function Request($type){
  global $supplierid,$base_url,$url_type;

  if(isset($url_type[$type])){
      $last_url = $url_type[$type];

      $url  = $base_url . $last_url;

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_ENCODING, "");
      curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      curl_setopt($ch, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);

      curl_setopt($ch,CURLOPT_HTTPHEADER,array(
        'Authorization: Basic '. base64_encode('1:1'),
        'Content-Type: application/json'));


        $response = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          $response = json_decode($response,true);
          return ($response);
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
