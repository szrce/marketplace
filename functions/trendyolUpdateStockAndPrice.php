<?php
require_once("../ciceksepeti/function_new_sezer2.php");

$objConn = dbConn();

$df_id = 1;
$request_type = 'update_product_priceandStock';

date_default_timezone_set('Etc/GMT-3');


function makeArchiveTrendyol($updateData){
      global $objConn;


      $updateDatad = json_decode($updateData,true);


      $pattern[0] = '/^L/';
      $pattern[1] = '/$/';

      $replacement[0] = '"$0';
      $replacement[1] = '"$0';

      $list = array();
      foreach($updateDatad as $key=>$data){
            foreach($data as $key=>$d){
              if($d['barcode'] == 'undefined'){
                continue;
              }
                    array_push($list,preg_replace($pattern, $replacement, $d['barcode']));

            }

      }

      $last_list = implode(',',$list);
      $updateis = $objConn->query("UPDATE urunler set trendyol_isarchive=1  where trendyol='1' and barkod IN($last_list) ");
      if($updateis){
          echo  'urun arsive tasindi';
      }
      mysqli_close($objConn);

      die();
}



function MakeUpdateList($OFFSET,$type=null,$count=null,$postfield=null,$proc=false){
    global $request_type,$df_id,$objConn,$logdata;

      $error = false;
      $whatgonnaUpdate = $objConn->query('select method,type from trendyol_update_stock limit 1');
      $whatgonnaUpdateData = mysqli_fetch_array($whatgonnaUpdate);

      if($type == 'all'){

        //get all data with count. this maybe stupid solve needs update.
          if(!$proc){
              $count = $objConn->query("SELECT DISTINCT urunler.*, urunler.urun_no, urunler.urun_adi, urunler.fiyat as uFiyat, urunler.indirim_fiyat, urunler.rekabet_fiyat, magaza_urunler.*, urun_kategoriler.resim_sekli, kdvler.kdv, markalar.marka, birimler.birim FROM magaza_urunler, urunler, dovizler, kdvler, markalar, birimler, urun_kategoriler WHERE urunler.durum=1 AND urunler.pazaryeri=1 AND trendyol_stock_isupdate=0 AND urunler.trendyol=1 AND magaza_urunler.magaza_id=1 AND urunler.id=magaza_urunler.urun_id AND dovizler.id=urunler.doviz_id AND markalar.id=urunler.marka_id AND kdvler.id=urunler.kdv_id AND birimler.id=urunler.birim_id AND  magaza_urunler.urun_kat_id=urunler.kategori_id AND urun_kategoriler.id=magaza_urunler.urun_kat_id");
              $count = mysqli_num_rows($count);
          }

          //get all data only trendyol for update
          //$strLimit = "ORDER BY urun_no DESC LIMIT 100 OFFSET $OFFSET";
          $strLimit = "$ADD ORDER BY urun_no DESC LIMIT 100 OFFSET $OFFSET";
          $allproduct = $objConn->query("SELECT DISTINCT urunler.*, urunler.urun_no, urunler.urun_adi, urunler.fiyat as uFiyat, urunler.indirim_fiyat, urunler.rekabet_fiyat, magaza_urunler.*, urun_kategoriler.resim_sekli, kdvler.kdv, markalar.marka, birimler.birim FROM magaza_urunler, urunler, dovizler, kdvler, markalar, birimler, urun_kategoriler WHERE urunler.durum=1 AND urunler.pazaryeri=1 AND trendyol_stock_isupdate=0 AND urunler.trendyol=1 AND magaza_urunler.magaza_id=1 AND urunler.id=magaza_urunler.urun_id AND dovizler.id=urunler.doviz_id AND markalar.id=urunler.marka_id AND kdvler.id=urunler.kdv_id AND birimler.id=urunler.birim_id AND  magaza_urunler.urun_kat_id=urunler.kategori_id AND urun_kategoriler.id=magaza_urunler.urun_kat_id $strLimit");

          //loop every produch each hundred
          while($obj=mysqli_fetch_object($allproduct)){
              //printf('%s %s ilk %s icindeki data <br> ',$strLimit,$obj->id,$count);

                $updateList['items'][] = array(
                    'barcode'=>$obj->barkod,
                    'quantity'=>$obj->stok_adedi,
                    'salePrice'=>$whatgonnaUpdateData['method'] = 'updateStockPriceCustom' ? ($obj->rekabet_fiyat == '0.00' ? $obj->indirim_fiyat : $obj->rekabet_fiyat) : $obj->indirim_fiyat ,
                    'listPrice'=>$obj->fiyat
                );

          }
          $postfield = json_encode($updateList,true);
    }

    $result  = Request($df_id,$request_type,$postfield);

    $logdata = 'test';

    if(!empty($result['batchRequestId'])){
        sleep(1);
        $resultd  = Request($df_id,'update_product_state',$postfield,$result['batchRequestId']);



        foreach($resultd['items'] as $updatedVal ){
            $update_status = ($updatedVal['status'] == 'SUCCESS' ? 1 : 0 );
            $is_exist_reason = ($update_status) ? null : $updatedVal['failureReasons'][0];
            $barcode  = $updatedVal['requestItem']['barcode'];
            $date = date('d-m-y H:i:s');
            $updateis = $objConn->query("UPDATE urunler set trendyol_stock_isupdate='$update_status' , trendyol_stock_update_date='$date',trendyol_stock_update_reason='$is_exist_reason' where trendyol='1' and barkod='$barcode' ");
        }


        if($type=='all'){
            $count = $count - 100;
            if($count >= 0){
               //MakeUpdateList($OFFSET,$type=null,$count=null,$postfield=null)

               MakeUpdateList($OFFSET=$OFFSET+100,$type=null,$count=null,$postfield=null,$proc=true);
            }
            mysqli_close($objConn);
        }


    }
    echo 'ok';

}

//$UpdateDataTrendyolPrepared = MakeUpdateList($default_limit);

/*

{
  "items": [
    {
      "barcode": "LD00000053968",
      "quantity": 998,
      "salePrice": 110.99,
      "listPrice": 179.99
    }
  ]
}*/

if(!empty($_POST)){

    $method = @$_POST['method'];
    $type = @$_POST['type'];


    if(!isset($_POST['method'])){
        $type = 'single';

    foreach($_POST as $barcode=>$val){
        $updateList['items'][] = array(
          'barcode'=>$barcode,
          "quantity"=>$val['stock_state'],
          "salePrice"=>$val['price'],
          "listPrice"=>$val['listprice'],
        );


    }
      $updateList = json_encode($updateList,true);
      $method = $val['method'];
      $type = $val['type'];
    }

    //$postfield = json_encode($updateList,true);
    //$result  = Request($df_id,$request_type,$postfield);
    // print_r(json_encode($updateList,true));


    $update_trendyol1 = $objConn->query('delete from trendyol_update_stock');
    $update_trendyol_urunSql =  $objConn->query("INSERT INTO trendyol_update_stock (method, type) VALUES ('".$method."','".$type."')");

    if($type == 'all'){
      MakeUpdateList($OFFSET=0,'all');
    }
    if($type == 'single'){
        MakeUpdateList($OFFSET,'single',$count=null,$updateList,$proc=false);
      //MakeUpdateList($OFFSET=0,'single',$updateList);
    }

    if($type == 'makeArchive'){
        makeArchiveTrendyol($updateList);
    }

    if($type == 'status' && $method=='info'){
      $logdata = !isset($logdata) ? 'not workings' : $logdata;
        print_r($logdata);
    }

}

?>
