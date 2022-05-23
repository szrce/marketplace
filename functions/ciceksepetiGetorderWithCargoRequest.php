<?php
require_once('requests.php');
header("Content-type: text/xml; charset=utf-8");

#date_default_timezone_set('Etc/GMT+3');
date_default_timezone_set('Europe/Istanbul');

//just no get order
//$olds ="2022-04-17T05:59:00.000Z";

$date1      = date("Y-m-d H:i:s", strtotime("-5 days"));
$date2      = date("Y-m-d H:i:s");

$postfield = new stdClass();
$postfield->startDate = $date1;
$postfield->endDate =  $date2;
$postfield->pageSize=10;
$postfield->page=0;
$postfield->statusId=1;

//1 ciceksepeti
$df_id = 3;
$request_type = get_order;
$postfield = json_encode($postfield);

$result  = Request($df_id,$request_type,$postfield);
$CariHesapKodu =  'C'.rand(100000000,999999999);

//echo '<TARIH>'.date("Y-m-d", $seconds).'</TARIH>'."\n";
//echo '<sipariszaman>'.date("H:i:s", $seconds).'</sipariszaman>'."\n";

/*GET ORDERS variable*/

function request_cargo_number($orderid){
  global $df_id;
$request_type = 'make_package';

$reg = '{
  "orderItemsGroup": [
    {
      "orderItemIds": [
       '.$orderid.'
      ]
    }
  ]

}';

