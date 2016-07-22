$(document).ready(function() {
    
    // Set accordion widget for products
	$( "#accordion" ).accordion({collapsible: true});
    
    //Collect pre-declared array  from home_page.js and de-strinifgy it back in to an object (sessionStorage saves things as strings so we use JSON.stringify() and JSON.parse())
	// This way, each time there's a page redirect, it doesn't reinitialize the arrays every time this js is ran
	var companyIdArray = JSON.parse(sessionStorage.getItem("companyIdString"));
    var productIdArray = JSON.parse(sessionStorage.getItem("productIdString"));
    var productNameArray = JSON.parse(sessionStorage.getItem("productNameString"));
    var productBrandArray = JSON.parse(sessionStorage.getItem("productBrandString"));
    var productDescriptionArray = JSON.parse(sessionStorage.getItem("productDescriptionString"));
    var productPriceArray = JSON.parse(sessionStorage.getItem("productPriceString"));
    var productQuantityArray = JSON.parse(sessionStorage.getItem("productQuantityString"));
    
    //Collect values to save for submit to DB on click of Add button
	$(".add_btn").button().click(function() {
		var $row = $(this).closest("tr"); //goes up the DOM to find the nearest tr
		var $company_id = $row.find(".company_id").text();  //goes down the DOM to find the element with company_id class
        var $product_id = $row.find(".product_id").text();  //goes down the DOM to find the element with product_id class
        var $product = $row.find(".product").text();  //goes down the DOM to find the element with product class
        var $brand = $row.find(".brand").text();  //goes down the DOM to find the element with brand class
        var $description = $row.find(".description").text();  //goes down the DOM to find the element with product_id class
        var $price = $row.find(".price").text();  //goes down the DOM to find the element with product_id class
		var $quantity = $row.find(".quantity").val();
		
		companyIdArray.push($company_id);
		
		productIdArray.push($product_id);
		
		productNameArray.push($product);
		
		productBrandArray.push($brand);
		
		productDescriptionArray.push($description);
        
        productPriceArray.push($price);
        
        productQuantityArray.push($quantity);
		
		$(this).closest("td").after('<td style="border: none; color:#96f226">&nbsp;&nbsp;&#10004;	<b>Added to cart<b></td>');

	});
    
    //Function to navigate to different product pages
    $('.product').each(function() {
        $(this).button().click(function() {
            var url = companyName + "-" + $(this).text() + ".html";
            sessionStorage.setItem('companyIdString', JSON.stringify(companyIdArray));
		    sessionStorage.setItem('productIdString', JSON.stringify(productIdArray)); 
		    sessionStorage.setItem('productNameString', JSON.stringify(productNameArray)); 
		    sessionStorage.setItem('productBrandString', JSON.stringify(productBrandArray));
		    sessionStorage.setItem('productDescriptionString', JSON.stringify(productDescriptionArray));
            sessionStorage.setItem('productPriceString', JSON.stringify(productPriceArray));
            sessionStorage.setItem('productQuantityString', JSON.stringify(productQuantityArray));
            window.location.href = url; 
        });
    });
    
    
	 
});