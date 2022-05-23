<?php  header("Content-type: text/xml; charset=utf-8");

date_default_timezone_set('Etc/GMT-3');

$date1      = date("Y-m-d", strtotime("-5 days"));
$timestamp1 = strtotime($date1);
$goToBefore = $timestamp1."000";


/*GET ORDERS*/
    $url = "https://api.trendyol.com/sapigw/suppliers/111111/orders?orderByDirection=DESC&orderByField=PackageLastModifiedDate&startDate=".$goToBefore."";    /*orders?status=Created&startDate=&endDate=&orderByField=PackageLastModifiedDate&orderByDirection=DESC&size=50";*/
    $ch = curl_init($url);

    //Creating All Trendyol Sokets
    $header = array(
        'Authorization: Basic '. base64_encode('1:1'),
        'Content-Type: application/json'
    );

    //Cheking Trendyol Authorization Information On Server
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";

    echo '<SIPARISLER>'."\n";

    //Getting Loop
    foreach ($result['content'] as $ma){

        $seconds = $ma["orderDate"] / 1000;

        /*SİPARİŞ DETAYI*/
        echo '<SIPARIS>'."\n";
            /*SİPARİŞ İÇİ*/
             echo '<SIPARIS_NO>'.$ma["orderNumber"].'</SIPARIS_NO>'."\n";
             echo '<SiparisKaynak>trendyol</SiparisKaynak>'."\n";
             echo '<PAZARYERI_KARGOKODU>'.$ma["cargoTrackingNumber"].'</PAZARYERI_KARGOKODU>'."\n";
             echo '<CariHesapKodu>'.$ma["customerId"].'</CariHesapKodu>'."\n";
             echo '<CariHesapOzelKodu>trendyol</CariHesapOzelKodu>'."\n";
             echo '<tcno>'.$ma["tcIdentityNumber"].'</tcno>'."\n";
             echo '<POSTA>'.$ma["customerEmail"].'</POSTA>'."\n";
             echo '<TARIH>'.date("Y-m-d", $seconds).'</TARIH>'."\n";
             echo '<sipariszaman>'.date("H:i:s", $seconds).'</sipariszaman>'."\n";
             echo '<ISKONTO>'.$ma["totalDiscount"].'</ISKONTO>'."\n";
             echo '<NET_TOPLAM>'.$ma["totalPrice"].'</NET_TOPLAM>'."\n";
             echo '<TeslimAlici>'.$ma["shipmentAddress"]["firstName"].' '.$ma["shipmentAddress"]["lastName"].'</TeslimAlici>'."\n";
             echo '<TeslimAdresi>'.$ma["shipmentAddress"]["fullAddress"].'</TeslimAdresi>'."\n";
             echo '<TeslimTelefon></TeslimTelefon>'."\n";
             echo '<teslimsekli>KR</teslimsekli>'."\n";
             echo '<teslimkod1></teslimkod1>'."\n";
             echo '<teslimkod4></teslimkod4>'."\n";
             echo '<teslimil>'.$ma["shipmentAddress"]["city"].'</teslimil>'."\n";
             echo '<teslimilce>'.$ma["shipmentAddress"]["district"].'</teslimilce>'."\n";
             echo '<faturaalici>'.$ma["invoiceAddress"]["firstName"].' '.$ma["invoiceAddress"]["lastName"].'</faturaalici>'."\n";
             echo '<faturaAdresi>'.$ma["invoiceAddress"]["fullAddress"].'</faturaAdresi>'."\n";
             echo '<faturaTelefon>'.$ma["invoiceAddress"]["phone"].'</faturaTelefon>'."\n";
             echo '<faturavergino>'.$ma["invoiceAddress"]["taxNumber"].'</faturavergino>'."\n";
             echo '<faturavergidairesi/>'."\n";
             echo '<faturail>'.$ma["invoiceAddress"]["city"].'</faturail>'."\n";
             echo '<faturailce>'.$ma["invoiceAddress"]["district"].'</faturailce>'."\n";
             echo '<SIPDURUM_Adi>'.$ma["status"].'</SIPDURUM_Adi>'."\n";
             echo '<status>1</status>'."\n";
             echo '<tasiyicifirma_adi>'.$ma["cargoProviderName"].'</tasiyicifirma_adi>'."\n";
             echo '<tasiyicifirma>'.$ma["cargoProviderName"].'</tasiyicifirma>'."\n";
             echo '<kargo_odemesi_adi>Satıcı Öder</kargo_odemesi_adi>'."\n";
             echo '<kargo_odemesi>2</kargo_odemesi>'."\n";
             echo '<kargokodu>'.$ma["cargoTrackingNumber"].'</kargokodu>'."\n";
             echo '<banka></banka>'."\n";
             echo '<odeme_adi>Trendyol</odeme_adi>'."\n";
             echo '<ODEME_SEKLI>22</ODEME_SEKLI>'."\n";
             echo '<not></not>'."\n";

            /*SATIRLAR*/
            echo '<SATIRLAR>'."\n";
            foreach ($ma['lines'] as $lin){

                echo '<SATIR>'."\n";
                    echo '<KOD>'.$lin["merchantSku"].'</KOD>'."\n";
                    echo '<VARKOD/>'."\n";
                    echo '<note/>'."\n";
                    echo '<BARCODE>'.$lin["barcode"].'</BARCODE>'."\n";
                    echo '<URUNADI>'.$lin["productName"].'</URUNADI>'."\n";
                    echo '<URUNKODU>'.$lin["productCode"].'</URUNKODU>'."\n";
                    echo '<FATURA_ADI>'.$lin["productName"].'</FATURA_ADI>'."\n";
                    echo '<SECENEK_DEGERI/>'."\n";
                    echo '<FIYAT>'.$lin["amount"].'</FIYAT>'."\n";
                    echo '<MIKTAR>'.$lin["quantity"].'</MIKTAR>'."\n";
                    echo '<BIRIM>Ad.</BIRIM>'."\n";
                    echo '<FIYAT>'.$lin["price"].'</FIYAT>'."\n";
                    echo '<KDV>'.$lin["vatBaseAmount"].'</KDV>'."\n";
                echo '</SATIR>'."\n";
            }

            echo '</SATIRLAR>'."\n";

        echo '</SIPARIS>'."\n";
    }
    /*this end cs csGetorders.php*/
    echo '</SIPARISLER>'."\n";
