<?php

#$res = request("https://api.trendyol.com/sapigw/suppliers/187768/v2/products",1,0);
#$get_brand = request("https://api.trendyol.com/sapigw/brands",0,0);
#$get_category = request("https://api.trendyol.com/sapigw/product-categories",0,0);
#$get_categoryid_categoriId = request("https://api.trendyol.com/sapigw/product-categories/4053/attributes",0,0);
#$get_categoryid_categoriId = request("https://api.trendyol.com/sapigw/product-categories/411/attributes",0,0);

$supplierid = '18776';
$base_url = 'https://api.trendyol.com/sapigw';
$url_type = array('get_sub_category' => "/product-categories/{catid}/attributes", 'get_category' => '/product-categories', 'to' => "suppliers/{$supplierid}/v2/products");


function Request($type, $json = false, $catid = null)
{
    global $supplierid, $base_url, $url_type;

    if (isset($url_type[$type])) {
        $last_url = $url_type[$type];

        if ($type == 'get_sub_category') {
            $last_url = str_replace('{catid}', $catid, $last_url);
        }
        $url  = $base_url . $last_url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . base64_encode('VNulVs4xqzZXsfmw7OeM:GFb0z5NeFPq9yQZapqUw'),
            'Content-Type: application/json'
        ));


        $response = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            if ($json) {
                return ($response);
            } else {
                $response = json_decode($response, true);
                return ($response);
            }
        }
    }
}

