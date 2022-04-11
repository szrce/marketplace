<?php

#$res = request("https://api.trendyol.com/sapigw/suppliers/18776/v2/products",1,0);
#$get_brand = request("https://api.trendyol.com/sapigw/brands",0,0);
#$get_category = request("https://api.trendyol.com/sapigw/product-categories",0,0);
#$get_categoryid_categoriId = request("https://api.trendyol.com/sapigw/product-categories/4053/attributes",0,0);
#$get_categoryid_categoriId = request("https://api.trendyol.com/sapigw/product-categories/411/attributes",0,0);

$supplierid = '18776';
$base_url = 'https://api.trendyol.com/sapigw';
$url_type = array('get_sub_category'=>"/product-categories/{catid}/attributes",'get_category'=>'/product-categories','to'=>"suppliers/{$supplierid}/v2/products");


function Request($type,$json = false,$catid=null){
  global $supplierid,$base_url,$url_type;

  if(isset($url_type[$type])){
      $last_url = $url_type[$type];

      if($type == 'get_sub_category'){
        $last_url = str_replace('{catid}', $catid, $last_url);
      }
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
            if($json){
            return ($response);
          }else{
            $response = json_decode($response,true);
            return ($response);
          }
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

/*
function search_category_old($test,$i=0,$deep=0){

echo "<ul class='deep deep_".$i."_"."$deep'>";

    for($i=0; $i<count($test); $i++){

      #dd($test[$i]);
      #$sublink = $test[$i]["subCategories"] > 0 ? "<a href='#' class='openSubTree' data-target='.deep" . "_" . $i . "_" . ($deep + 1) . "'>+</a>" : '';
      $sublink = count($test[$i]["subCategories"]) > 0 ? "+" : "-";
      $deep2id = $i . $deep;

      echo "<li class=open>
        <span class=\"trendyol_span\">{$sublink} {$test[$i]['name']}</span>
        ";
        if(!count($test[$i]["subCategories"]) > 0){
          echo " <span  class=\"d\" id=\"cat_id_$deep2id\">{$test[$i]['id']}</span>";
          //echo "<a href=\"main.php?c=PazaryeriMagazaKategoriler&m=trendyol3&subid={$test[$i]['id']}\"  class=\"d\" id=\"cat_id_$deep2id\">{$test[$i]['id']}</a>";
        }
      /*
      echo "<li class=open>


          $sublink
      <a hreF='#' class='sendInput' data-cn='{$test[$i]['name']}'>{$test[$i]['name']}</a>";*/

      /*
      if(count($test[$i]['subCategories']) > 0){
          search_category($test[$i]['subCategories'],$i,$deep+1);
      }
      echo "</li>";
    }
    echo "</ul>";
}

$test = array(
      0=>array(
        'name'=>'Aksesuar',
        'id'=>'368',
        'subCategories'=> array(
                array('name'=>'saat','parentId' => '368'),
                array('name'=>'Şapka','parentId' => '368'),
                array('name'=>'flarli sapka','parentId' => '368'),
                array('name'=>'Takı & Mücevher','id' => '368',
                   'subCategories'=>array(array('name'=>'Bileklik','id' => '368')),
                ),
        ),
      ),
      1=>array(
        'name' => 'etek',
        'id'=>'2869',
        'subCategories'=> array(
                array('name'=>'uzun etek','parentId' => '2869'),
                array('name'=>'short etek','parentId' => '2869'),
                array('name'=>'polarli etek','parentId' => '2869'),
        ),
      )
);*/

function search_category($test,$i=0,$deep=0,$name,$last){

    for($i=0; $i<count($test); $i++){
        $is_active = ($i == 0) ? 'actived' : null;

      if(!count($test[$i]['subCategories']) >0){
          $d = ($name .' > '. $test[$i]['name']);
          echo "<a id=\"trendyolcategories\" class=\"list-group-item list-group-item-action\" id=\"list-{$test[$i]['id']}-list\"
          data-toggle=\"list\" href=\"#list-{$test[$i]['id']}\" data-sid=\"{$test[$i]['id']}\" parentid=\"{$test[$i]['parentId']}\" role=\"tab\" aria-controls=\"{$test[$i]['name']}\">
          <span id=\"name\">{$d}</span>
          </a>";
      }

        if(count($test[$i]['subCategories']) > 0){
            search_category($test[$i]['subCategories'],$i,$deep+1,$test[$i]['name'],$last=1);
        }
    }

}


/*
function showsub_Category_old($test,$i=0,$deep=0){

echo "<ul class='deep deep_".$i."_"."$deep'>";

  for($i=0; $i<count($test); $i++){

    $sublink = count($test[$i]["attributeValues"]) > 0 ? "+" : "-";
    $deep2id = $i . $deep;

      if(isset($test[$i]['attribute'])){
        if($test[$i]['required']){

          echo "<li class=open>
            <span class=\"trendyol_span\">{$sublink} {$test[$i]['attribute']['name']}</span>
            <span class=\"mainsubid\">{$test[$i]['attribute']['id']}</span>
            ";
        }
      }

      if(!count($test[$i]['attributeValues'])>0){
        echo "<li class=open>";
          echo "<span class=\"description\">{$sublink} {$test[$i]['name']}</span> ";
          echo "<span class=\"catsubid\">{$test[$i]['id']}</span>";
      }
      if(count($test[$i]['attributeValues'])>0){
          if($test[$i]['required']){
              showsub_Category($test[$i]['attributeValues'],$i,$deep=0);
          }
      }


  }
  echo "</ul>";
}*/


function showsub_Category($subcat,$si=0,$sdeep=0){

  for($si=0; $si<count($subcat); $si++){

          if(isset($subcat[$si]['attribute'])){
            //if($subcat[$si]['required']){

                if(!count($subcat[$si]['attributeValues']) > 0){
                  echo "<div class=\"form-group\">
                    <label for=\"exampleInputEmail1\">{$subcat[$si]['attribute']['name']}</label>
                    <input type=\"email\" class=\"form-control\" id=\"exampleInputEmail1\" aria-describedby=\"emailHelp\">
                    <small id=\"emailHelp\" class=\"form-text text-muted\">We'll never share your email with anyone else.</small>
                  </div>";
                  continue;
                }
                $is_required = ($subcat[$si]['required']) ? '<span style="color:red">*</span>' : null;
                echo "<div class=\"form-group\">
                  <label for=\"exampleFormControlSelect1\">{$subcat[$si]['attribute']['name']} {$is_required}</label>
                  <select class=\"form-control\" id=\"exampleFormControlSelect1\">";

                foreach($subcat[$si]['attributeValues'] as $val){
                    echo "<option>{$val['name']}</option>";
                }
                echo '</select>
                </div>';
          //}
        }

  }
}

if($_POST['get_sub_category']){
  if(!empty($subid = $_POST['get_sub_category']) && $subid !== '' ){
      $data = Request('get_sub_category',$json = false,$subid)['categoryAttributes'];
      showsub_Category($data,0,0);
  }
}

?>
