<?php
require_once('requests.php');

header("Content-type: text/xml; charset=utf-8");


date_default_timezone_set('Europe/Istanbul');
/*
{
    "success": true,
    "code": 0,
    "version": 1,
    "message": null,
    "data": {
        "trackingId": "ABCCCCCDDD-ABCCCCCDDD-ABCCCCCDDD-ABCCCCCDDD-ABCCCCCDDD"
    }
}*/

//SaleServiceResponse getSalesByDateRange(String apiKey, String sign, long time, Boolean withData,
//String byStatus, String byUser, String orderBy, String orderType, String startDate, String endDate,
//Int pageNumber, Int pageSize, String lang);
//$now_time = (date('Y-m-d H:i:s'));

$date1      = date("Y-m-d H:i:s", strtotime("-5 days"));
$date2      = date("Y-m-d H:i:s");

$mic = list($usec, $sec) = explode(" ", microtime());
$now = round(((float)$usec + (float)$sec) * 100) . '0';

$sign = md5("ABCDSSSSSSSSSSSSSaBKe6RVfektfABCDFGDDDD{$now}");

//$now_time = '1651666357000';
$requestUrld = 'https://dev.gittigidiyor.com:8443/listingapi/ws/IndividualSaleService?wsdl';
$paramsd = [
  'apiKey' => 'ABCDSSSSSSSSSSSSSaBKe6RVfektfABCDFGDDDD',
  'sign' => $sign,
  'time' => $now,
  'withData'=>1,
  'byStatus'=>'S',
  'byUser'=>'',
  'orderBy'=>'A',
  'orderType'=>'A',
  'startDate'=>$date1,
  'endDate'=>$date2,
  'pageNumber'=>'1',
  'pageSize'=>'10',
  'lang'=>'en'
];

//saleServicePagingResponse getSalesByDateRange(string $apiKey, string
//$sign, long $time, boolean $withData, string $byStatus, string $byUser, string $orderBy,
//string $orderType, string $startDate, string $endDate, int $pageNumber, int $pageSize, string $lang)

$client = new SoapClient($requestUrld,
          [
              'login' => 'user',
              'password' => '1',
              'authentication' => SOAP_AUTHENTICATION_BASIC
          ]
      );

   //dd($soapClient->__getFunctions());
    //dd($soapClient->__getTypes());

$res = $client->__soapCall('getSalesByDateRange',$paramsd);

