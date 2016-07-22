$(document).ready(function() {
    
    var name = sessionStorage.getItem('name');
    var compIdArray = (JSON.parse(sessionStorage.getItem('companyIdString')));
    var prodIdArray = (JSON.parse(sessionStorage.getItem('productIdString')));
    var prodNameArray = (JSON.parse(sessionStorage.getItem('productNameString')));
    var prodBrandArray = (JSON.parse(sessionStorage.getItem('productBrandString')));
    var prodDescriptionArray = (JSON.parse(sessionStorage.getItem('productDescriptionString')));
    var productPriceArray = (JSON.parse(sessionStorage.getItem('productPriceString')));
    var productQuantityArray = (JSON.parse(sessionStorage.getItem('productQuantityString')));
    
    var total = 0;
    

    console.log("Name: " + name);
    console.log("Company ID: " + compIdArray);
    console.log("Product IDs: " + prodIdArray);
    console.log("Product Names: " + prodNameArray);
    console.log("Product Brands: " + prodBrandArray);
    console.log("Product Descriptions: " + prodDescriptionArray);
    console.log("Product Prices: " + productPriceArray);
    console.log("Product Quantities: " + productQuantityArray);
    
    //function to calculate the total cost
	function calcTotal() {
		$(productPriceArray).each(function (i) {
			var price = Number(productPriceArray[i]);
			var quantity = Number(productQuantityArray[i]);
			total += (price * quantity);
			document.getElementById("total").innerHTML = "Total: " + "$" + total.toFixed(2);
		});
        
	}
    
    //Function to build the shopping cart table
	function writeTable() {
    var body = $('#finalOrder');
		for (var i = 0; i < productPriceArray.length; i++) {
			var tr = $('<tr/>').appendTo(body);
			tr.append("<td style='display:none;'><input type='text' class='cids' name='company_ids[] ' value= " + "'" + compIdArray[i] + "'" + " readonly></td>").
			append("<td style='display:none;'><input type='text' class='pids' name='product_ids[] ' value= " + "'" + prodIdArray[i] + "'" + " readonly></td>").append("<td><input type='text' class='pnames' name='product_names[] ' value= " + "'" + prodNameArray[i] + "'" + " readonly></td>")
			.append("<td><input type='text' class='pbrands' name='product_brands[] ' value= " + "'" + prodBrandArray[i] + "'" + " readonly></td>").append("<td><input type='text' class='pdescriptions' name='product_descriptions[] ' value= " + "'" + prodDescriptionArray[i] + "'" + " readonly></td>").append("<td><input type='text' class='pprices' name='product_prices[] ' value= " + "'" + productPriceArray[i] + "'" + " readonly></td>").append("<td><input type='text' class='pamounts' name='product_amounts_ordered[] ' value= " + "'" + productQuantityArray[i] + "'" + " readonly></td>").
			append('<td style="border: none;"><a href="#" class="remove_field">Remove</a></td>');
		}
	}
    
    writeTable();
    calcTotal();
    
    //Functionality to remove an item from the cart (removes each form input from the DOM by this row and the session stored arrays)
	$('.remove_field').click(function() {

		var $row = $(this).closest('tr');
		var quantity = Number($row.find(".pamounts").val());
		var price = Number($row.find(".pprices").val());
		
		total -= (price * quantity);

		document.getElementById("total").innerHTML = "Total: " + "$" + total.toFixed(2);
		
		$row.remove();
		
		var companyIdIndex = compIdArray.indexOf($row.find(".cids").val());
		var productIdIndex = prodIdArray.indexOf($row.find(".pids").val());
        var productNameIndex = prodNameArray.indexOf($row.find(".pnames").val());
        var productBrandIndex = prodBrandArray.indexOf($row.find(".pbrands").val());
        var productDescriptionIndex = prodDescriptionArray.indexOf($row.find(".pdescriptions").val());
        var productPriceIndex = productPriceArray.indexOf($row.find(".pprices").val());
        var productQuantityIndex = productQuantityArray.indexOf($row.find(".pamounts").val());
        
		var tmp_cidsSaved = (JSON.parse(sessionStorage.getItem('companyIdString')));
		var tmp_pidsSaved = (JSON.parse(sessionStorage.getItem('productIdString')));
		var tmp_pnamesSaved = (JSON.parse(sessionStorage.getItem('productNameString')));
		var tmp_pbrandsSaved = (JSON.parse(sessionStorage.getItem('productBrandString')));
		var tmp_pdescriptionsSaved = (JSON.parse(sessionStorage.getItem('productDescriptionString')));
        var tmp_ppricesSaved = (JSON.parse(sessionStorage.getItem('productPriceString')));
        var tmp_amountsSaved = (JSON.parse(sessionStorage.getItem('productQuantityString')));
        
        tmp_cidsSaved.splice(companyIdIndex, 1);
        tmp_pidsSaved.splice(productIdIndex, 1);
        tmp_pnamesSaved.splice(productNameIndex, 1);
        tmp_pbrandsSaved.splice(productBrandIndex, 1);
        tmp_pdescriptionsSaved.splice(productDescriptionIndex, 1);
        tmp_ppricesSaved.splice(productPriceIndex, 1);
        tmp_amountsSaved.splice(productQuantityIndex, 1);
        
        sessionStorage.setItem('companyIdString', JSON.stringify(tmp_cidsSaved));
		sessionStorage.setItem('productIdString', JSON.stringify(tmp_pidsSaved)); 
		sessionStorage.setItem('productNameString', JSON.stringify(tmp_pnamesSaved)); 
		sessionStorage.setItem('productBrandString', JSON.stringify(tmp_pbrandsSaved));
		sessionStorage.setItem('productDescriptionString', JSON.stringify(tmp_pdescriptionsSaved));
        sessionStorage.setItem('productPriceString', JSON.stringify(tmp_ppricesSaved));
        sessionStorage.setItem('productQuantityString', JSON.stringify(tmp_amountsSaved)); 

	});
    
    //Submit final order
    //"http://localhost:88/senior_project_testing/customer_main.php"
	$("#submit1").button().click(function() {  
		
		 // Get form elements values 
		var values = $('#form1').serialize(); 
		//Use ajax to push form data to be processed by PHP, on success of POST, user has completed order and will be redirected to deli kiosk start page
		$.ajax({
			url: "../../customer_main.php",
			type: "post",
			data: values ,
			success: function () {     
				$('#form1').submit();
			},
			error: function() {
				alert("Error loading order!");
			}
		}); 
     });  
    
    //Continue Shopping
    //Function to navigate to different product pages
    $('.product').each(function() {
        $(this).button().click(function() {
            //companyName pulled from product_pop_oop.php's $cn var
            var url = companyName + "-" + $(this).text() + ".html";
            window.location.href = url; 
        });
    });
});