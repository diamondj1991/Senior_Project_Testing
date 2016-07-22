$(document).ready(function() {
    
    var wrapper = $("#product_info");
    var add_button      = $("#add_field_button");
    
    $(wrapper).hide();
    $(add_button).hide();
    
    $('form > input:not("input[type=submit]")').keyup(function() {

        var empty = false;
        
        $('form > input:not("input[type=submit]")').each(function() {
            if ($(this).val() == '') {
                empty = true;
            }
        });
        
        if (empty) {
            $('#next, #add_field_button').attr('disabled', 'disabled');
        } else {
            $('#next, #add_field_button').removeAttr('disabled');
        }
    });
    
    $('#next').click(function() {
        $(wrapper).show();
        $(add_button).show();
        $('#submit_business_info').removeAttr('style');
        $('#next').attr('disabled', 'disabled');
    });
    
    $(add_button).click(function(e) {
        e.preventDefault();
        $(wrapper).append('<hr>Product Id: <input type="text" id="product_id" name="product_id[]"><br>Product Name: <input type="text" id="product_name" name="product_name[]"><br>Product Price: <input type="text" id="product_price" name="product_price[]"><br>Product Type: <input type="text" id="product_type" name="product_type[]"><br>Product Description: <input type="text" id="product_description" name="product_description[]"><br>Product Brand: <input type="text" id="product_brand" name="product_brand[]"><br>');
    });
    
    $('#submit_business_info').submit();
});