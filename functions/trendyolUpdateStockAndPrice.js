function trendyol_function_checkall(){

	$('input[type=checkbox]').each(function () {
		if($(this).attr('id') !== 'trendyol_function_checkall_checkbox' && $(this).attr('id') !== 'checkbox'){

				var id = $(this).attr('id');
				console.log(id);
				$('#'+id).trigger('click');
		}
	}
)};


function	trendyolpriceCheckType(el){

	if($(el).attr('id') == 'updateStockPriceCustom'){

		$('input[type=checkbox]').each(function () {

			if (this.checked && this.id !=='trendyol_function_checkall_checkbox') {
				if($(this).data('price_old') !== '0.00'){
						$(this).data('price',$(this).data('price_old'));
						//console.log(1)
				}
			}
		});


	}
	/*
	var realvalues = new Array();
	var product_code  = ($(el).data('product_code'));
	var price = ($(el).data('price'));
	var list = ($(el).data('listprice'));
	var barcode = ($(el).data('barkod'));

	realvalues[product_code] = price;*/
}
function trendyolpriceCheck(singlethis){

		$("#AllSelectcheckbox").prop('checked',false);
		$("#updateStockPriceCustom").prop('checked',false);
		$("#updateStockPriceCustom").prop('checked',false);
		//console.log($(singlethis).data());
}

function trendyolupdatePrice(){

//is(":checked")

	var isSelectedAlldata = $("#AllSelectcheckbox").is(":checked");
	var isSelectedArchive = $("#makeArchieTrendyol").is(":checked");

	//if selected isSelectedAlldata
	var update_product = {};

		if(isSelectedAlldata){
				if($("#updateStockPrice").is(":checked")){
						var v_method = 'updateStockPrice';
				}
				if($("#updateStockPriceCustom").is(":checked")){
						var v_method = 'updateStockPriceCustom';
				}
				var v_type = 'all';

				update_product = {
					method: v_method,
					type:v_type
				};

		}


	/*
	var update_product = {};*/
if(!isSelectedAlldata){


	$('input[type=checkbox]').each(function () {
	     if (this.checked) {

		var v_type = 'single';
		var vprice =  $(this).data('price');
		var vlistprice = $(this).data('listprice');
		var vbarcode = $(this).data('barkod');
		var vproduct_code  = ($(this).data('product_code'));
		var vstock_state  = ($(this).data('stock_state'));

		if($("#updateStockPrice").is(":checked")){
			var v_method = 'updateStockPrice';
		}
		if($("#updateStockPriceCustom").is(":checked")){
				var v_method = 'updateStockPriceCustom';
		}
		f($("#makeArchieTrendyol").is(":checked")){
				var v_method = 'updateArchiveCustom';
				var v_type = 'makeArchive';
		}

		update_product[vbarcode] = {
		listprice: vlistprice,
		price:vprice,
		product_code:vproduct_code,
		stock_state:vstock_state,
		method:v_method,
		type:v_type
		
		};
	}


	});
}

	$.ajax({
		type: "POST",
		url: "/trendyol/trendyol_updateStockPrice0.php",
		data:update_product,
		//dataType: "json",
		success: function(message){
			if(message==0){
				alert("Hata oluştu tekrar deneyin.");
			} else {
				console.log(message);
				//alert(message);
			}
		}
	});
	 //console.log(update_product);
}

update_product = {
	method: 'info',
	type:'status'
};


var interval = 10000;
function reqss(){
	$.ajax({
		type: "POST",
		url: "/trendyol/trendyol_updateStockPrice0.php",
		data:update_product,
		//dataType: "json",
		success: function(message){
			if(message==0){
				alert("Hata oluştu tekrar deneyin.");
			} else {
					console.log('process:' + message);
					$("#updatetrendyolprocess").text('process:' + message);
			}
		},complete: function (message) {
							// Schedule the next
							setTimeout(reqss, interval);
			}
	});

}
setTimeout(reqss, interval);
