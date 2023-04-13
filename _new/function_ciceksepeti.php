
$base_url = 'https://apis.ciceksepeti.com/api/v1';
$url_type = array('get_sub_category'=>"/Categories/{catid}/attributes",'get_category'=>'/Categories');


function ciceksepeti_Request($type,$json = false,$catid=null){
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
        'x-api-key:ZFIPSw5lW89W4Xy0tGPq04I6uaoRZBQL6t6Vg9cc',
        'Content-Type:application/json'));


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
*/
$test = array(
      0=>array(
        'name'=>'Aksesuar',
        'id'=>'368',
        'subCategories'=> array(
                array('name'=>'saat','parentId' => '387'),
                array('name'=>'Şapka','parentId' => '368'),
                array('name'=>'flarli sapka','parentId' => '368'),
                array('name'=>'Takı & Mücevher','id' => '387',
                   'subCategories'=>array(array('name'=>'Bileklik','id' => '4053','parentId' => '4052')),
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
);

function ciceksepeti_search_category($test,$i=0,$deep=0,$name,$last){


    for($i=0; $i<count($test); $i++){

        $is_active = ($i == 0) ? 'actived' : null;

      if(!count($test[$i]['subCategories']) >0){
          $d = ($name .' > '. $test[$i]['name']);
          echo "<a id=\"ciceksepeti_categories\" class=\"list-group-item list-group-item-action\" id=\"list-{$test[$i]['id']}-list\"
          data-toggle=\"list\" href=\"#list-{$test[$i]['id']}\" data-sid=\"{$test[$i]['id']}\" parentid=\"{$test[$i]['parentId']}\" role=\"tab\" aria-controls=\"{$test[$i]['name']}\">
          <span id=\"name\">{$d}</span>
          </a>";
      }

        if(count($test[$i]['subCategories']) > 0){
            ciceksepeti_search_category($test[$i]['subCategories'],$i,$deep+1,$test[$i]['name'],$last=1);
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


function ciceksepeti_showsub_Category($subcat,$si=0,$sdeep=0,$selected_field =[]){

/*
Array
(
    [0] => Array
        (
            [attributeId] => 47
            [customAttributeValue] => beyaz
            [attributeValueId] =>
            [selected_element] => beyaz
            [element_name] => Renk
        )

    [1] => Array
        (
            [attributeId] => 782
            [customAttributeValue] =>
            [attributeValueId] => 315413
            [selected_element] => 13.6
            [element_name] => Desi
        )

)*/

  for($si=0; $si<count($subcat); $si++){


		if($subcat[$si]['required']){


          //if(isset($subcat[$si]['attribute'])){

						//print_r($subcat[$si]);

                if(!count($subcat[$si]['attributeValues']) > 0){
									$selected_key = array_search($subcat[$si]['attribute']['id'],array_column($selected_field,'attributeId'));
									$exist_value = ($selected_field[$selected_key]['selected_element']);
									$is_need_val = (!empty($exist_value) ? "value=$exist_value" : null);

                  echo "<div class=\"form-group\">
                    <label for=\"exampleInputEmail1\">{$subcat[$si]['attribute']['name']}</label>
                    <input $is_need_val name=\"{$subcat[$si]['attribute']['name']}_{$subcat[$si]['attribute']['id']}\"  class=\"form-control\"  data-val=\"attributeId\" value=\"\" aria-describedby=\"emailHelp\">
                    <small id=\"emailHelp\" class=\"form-text text-muted\">We'll never share your email with anyone else.</small>
                  </div>";
                  continue;
                }
                $is_required = ($subcat[$si]['required']) ? '<span style="color:red">*</span>' : null;
                echo "<div class=\"form-group\">
                  <label for=\"exampleFormControlSelect1\">{$subcat[$si]['attribute']['name']} {$is_required}</label>
                  <select name=\"{$subcat[$si]['attributeName']}_{$subcat[$si]['attributeId']}\" class=\"form-control selectVal\" id=\"selectVal\" >";

                foreach($subcat[$si]['attributeValues'] as $val){
										$selected_key_option = array_search($val['id'],array_column($selected_field,'attributeValueId'));
										if(!$selected_key_option == false){
											$exist_value_option = ($selected_field[$selected_key_option]['selected_element']);
												  echo "<option selected value=\"{$val['name']}_{$val['id']}\">{$val['name']}</option>";
										}else{
											  echo "<option value=\"{$val['name']}_{$val['id']}\">{$val['name']}</option>";
										}


                }
                echo '</select>
                </div>';
								//}
					}


  }
}

/*dont forget pass*/

function ciceksepeti_check_data($us_id,$us_storeid){

    if(!empty($us_id)){
          $objConn = dbConn();

          $strSQL = "select us_catid,us_storeid from ciceksepeti_category_info where us_catid=$us_id and us_storeid=$us_storeid";
          $ciceksepeti_category_infos = mysql_query($strSQL);
          $is_exist = mysql_fetch_array($ciceksepeti_category_infos);
          if(count($is_exist) > 0 && $is_exist !==false ){
              return false;
          }
          return true;
    }
}

if($_POST['save_ciceksepeticat']){


  $strVal = "";
	$strKey = "";

  $checkfield = array('save_ciceksepeticat','ciceksepeti_catid','us_catid','us_storeid');

  $us_catid = $_POST['us_catid'];
  $us_storeid = $_POST['us_storeid'];

	function parse_element($str){

			foreach($str as $key=>$val){
					$d[$key] = array(
						'attributeId'=>explode('_',$val['name'])[1],
						'customAttributeValue'=>!isset(explode('_',$val['value'])[1]) ? $val['value'] : null,
						'attributeValueId'=>explode('_',$val['value'])[1],
						'selected_element'=>explode('_',$val['value'])[0],
						'element_name'=>explode('_',$val['name'])[0],

					);

			}
			return json_encode($d);
	}


	$ciceksepeti_data_x =parse_element($_POST['ciceksepeti_data']);
	$_POST['ciceksepeti_data'] = $ciceksepeti_data_x;

  foreach($checkfield as $field){
    if(empty($_POST[$field])){
        $error = 1;
    }
  }

  if(!$check){
      unset($_POST['save_ciceksepeticat']);
      foreach($_POST as $key => $val) {
        $strKey .= 	$key . ",";
        $strVal .= 	"'" . $val . "',";
      }
        $strKey = rtrim($strKey,',');
        $strVal = rtrim($strVal,',');

        $objConn = dbConn();

        /*check same data*/
        //echo "INSERT INTO trendyol_category_info (".$strKey.") VALUES (".$strVal.")";

				//print_r($strKey);
				//die();
      if(ciceksepeti_check_data($us_catid,$us_storeid)){
           $strSQL = "INSERT INTO ciceksepeti_category_info(".$strKey.") VALUES (".$strVal.")";
            if(mysql_query($strSQL)){
                echo '<div class="alert alert-primary" role="alert">
                     Kayıt Yapıldı
                    </div>';
            }
      }else{
        echo '<div class="alert alert-danger" role="alert">
              Bu kategori özelliği  daha evel kaydedilmiş
            </div>';
      }
  }
}

if($_POST['get_sub_category']){
  if(!empty($subid = $_POST['get_sub_category']) && $subid !== '' ){
			$selected_field = ciceksepeti_get_field($subid);
      $data = ciceksepeti_Request('get_sub_category',$json = false,$subid)['categoryAttributes'];
			ciceksepeti_showsub_Category($data,0,0,$selected_field);
  }
}

function ciceksepeti_get_field($ciceksepetid){

	$objConn = dbConn();

	$strSQL = "select ciceksepeti_data from ciceksepeti_category_info where ciceksepeti_catid=$ciceksepetid";
	$ciceksepeti_category_infos = mysql_query($strSQL);
	$is_exist = mysql_fetch_array($ciceksepeti_category_infos)[0];

	if(!empty($is_exist)){
		return json_decode($is_exist,true);
	}

}

?>