if($res->ackCode !== 'success'){
  $error = true;
}

  echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
  echo '<SIPARISLER>'."\n";

  $CariHesapKodu =  'C'.rand(100000000,999999999);

  for($i=0; $i < $res->saleCount; $i++){

      $maindata = (count($res->sales->sale) == 1) ? $res->sales->sale : $res->sales->sale[$i];


      $buyerName = $maindata->buyerInfo->name .' '. $maindata->buyerInfo->surname;
      $t_ad = "{$maindata->buyerInfo->address} {$maindata->buyerInfo->neighborhoodType->neighborhoodId} {$maindata->buyerInfo->neighborhoodType->neighborhoodName}";
      echo '<SIPARIS>'."\n";
          /*SİPARİŞ İÇİ*/
           echo '<SIPARIS_NO>'.$maindata->saleCode.'</SIPARIS_NO>'."\n";
           echo '<SiparisKaynak>gg</SiparisKaynak>'."\n";
           echo '<PAZARYERI_KARGOKODU>'.$maindata->cargoCode.'</PAZARYERI_KARGOKODU>'."\n";
           echo '<CariHesapKodu>'.$CariHesapKodu.'</CariHesapKodu>'."\n";
           echo '<CariHesapOzelKodu>gittigidiyor</CariHesapOzelKodu>'."\n";
           echo '<tcno>111111111</tcno>'."\n";
           echo '<POSTA>'.$maindata->buyerInfo->username.'</POSTA>'."\n";
           echo '<TARIH>'.$maindata->endDate.'</TARIH>'."\n";
           echo '<sipariszaman>'.$maindata->endDate.'</sipariszaman>'."\n";
           echo '<ISKONTO>'.$maindata->commissionRate.'</ISKONTO>'."\n";
           echo '<NET_TOPLAM>'.$maindata->price.'</NET_TOPLAM>'."\n";
           echo '<TeslimAlici>'.$buyerName.'</TeslimAlici>'."\n";
           echo '<TeslimAdresi>'.$t_ad.'</TeslimAdresi>'."\n";
           echo '<TeslimTelefon>'.$maindata->buyerInfo->phone.'</TeslimTelefon>'."\n";
           echo '<teslimsekli>KR</teslimsekli>'."\n";
           echo '<teslimkod1></teslimkod1>'."\n";
           echo '<teslimkod4></teslimkod4>'."\n";
           echo '<teslimil>'.$maindata->buyerInfo->city.'</teslimil>'."\n";
           echo '<teslimilce>'.$maindata->buyerInfo->district.'</teslimilce>'."\n";
           echo '<faturaalici>'.$buyerName.'</faturaalici>'."\n";
           echo '<faturaAdresi>'.$t_ad.'</faturaAdresi>'."\n";
           echo '<faturaTelefon>'.$maindata->buyerInfo->phone.'</faturaTelefon>'."\n";
           echo '<faturavergino>111111111</faturavergino>'."\n";
           echo '<faturavergidairesi/>'."\n";
           echo '<faturail>'.$maindata->buyerInfo->city.'</faturail>'."\n";
           echo '<faturailce>'.$maindata->buyerInfo->district.'</faturailce>'."\n";
           echo '<SIPDURUM_Adi>Yeni Sipariş</SIPDURUM_Adi>'."\n";
           echo '<status>1</status>'."\n";
           echo '<tasiyicifirma_adi>'.$maindata->shippingInfo->shippingFirmName.'</tasiyicifirma_adi>'."\n";
           echo '<tasiyicifirma>'.explode(" ",$maindata->shippingInfo->shippingFirmName)[0].'</tasiyicifirma>'."\n";
           echo '<kargo_odemesi_adi>Satıcı Öder</kargo_odemesi_adi>'."\n";
           echo '<kargo_odemesi>2</kargo_odemesi>'."\n";
           echo '<kargokodu>'.$maindata->cargoCode.'</kargokodu>'."\n";
           echo '<banka></banka>'."\n";
           echo '<odeme_adi>gittigidiyor</odeme_adi>'."\n";
           echo '<ODEME_SEKLI>'.$maindata->shippingInfo->shippingPaymentType.'</ODEME_SEKLI>'."\n";
           echo '<not>'.$custom_title.'</not>'."\n";

          /*SATIRLAR*/
          echo '<SATIRLAR>'."\n";

              echo '<SATIR>'."\n";
                  echo '<KOD>'.$maindata->saleCode.'</KOD>'."\n";
                  echo '<VARKOD/>'."\n";
                  echo '<note/>'."\n";
                  echo '<BARCODE>'.$maindata->cargoCode.'</BARCODE>'."\n";
                  echo '<URUNADI>'.$maindata->productTitle.'</URUNADI>'."\n";
                  echo '<URUNKODU>'.$maindata->productId.'</URUNKODU>'."\n";
                  echo '<FATURA_ADI>'.$buyerName.'</FATURA_ADI>'."\n";
                  echo '<SECENEK_DEGERI/>'."\n";
                  echo '<FIYAT>'.$maindata->price.'</FIYAT>'."\n";
                  echo '<MIKTAR>'.$maindata->amount.'</MIKTAR>'."\n";
                  echo '<BIRIM>Ad.</BIRIM>'."\n";
                  echo '<FIYAT>'.$maindata->price.'</FIYAT>'."\n";
                  echo '<KDV>'.$ma["tax"].'</KDV>'."\n";
              echo '</SATIR>'."\n";


          echo '</SATIRLAR>'."\n";

      echo '</SIPARIS>'."\n";
  }

    echo '</SIPARISLER>'."\n";

?>
