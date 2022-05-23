<?php
require_once('requests.php');
header("Content-type: text/xml; charset=utf-8");

$test = 0;

$df_id = ($test) ? 10 : 2;
$make_package_wait_request_type = 'get_order';
$package_completed_result_request_type = 'get_packagelist';

#$date1      = date("Y-m-d", strtotime("-5 days"));
#$timestamp1 = strtotime($date1);

$make_package_wait  = Request($df_id,$make_package_wait_request_type,null);
$package_completed_result  = Request($df_id,$package_completed_result_request_type,null);

function requestMakepackage($oderid){
  global $df_id;

$request_make_package = '{
      "lineItemRequests":
     [
       {
         "id":"'.$oderid.'",
         "quantity":"1"
       }
     ]
    }';


    $make_package_response  = Request($df_id,'make_package',$request_make_package);
    if (!empty($make_package_response['packageNumber']) && !empty($make_package_response['barcode'])) {
          return $make_package_response;
    }
    return array('packageNumber'=>'','barcode'=>'');

}

$CariHesapKodu =  'H'.rand(100000000,999999999);
$orderRand = '-' . rand(1,99);

if(count($make_package_wait['totalCount']) > 0){
    //first time packages
    foreach ($make_package_wait['items'] as $ma){
        $packageinfo = requestMakepackage($ma['id']);
    }
}

if(isset($package_completed_result['statusCode'])){
    exit;
}


#dd($package_completed_result);
#die();

echo  '<?xml version="1.0" encoding="UTF-8"?>'."\n";
echo '<SIPARISLER>'."\n";


$check_order = array();

function xmlEscape($string) {
    return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
}

