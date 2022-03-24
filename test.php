<?php

/*
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://stageapi.trendyol.com/stagesapigw/suppliers/%7B%7BsupplierId%7D%7D/v2/products",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n  \"items\": [\n    {\n      \"barcode\": \"Testbarkod-Deneme\",\n      \"title\": \"Bebek Takımı Pamuk\",\n      \"productMainId\": \"1234BT\",\n      \"brandId\": 1791,\n      \"categoryId\": 411,\n      \"quantity\": 100,\n      \"stockCode\": \"STK-345\",\n      \"dimensionalWeight\": 2,\n      \"description\": \"Ürün açıklama bilgisi\",\n      \"currencyType\": \"TRY\",\n      \"listPrice\": 250.99,\n      \"salePrice\": 120.99,\n      \"vatRate\": 18,\n      \"cargoCompanyId\": 10,\n      \"images\": [\n        {\n          \"url\": \"https://www.sampleadress/path/folder/image_1.jpg\"\n        }\n      ],\n      \"attributes\": [\n        {\n          \"attributeId\": 338,\n          \"attributeValueId\": 6980\n        },\n        {\n           \"attributeId\": 47,\n           \"customAttributeValue\": \"PUDRA\"\n         },\n        {\n          \"attributeId\": 346,\n          \"attributeValueId\": 4290\n        }\n      ]\n    }\n  ]\n}",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "postman-token: b043764c-5757-2c21-50e9-736615c13d63"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}


  "items": [
    {
      "barcode": "Testbarkod-Deneme",
      "title": "Bebek Takımı Pamuk",
      "productMainId": "1234BT",
      "brandId": 1791,
      "categoryId": 411,
      "quantity": 100,
      "stockCode": "STK-345",
      "dimensionalWeight": 2,
      "description": "Ürün açıklama bilgisi",
      "currencyType": "TRY",
      "listPrice": 250.99,
      "salePrice": 120.99,
      "vatRate": 18,
      "cargoCompanyId": 10,
      "images": [
        {
          "url": "https://www.sampleadress/path/folder/image_1.jpg"
        }
      ],
      "attributes": [
        {
          "attributeId": 338,
          "attributeValueId": 6980
        },
        {
           "attributeId": 47,
           "customAttributeValue": "PUDRA"
         },
        {
          "attributeId": 346,
          "attributeValueId": 4290
        }
      ]
    }
  ]
}');


$item_to_trendyol = ('{
  "items": [
    {
      "barcode": "Testbarkod-Deneme",
      "title": "Bebek Takımı Pamuk",
      "productMainId": "1234BT",
      "brandId": 1791,
      "categoryId": 411,
      "quantity": 100,
      "stockCode": "STK-345",
      "dimensionalWeight": 2,
      "description": "Ürün açıklama bilgisi",
      "currencyType": "TRY",
      "listPrice": 250.99,
      "salePrice": 120.99,
      "vatRate": 18,
      "cargoCompanyId": 10,
      "images": [
        {
          "url": "https://www.sampleadress/path/folder/image_1.jpg"
        }
      ],
      "attributes": [
        {
          "attributeId": 338,
          "attributeValueId": 6980
        },
        {
           "attributeId": 47,
           "customAttributeValue": "PUDRA"
         },
        {
          "attributeId": 346,
          "attributeValueId": 4290
        }
      ]
    }
  ]
}');



*/

die();
$d = array(
       'items' => array(
           0 => array(
            'barcode' =>'LD00000089196',
            'title' =>'Lora Davet Leblebi Kutusu lazer kesim ahşap kutu (1 Adet) Ceviz - 89196',
            'productMainId'=>'89196',
            'brandId'=>'12960',
            'categoryId'=> 860,
            'quantity'=> 100,
            'stockCode'=> 'STK-345',
            'dimensionalWeight'=> 2,
            'description'=> 'Kutu üzerine istediğiniz bilgiler yazılmaktadır. siparis NOTLARI: Lütfen İsim (Marka) ve Tarih bilgisi yazınız.',
            'currencyType'=> 'TRY',
            'listPrice'=> '239.99',
            'salePrice'=> '199.99',
            'vatRate'=> '18',
            'cargoCompanyId'=> '10',
            'images'=>array(
                        array('url' => 'http://www.nesatr.com/img/urunler/89196_3.jpg'),
                    ),
            'attributes'=>array(
                    'attributeId'=>'338',
                    'attributeValueId'=>'6980'
                ),

        ),
    ), 
);


$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.trendyol.com/sapigw/suppliers/1/v2/products",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>    json_encode($d),
  CURLOPT_HTTPHEADER => array(
   'Authorization: Basic '. base64_encode('1:1'),
    'Content-Type: application/json',
    
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

?>