function dd($dd)
{

    echo '<pre>';
    if (is_array($dd)) {
        print_r($dd);
    } else {
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
*/
$test = array(
    0 => array(
        'name' => 'Aksesuar',
        'id' => '368',
        'subCategories' => array(
            array('name' => 'saat', 'parentId' => '387'),
            array('name' => 'Şapka', 'parentId' => '368'),
            array('name' => 'flarli sapka', 'parentId' => '368'),
            array(
                'name' => 'Takı & Mücevher', 'id' => '387',
                'subCategories' => array(array('name' => 'Bileklik', 'id' => '4053', 'parentId' => '4052')),
            ),
        ),
    ),
    1 => array(
        'name' => 'etek',
        'id' => '2869',
        'subCategories' => array(
            array('name' => 'uzun etek', 'parentId' => '2869'),
            array('name' => 'short etek', 'parentId' => '2869'),
            array('name' => 'polarli etek', 'parentId' => '2869'),
        ),
    )
);

function search_category($test, $i = 0, $deep = 0, $name, $last)
{

    for ($i = 0; $i < count($test); $i++) {
        $is_active = ($i == 0) ? 'actived' : null;

        if (!count($test[$i]['subCategories']) > 0) {
            $d = ($name . ' > ' . $test[$i]['name']);
            echo "<a id=\"trendyol_categories\" class=\"list-group-item list-group-item-action\" id=\"list-{$test[$i]['id']}-list\"
          data-toggle=\"list\" href=\"#list-{$test[$i]['id']}\" data-sid=\"{$test[$i]['id']}\" parentid=\"{$test[$i]['parentId']}\" role=\"tab\" aria-controls=\"{$test[$i]['name']}\">
          <span id=\"name\">{$d}</span>
          </a>";
        }

        if (count($test[$i]['subCategories']) > 0) {
            search_category($test[$i]['subCategories'], $i, $deep + 1, $test[$i]['name'], $last = 1);
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


function showsub_Category($subcat, $si = 0, $sdeep = 0, $selected_field = [])
{
    //dd($subcat);
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

    for ($si = 0; $si < count($subcat); $si++) {

        if (isset($subcat[$si]['attribute'])) {
            //if($subcat[$si]['required']){


            if (!count($subcat[$si]['attributeValues']) > 0) {
                $selected_key = array_search($subcat[$si]['attribute']['id'], array_column($selected_field, 'attributeId'));
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
                  <select name=\"{$subcat[$si]['attribute']['name']}_{$subcat[$si]['attribute']['id']}\" class=\"form-control selectVal\" id=\"selectVal\" >";

            foreach ($subcat[$si]['attributeValues'] as $val) {
                $selected_key_option = array_search($val['id'], array_column($selected_field, 'attributeValueId'));
                if (!$selected_key_option == false) {
                    $exist_value_option = ($selected_field[$selected_key_option]['selected_element']);
                    echo "<option selected value=\"{$val['name']}_{$val['id']}\">{$val['name']}</option>";
                } else {
                    echo "<option value=\"{$val['name']}_{$val['id']}\">{$val['name']}</option>";
                }
            }
            echo '</select>
                </div>';
        }
        //  }

    }
}

/*dont forget pass*/

function check_data($us_id, $us_storeid)
{

    if (!empty($us_id)) {
        $objConn = dbConn();

        //$strSQL = "select us_catid,us_storeid from trendyol_category_info where us_catid=$us_id and us_storeid=$us_storeid";
        $strSQL = "detele from trendyol_category_info where us_catid=$us_id and us_storeid=$us_storeid";
        $trendyol_category_infos = mysql_query($strSQL);
        $is_exist = mysql_fetch_array($trendyol_category_infos);
        if (count($is_exist) > 0 && $is_exist !== false) {
            return false;
        }
        return true;
    }
}

if ($_POST['save_trendyolcat']) {


    $strVal = "";
    $strKey = "";

    $checkfield = array('save_trendyolcat', 'trendyol_catid', 'us_catid', 'us_storeid');

    $us_catid = $_POST['us_catid'];
    $us_storeid = $_POST['us_storeid'];

    function parse_element($str)
    {

        foreach ($str as $key => $val) {
            $d[$key] = array(
                'attributeId' => explode('_', $val['name'])[1],
                'customAttributeValue' => !isset(explode('_', $val['value'])[1]) ? $val['value'] : null,
                'attributeValueId' => explode('_', $val['value'])[1],
                'selected_element' => explode('_', $val['value'])[0],
                'element_name' => explode('_', $val['name'])[0],

            );
        }
        return json_encode($d);
    }


    $trendyol_data_x = parse_element($_POST['trendyol_data']);
    $_POST['trendyol_data'] = $trendyol_data_x;

    foreach ($checkfield as $field) {
        if (empty($_POST[$field])) {
            $error = 1;
        }
    }
    if (!$check) {
        unset($_POST['save_trendyolcat']);
        foreach ($_POST as $key => $val) {
            $strKey .=     $key . ",";
            $strVal .=     "'" . $val . "',";
        }
        $strKey = rtrim($strKey, ',');
        $strVal = rtrim($strVal, ',');

        $objConn = dbConn();

        /*check same data*/
        //echo "INSERT INTO trendyol_category_info (".$strKey.") VALUES (".$strVal.")";


        if (check_data($us_catid, $us_storeid)) {
            $strSQL = "INSERT INTO trendyol_category_info(" . $strKey . ") VALUES (" . $strVal . ")";
            if (mysql_query($strSQL)) {
                echo '<div class="alert alert-primary" role="alert">
                     Kayıt Yapıldı
                    </div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">
              Bu kategori özelliği  daha evel kaydedilmiş
            </div>';
        }
    }
}

if ($_POST['get_sub_category']) {
    if (!empty($subid = $_POST['get_sub_category']) && $subid !== '') {
        $selected_field = get_field($subid);
        $data = Request('get_sub_category', $json = false, $subid)['categoryAttributes'];
        showsub_Category($data, 0, 0, $selected_field);
    }
}

function get_field($trendyolid)
{

    $objConn = dbConn();

    $strSQL = "select trendyol_data from trendyol_category_info where trendyol_catid=$trendyolid";
    $trendyol_category_infos = mysql_query($strSQL);
    $is_exist = mysql_fetch_array($trendyol_category_infos)[0];

    if (!empty($is_exist)) {
        return json_decode($is_exist, true);
    }
}