//Getting Loop
foreach ($package_completed_result as $keyid=>$ma){

    $orderitem = $ma['items'][0];

    $orderDate =(date('Y-m-d',strtotime(str_replace('/', '-', $ma['orderDate']))));
    $orderTime =(date('H:i',strtotime(str_replace('/', '-', $ma['orderDate']))));


    $shippingAddressinfo = $ma['shippingAddress'];
    $invoiceinfo = $ma['invoice']['address'];

    $sub_cargo_info = explode(" ",$ma['cargoCompany']);
    $sub_cargo_info_first = $sub_cargo_info[0];
    $sub_cargo_info_end = $sub_cargo_info[1];

    $cargoNumber = $packageinfo['barcode'];
    $packageNumber = $packageinfo['packageNumber'];
    //[packageNumber] => 5000056140
    //[barcode] => 62150000561406

    if(!in_array($orderitem['orderNumber'],$check_order)){
        $check_order[] = $orderitem['orderNumber'];
    }else{
        $orderitem['orderNumber'] = $orderitem['orderNumber'] .'99';
    }


    //$orderNumberswith_packagenumber = $orderitem["orderNumber"].'0';
    /*SİPARİŞ DETAYI*/
    echo '<SIPARIS>'."\n";
        /*SİPARİŞ İÇİ*/
         echo '<SIPARIS_NO>'.$orderitem['orderNumber'].'</SIPARIS_NO>'."\n";
         echo '<SiparisKaynak>hb</SiparisKaynak>'."\n";
         echo '<PAZARYERI_KARGOKODU>'.$ma["barcode"].'</PAZARYERI_KARGOKODU>'."\n";
         echo '<CariHesapKodu>'.$CariHesapKodu.'</CariHesapKodu>'."\n";
         echo '<CariHesapOzelKodu>hb</CariHesapOzelKodu>'."\n";
         echo '<tcno>'.$ma["identityNo"].'</tcno>'."\n";
         echo '<POSTA>'.$ma["email"].'</POSTA>'."\n";
         echo '<TARIH>'.$orderDate.'</TARIH>'."\n";
         echo '<sipariszaman>'.$orderTime.'</sipariszaman>'."\n";
         echo '<ISKONTO>'.$orderitem["commission"]["amount"].'</ISKONTO>'."\n";
         echo '<NET_TOPLAM>'.$orderitem["totalPrice"]["amount"].'</NET_TOPLAM>'."\n";
         echo '<TeslimAlici>'.$ma["recipientName"].'</TeslimAlici>'."\n";
         echo '<TeslimAdresi>'.$ma["shippingAddressDetail"].'</TeslimAdresi>'."\n";
         echo '<TeslimTelefon>'.$ma["phoneNumber"].'</TeslimTelefon>'."\n";
         echo '<teslimsekli>KR</teslimsekli>'."\n";
         echo '<teslimkod1></teslimkod1>'."\n";
         echo '<teslimkod4></teslimkod4>'."\n";
         echo '<teslimil>'.$ma["shippingCity"].'</teslimil>'."\n";
         echo '<teslimilce>'.$ma["shippingTown"].'</teslimilce>'."\n";
         echo '<faturaalici>'.$ma["companyName"].'</faturaalici>'."\n";
         echo '<faturaAdresi>'.$ma["billingAddress"].'</faturaAdresi>'."\n";
         echo '<faturaTelefon>'.$ma["phoneNumber"].'</faturaTelefon>'."\n";
         echo '<faturavergino>'.$ma["taxNumber"].'</faturavergino>'."\n";
         echo '<faturavergidairesi/>'."\n";
         echo '<faturail>'.$ma["billingCity"].'</faturail>'."\n";
         echo '<faturailce>'.$ma["billingDistrict"].'</faturailce>'."\n";
         echo '<SIPDURUM_Adi>Yeni Sipariş</SIPDURUM_Adi>'."\n";
         echo '<status>1</status>'."\n";
         echo '<tasiyicifirma_adi>'.$ma["cargoCompany"].'</tasiyicifirma_adi>'."\n";
         echo '<tasiyicifirma>'.$sub_cargo_info_first.'</tasiyicifirma>'."\n";
         echo '<kargo_odemesi_adi>Satıcı Öder</kargo_odemesi_adi>'."\n";
         echo '<kargo_odemesi>2</kargo_odemesi>'."\n";
         echo '<kargokodu>'.$ma["barcode"].'</kargokodu>'."\n";
         echo '<banka></banka>'."\n";
         echo '<odeme_adi>hepsiburada</odeme_adi>'."\n";
         echo '<ODEME_SEKLI>22</ODEME_SEKLI>'."\n";
         echo '<not>'.xmlEscape($orderitem['customizedText01']).'</not>'."\n";

        /*SATIRLAR*/
        echo '<SATIRLAR>'."\n";


            echo '<SATIR>'."\n";
                echo '<KOD>'.$orderitem["hbSku"].'</KOD>'."\n";
                echo '<VARKOD/>'."\n";
                echo '<note/>'."\n";
                echo '<BARCODE>'.$orderitem["hbSku"].'</BARCODE>'."\n";
                echo '<URUNADI>'.$orderitem["productName"].'</URUNADI>'."\n";
                echo '<URUNKODU>'.$orderitem["hbSku"].'</URUNKODU>'."\n";
                echo '<FATURA_ADI>'.$ma["totalPrice"]['amount'].'</FATURA_ADI>'."\n";
                echo '<SECENEK_DEGERI/>'."\n";
                echo '<FIYAT>'.$ma["totalPrice"]["amount"].'</FIYAT>'."\n";
                echo '<MIKTAR>'.$orderitem["quantity"].'</MIKTAR>'."\n";
                echo '<BIRIM>Ad.</BIRIM>'."\n";
                echo '<FIYAT>'.$orderitem["merchantUnitPrice"]["amount"].'</FIYAT>'."\n";
                echo '<KDV>'.$orderitem["vatRate"].'</KDV>'."\n";
            echo '</SATIR>'."\n";


        echo '</SATIRLAR>'."\n";

    echo '</SIPARIS>'."\n";
}

echo '</SIPARISLER>'."\n";

?>
