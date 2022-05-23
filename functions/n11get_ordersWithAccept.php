<?php

require_once('function_new_sezer2.php');
$xmlheader = 1;
if($xmlheader){
    header("Content-type: text/xml; charset=utf-8");
}

date_default_timezone_set('Europe/Istanbul');

$df_id = 4;
$request_type = get_order;
$appKey = get_store_attribute(4)['xmloptions'][0];
$appSecret = get_store_attribute(4)['xmloptions'][1];


$date1      = date("d/m/Y", strtotime("-10 days"));
$date2      = date("d/m/Y");


/*
Sipariş durum bilgisi:
1:İşlem Bekliyor
2:İşlemde
3:İptal Edilmiş
4:Geçersiz
5:Tamamlandı

*/

$status = 'New';
$postfield = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sch="http://www.n11.com/ws/schemas">
   <soapenv:Header/>
   <soapenv:Body>
      <sch:DetailedOrderListRequest>
         <auth>
           <appKey>$appKey</appKey>
           <appSecret>$appSecret</appSecret>
         </auth>
          <searchData>
            <productId></productId>
            <status></status>
            <orderNumber></orderNumber>
            <productSellerCode></productSellerCode>
            <recipient></recipient>
            <sameDayDelivery></sameDayDelivery>
            <citizenshipId></citizenshipId>
            <period>
               <startDate>$date1</startDate>
               <endDate>$date2</endDate>
            </period>
            <sortForUpdateDate>true</sortForUpdateDate>
         </searchData>
         <pagingData>
            <currentPage>0</currentPage>
            <pageSize></pageSize>
            <totalCount>5</totalCount>
            <pageCount>0</pageCount>
         </pagingData>
      </sch:DetailedOrderListRequest>
   </soapenv:Body>
</soapenv:Envelope>
XML;

$result  = Request($df_id,$request_type,$postfield);

