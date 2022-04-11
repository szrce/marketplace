
<!--sezer change for search*/-->
<script>

  $("#exampleInputsearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#trendyolcategories span").filter(function() {
			$(this).parents('a').toggle($(this).text().toLowerCase().indexOf(value) > -1)
			//console.log($(this));
    });
  });

$("a#trendyolcategories.list-group-item.list-group-item-action").click(function(){
		$subcatid = ($(this).data('sid'));

		$.post("modules/PazaryeriMagazaKategoriler/custom_trendyol_helper_sezer/function_trendyol.php",{get_sub_category: $subcatid},function(data, status){
    	//alert("Data: " + data + "\nStatus: " + status);

				if(data == ''){
						$("#subcatform").html('there is no subCategories');
				}else{
					$("#subcatform").html(data);
				}

  	});

	});

</script>
<!--sezer end for search*/-->
