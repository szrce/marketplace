<?php
include('function.php');

/*
Array
(
    [categories] => Array
        (
            [0] => Array
                (
                    [id] => 368
                    [name] => Aksesuar
                    [parentId] =>
                    [subCategories] => Array
                        (
                            [0] => Array
                                (
                                    [id] => 387
                                    [name] => Saat
                                    [parentId] => 368
                                    [subCategories] => Array
                                        (
                                        )

                                )

                            [1] => Array
                                (
                                    [id] => 394
                                    [name] => Şapka
                                    [parentId] => 368
                                    [subCategories] => Array
                                        (
                                        )

                                )

                            [2] => Array
                                (
                                    [id] => 396
                                    [name] => Takı & Mücevher
                                    [parentId] => 368
                                    [subCategories] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [id] => 397
                                                    [name] => Bileklik
                                                    [parentId] => 396
                                                    [subCategories] => Array
                                                        (
                                                            [0] => Array
                                                                (
                                                                    [id] => 1238
                                                                    [name] => Altın Bileklik
                                                    [parentId] => 397  */
  /*
$categoryList = Request('get_category');


function category_Search($i){
    global $categoryList;

    $category =&$categoryList['categories'][$i];

    echo "{$category['name']} "."<br>";

    for($i=0; $i<count($category); $i++){
        echo $category['subCategories'][$i]['name'] . '<br>';

        if(count($category['subCategories'][$i]['subCategories']) > 0 ){
            echo 1;

        }

    }

}
category_Search(0);
#dd($categoryList['categories'][0]);

$test = array(
      0=>array(
        'name'=>'Aksesuar',
        'subCategories'=> array(
                array('name'=>'saat','subCategories'=>array(array('name'=>'duvar saati'),array('name'=>'kol_saati'))),
                array('name'=>'Şapka'),
                array('name'=>'flarli sapka'),
        ),
      ),
      1=>array(
        'name' => 'etek',
        'subCategories'=> array(
                array('name'=>'uzun etek'),
                array('name'=>'short etek'),
                array('name'=>'polarli etek'),

        ),
      )
);

dd($test);*/

function search_category($test,$i=0,$deep=0){

echo "<ul class='deep deep_".$i."_"."$deep'>";

    for($i=0; $i<count($test); $i++){

      $sublink = $test[$i]["subCategories"] > 0 ? "<a href='#' class='openSubTree' data-target='.deep" . "_" . $i . "_" . ($deep + 1) . "'>+</a>" : '';
      echo "<li class=>
          $sublink
      <a hreF='#' class='sendInput' data-cn='{$test[$i]['name']}'>{$test[$i]['name']}</a>";

      if(count($test[$i]['subCategories']) > 0){
          search_category($test[$i]['subCategories'],$i,$deep+1);
      }
      echo "</li>";
    }
  echo "</ul>";
}

$categoryList = Request('get_category')['categories'];
search_category($categoryList);

#dd($categoryList[9]);
?>