$xml = simplexml_load_string($result, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
$xml->registerXPathNamespace('SOAP-ENV', 'http://schemas.xmlsoap.org/soap/envelope/');
$xml->registerXPathNamespace('ns3', 'http://www.n11.com/ws/schemas');
$xml = json_decode(json_encode($xml->xpath('//ns3:DetailedOrderListResponse')),TRUE)[0];


if(is_array($xml) and $xml['result']['status'] == 'success'){

    if(count($xml['pagingData']['totalCount']) == '0'){
        echo "n11:{$xml['result']['status']} : yeni siparis mevcut degil";
        exit();
    }
}else{
  echo "n11:{$xml['result']['status']} : {$xml['result']['errorMessage']} ";
  exit();
}

function getorder_accept($orderid = null){
  global $df_id,$request_type,$appKey,$appSecret;

$postfield_sub = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sch="http://www.n11.com/ws/schemas">
   <soapenv:Header/>
   <soapenv:Body>
      <sch:OrderItemAcceptRequest>
         <auth>
            <appKey>$appKey</appKey>
            <appSecret>$appSecret</appSecret>
         </auth>
         <orderItemList>
            <orderItem>
               <id>$orderid</id>
            </orderItem>
         </orderItemList>
	<numberOfPackages>1</numberOfPackages>
      </sch:OrderItemAcceptRequest>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    $result  = Request($df_id,$request_type,$postfield_sub);

    $xml = simplexml_load_string($result, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
    $xml->registerXPathNamespace('SOAP-ENV', 'http://schemas.xmlsoap.org/soap/envelope/');
    $xml->registerXPathNamespace('ns3', 'http://www.n11.com/ws/schemas');
    $xml = json_decode(json_encode($xml->xpath('//ns3:OrderItemAcceptResponse')),TRUE)[0];
    sleep(1);
    //return $xml;
}



function get_subinfo_n11($orderid = null){
  global $df_id,$request_type,$appKey,$appSecret;

//331257235

$postfield_sub = <<<XML
    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sch="http://www.n11.com/ws/schemas">
       <soapenv:Header/>
       <soapenv:Body>
          <sch:OrderDetailRequest>
             <auth>
               <appKey>$appKey</appKey>
               <appSecret>$appSecret</appSecret>
             </auth>
             <orderRequest>
                <id>$orderid</id>
             </orderRequest>
          </sch:OrderDetailRequest>
       </soapenv:Body>
    </soapenv:Envelope>
XML;


    $result  = Request($df_id,$request_type,$postfield_sub);
    $xml = simplexml_load_string($result, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
    $xml->registerXPathNamespace('SOAP-ENV', 'http://schemas.xmlsoap.org/soap/envelope/');
    $xml->registerXPathNamespace('ns3', 'http://www.n11.com/ws/schemas');
    $xml = json_decode(json_encode($xml->xpath('//ns3:OrderDetailResponse')),TRUE)[0];

    sleep(1);
    return $xml;
}


  /*GET ORDERS variable*/

      echo  '<?xml version="1.0" encoding="UTF-8"?>'."\n";
      echo     '<SIPARISLER>'."\n";

      //Getting Loop
      for ($defi = 0; $defi < ($xml['pagingData']['totalCount']); $defi++){

            $orderinfoList  = $xml['orderList']['order'][$defi];
          if(!isset($xml['orderList']['order'][$defi])){
              $orderinfoList  = $xml['orderList']['order'];
          }

          $order_status = ($orderinfoList['status'] == '3') ? '9' : '1';
          $order_description = ($orderinfoList['status'] == '3') ? 'Sipariş iptal' : 'Yeni Sipariş';

          $orderitems =$orderinfoList['orderItemList']['orderItem'];
          $orderid = $orderinfoList['id'];


          $oder_sub_info_data = get_subinfo_n11($orderid);
          $ordernewid_for_accept = $orderitems['id'];
          $acceptorderid = getorder_accept($ordernewid_for_accept);

          $order_sub_billingAddress_detail = $oder_sub_info_data['orderDetail']['billingAddress'];
          $order_buyer_info = $oder_sub_info_data['orderDetail']['buyer'];
          $shipdetail = $oder_sub_info_data['orderDetail']['shippingAddress'];
          $serviceDetail = $oder_sub_info_data['orderDetail']['serviceItemList']['serviceItem'];

          $tcid = is_array($order_buyer_info['tcId']) ? null : $order_buyer_info['tcId'];
          $orderDate =(date('Y-m-d',strtotime(str_replace('/', '-', $oder_sub_info_data["orderDetail"]['createDate']))));
          $orderDatetime =(date('H:i:s',strtotime(str_replace('/', '-', $oder_sub_info_data["orderDetail"]['createDate']))));
          //$orderDate = date('Y-m-d H:i');

          $sub_cargo_title = ($orderitems["shipmentInfo"]['shipmentCompany']['shortName'] == 'ARAS') ?  'aras' :  strtolower($orderitems["shipmentInfo"]['shipmentCompany']['shortName']);
          $orderPaymentType = ($ma["paymentType"]) ?  '22' :  $ma["paymentType"];

          //count($item_node['customTextOptionValues']) >= 0 ? "KİŞİSELLEŞTİRME BİLGİSİ:{$item_node['customTextOptionValues']['customTextOptionValue']['option']}: {$item_node['customTextOptionValues']['customTextOptionValue']['text']}" : 'mevcut degil';
          $custom_name = str_replace('&','ve',$orderitems['customTextOptionValues']['customTextOptionValue']['text']);
          $custom_title = (count($orderitems['customTextOptionValues']) >= 0 ? "KİŞİSELLEŞTİRME BİLGİSİ:{$orderitems['customTextOptionValues']['customTextOptionValue']['option']}: {$custom_name}" : NULL );
          /*SİPARİŞ DETAYI*/
          echo '<SIPARIS>'."\n";
              /*SİPARİŞ İÇİ*/
               echo '<SIPARIS_NO>'.$oder_sub_info_data["orderDetail"]['orderNumber'].'</SIPARIS_NO>'."\n";
               echo '<SiparisKaynak>n11</SiparisKaynak>'."\n";
               echo '<PAZARYERI_KARGOKODU>'.$orderitems['shipmentInfo']['shipmentCode'].'</PAZARYERI_KARGOKODU>'."\n";
               echo '<CariHesapKodu>'.$CariHesapKodu.'</CariHesapKodu>'."\n";
               echo '<CariHesapOzelKodu>n11</CariHesapOzelKodu>'."\n";
               echo '<tcno>'.$tcid.'</tcno>'."\n";
               echo '<POSTA>'.$order_buyer_info["email"].'</POSTA>'."\n";
               echo '<TARIH>'.$orderDate.'</TARIH>'."\n";
               echo '<sipariszaman>'.$orderDatetime.'</sipariszaman>'."\n";
               echo '<ISKONTO>'.$orderinfoList["totalDiscountAmount"].'</ISKONTO>'."\n";
               echo '<NET_TOPLAM>'.$orderitems["sellerInvoiceAmount"].'</NET_TOPLAM>'."\n";
               echo '<TeslimAlici>'.mb_strtolower($shipdetail["fullName"]).'</TeslimAlici>'."\n";
               echo '<TeslimAdresi>'.strtolower($shipdetail["address"]).'</TeslimAdresi>'."\n";
               echo '<TeslimTelefon></TeslimTelefon>'."\n";
               echo '<teslimsekli>KR</teslimsekli>'."\n";
               echo '<teslimkod1></teslimkod1>'."\n";
               echo '<teslimkod4></teslimkod4>'."\n";
               echo '<teslimil>'.$shipdetail["city"].'</teslimil>'."\n";
               echo '<teslimilce>'.$shipdetail["district"].'</teslimilce>'."\n";
               echo '<faturaalici>'.$order_sub_billingAddress_detail["fullName"].'</faturaalici>'."\n";
               echo '<faturaAdresi>'.$order_sub_billingAddress_detail["address"].'</faturaAdresi>'."\n";
               echo '<faturaTelefon>'.$order_sub_billingAddress_detail["gsm"].'</faturaTelefon>'."\n";
               echo '<faturavergino>'.$order_sub_billingAddress_detail["taxId"].'</faturavergino>'."\n";
               echo '<faturavergidairesi/>'."\n";
               echo '<faturail>'.$order_sub_billingAddress_detail["city"].'</faturail>'."\n";
               echo '<faturailce>'.$order_sub_billingAddress_detail["district"].'</faturailce>'."\n";
               echo '<SIPDURUM_Adi>'.$order_description.'</SIPDURUM_Adi>'."\n";
               echo '<status>'.$order_status.'</status>'."\n";
               echo '<tasiyicifirma_adi>'.$orderitems["shipmentInfo"]['shipmentCompany']["name"].'</tasiyicifirma_adi>'."\n";
               echo '<tasiyicifirma>'.$sub_cargo_title.'</tasiyicifirma>'."\n";
               echo '<kargo_odemesi_adi>Satıcı Öder</kargo_odemesi_adi>'."\n";
               echo '<kargo_odemesi>2</kargo_odemesi>'."\n";
               echo '<kargokodu>'.$orderitems['shipmentInfo']['trackingNumber'].'</kargokodu>'."\n";
               echo '<banka></banka>'."\n";
               echo '<odeme_adi>n11</odeme_adi>'."\n";
               echo '<ODEME_SEKLI>'.$orderPaymentType.'</ODEME_SEKLI>'."\n";
               echo '<not>'.$custom_title.'</not>'."\n";

              /*SATIRLAR*/
              echo '<SATIRLAR>'."\n";
                  echo '<SATIR>'."\n";
                      echo '<KOD>'.$orderitems["productId"].'</KOD>'."\n";
                      echo '<VARKOD/>'."\n";
                      echo '<note/>'."\n";
                      echo '<BARCODE>'.$ma["barcode"].'</BARCODE>'."\n";
                      echo '<URUNADI>'.$orderitems["productName"].'</URUNADI>'."\n";
                      echo '<URUNKODU>'.$orderitems["productSellerCode"].'</URUNKODU>'."\n";
                      echo '<FATURA_ADI>'.$order_sub_billingAddress_detail["fullName"].'</FATURA_ADI>'."\n";
                      echo '<SECENEK_DEGERI/>'."\n";
                      echo '<FIYAT>'.$orderitems["price"].'</FIYAT>'."\n";
                      echo '<MIKTAR>'.$orderitems["quantity"].'</MIKTAR>'."\n";
                      echo '<BIRIM>Ad.</BIRIM>'."\n";
                      echo '<FIYAT>'.$orderitems["sellerInvoiceAmount"].'</FIYAT>'."\n";
                      echo '<KDV>'.$serviceDetail["sellerInvoiceAmount"].'</KDV>'."\n";
                  echo '</SATIR>'."\n";
              echo '</SATIRLAR>'."\n";
          echo '</SIPARIS>'."\n";
      }
      echo '</SIPARISLER>'."\n";


?>