$result  = Request($df_id,$request_type,$reg)['statusUpdateResponse'][0]['orderItems'][0]['partialNumber'];

  if(!empty($result)){
    return $result;
  }
  return '111111000000';
}

    echo  '<?xml version="1.0" encoding="UTF-8"?>'."\n";

    echo '<SIPARISLER>'."\n";

    //Getting Loop
    foreach ($result['supplierOrderListWithBranch'] as $ma){

        $orderDate =(date('Y-m-d',strtotime(str_replace('/', '-', $ma['orderCreateDate']))));

        if(empty($ma['cargoNumber']) && empty($ma['partialNumber'])){
            $cargoN =  request_cargo_number($ma["orderItemId"]);
        }else{
            $cargoN = (empty($ma['cargoNumber'])) ? $ma['partialNumber'] : $ma['cargoNumber'];
        }

        $sub_cargo_title = ($ma["cargoCompany"] == 'Aras Kargo') ?  'aras' :  $ma["cargoCompany"];

        $orderPaymentType = ($ma["orderPaymentType"] == 'Kredi Kartı İle Ödeme') ?  '22' :  $ma["orderPaymentType"];
        $custom_title = "KİŞİSELLEŞTİRME BİLGİSİ:{$ma['orderItemTextListModel'][0]['value']}:{$ma['orderItemTextListModel'][0]['text']}";
        /*SİPARİŞ DETAYI*/
        echo '<SIPARIS>'."\n";
            /*SİPARİŞ İÇİ*/
             echo '<SIPARIS_NO>'.$ma["orderItemId"].'</SIPARIS_NO>'."\n";
             echo '<SiparisKaynak>ciceksepeti</SiparisKaynak>'."\n";
             echo '<PAZARYERI_KARGOKODU>'.$cargoN.'</PAZARYERI_KARGOKODU>'."\n";
             echo '<CariHesapKodu>'.$CariHesapKodu.'</CariHesapKodu>'."\n";
             echo '<CariHesapOzelKodu>ciceksepeti</CariHesapOzelKodu>'."\n";
             echo '<tcno>'.$ma["tcIdentityNumber"].'</tcno>'."\n";
             echo '<POSTA>'.$ma["invoiceEmail"].'</POSTA>'."\n";
             echo '<TARIH>'.$orderDate.'</TARIH>'."\n";
             echo '<sipariszaman>'.$ma['orderCreateTime'].'</sipariszaman>'."\n";
             echo '<ISKONTO>'.$ma["discount"].'</ISKONTO>'."\n";
             echo '<NET_TOPLAM>'.$ma["totalPrice"].'</NET_TOPLAM>'."\n";
             echo '<TeslimAlici>'.$ma["receiverName"].'</TeslimAlici>'."\n";
             echo '<TeslimAdresi>'.$ma["receiverAddress"].'</TeslimAdresi>'."\n";
             echo '<TeslimTelefon></TeslimTelefon>'."\n";
             echo '<teslimsekli>KR</teslimsekli>'."\n";
             echo '<teslimkod1></teslimkod1>'."\n";
             echo '<teslimkod4></teslimkod4>'."\n";
             echo '<teslimil>'.$ma["receiverCity"].'</teslimil>'."\n";
             echo '<teslimilce>'.$ma["receiverDistrict"].'</teslimilce>'."\n";
             echo '<faturaalici>'.$ma["senderName"].'</faturaalici>'."\n";
             echo '<faturaAdresi>'.$ma["receiverAddress"].'</faturaAdresi>'."\n";
             echo '<faturaTelefon>'.$ma["invoiceAddress"]["phone"].'</faturaTelefon>'."\n";
             echo '<faturavergino>'.$ma["invoiceAddress"]["taxNumber"].'</faturavergino>'."\n";
             echo '<faturavergidairesi/>'."\n";
             echo '<faturail>'.$ma["receiverCity"].'</faturail>'."\n";
             echo '<faturailce>'.$ma["receiverDistrict"].'</faturailce>'."\n";
             echo '<SIPDURUM_Adi>Yeni Sipariş</SIPDURUM_Adi>'."\n";
             echo '<status>1</status>'."\n";
             echo '<tasiyicifirma_adi>'.$ma["cargoCompany"].'</tasiyicifirma_adi>'."\n";
             echo '<tasiyicifirma>'.$sub_cargo_title.'</tasiyicifirma>'."\n";
             echo '<kargo_odemesi_adi>Satıcı Öder</kargo_odemesi_adi>'."\n";
             echo '<kargo_odemesi>2</kargo_odemesi>'."\n";
             echo '<kargokodu>'.$cargoN.'</kargokodu>'."\n";
             echo '<banka></banka>'."\n";
             echo '<odeme_adi>Çiçeksepeti</odeme_adi>'."\n";
             echo '<ODEME_SEKLI>'.$ma["orderPaymentType"].'</ODEME_SEKLI>'."\n";
             echo '<not>'.$custom_title.'</not>'."\n";

            /*SATIRLAR*/
            echo '<SATIRLAR>'."\n";


                echo '<SATIR>'."\n";
                    echo '<KOD>'.$ma["code"].'</KOD>'."\n";
                    echo '<VARKOD/>'."\n";
                    echo '<note/>'."\n";
                    echo '<BARCODE>'.$ma["barcode"].'</BARCODE>'."\n";
                    echo '<URUNADI>'.$ma["name"].'</URUNADI>'."\n";
                    echo '<URUNKODU>'.$ma["code"].'</URUNKODU>'."\n";
                    echo '<FATURA_ADI>'.$ma["name"].'</FATURA_ADI>'."\n";
                    echo '<SECENEK_DEGERI/>'."\n";
                    echo '<FIYAT>'.$ma["invoicePrice"].'</FIYAT>'."\n";
                    echo '<MIKTAR>'.$ma["quantity"].'</MIKTAR>'."\n";
                    echo '<BIRIM>Ad.</BIRIM>'."\n";
                    echo '<FIYAT>'.$ma["itemPrice"].'</FIYAT>'."\n";
                    echo '<KDV>'.$ma["tax"].'</KDV>'."\n";
                echo '</SATIR>'."\n";


            echo '</SATIRLAR>'."\n";

        echo '</SIPARIS>'."\n";
    }
    /*this end cs n11Getorders.php*/
    echo '</SIPARISLER>'."\n";
