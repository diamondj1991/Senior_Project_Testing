<?php
        
    include "product_pop.php";

    $servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "senior_project";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	} 

    if (isset($_POST["company_name"]) && isset($_POST["company_phone"]) && isset($_POST["company_email"])) {
        $company_name = $_POST["company_name"];
        $company_phone = $_POST["company_phone"];
        $company_email = $_POST["company_email"];
        
        echo $company_name . "<br>";
        echo $company_phone . "<br>";
        echo $company_email . "<br><br>";
        
        $insert_company_info = $conn->query("INSERT INTO client (company_name, company_phone, company_email) VALUES ('$company_name', '$company_phone', '$company_email')");
        
        $company_id = $conn->insert_id;
        
    }
	
   //Insert form inputs along with collected order_id in to orderline
	if(isset($_POST["product_id"]) && isset($_POST["product_name"]) && isset($_POST["product_price"]) && isset($_POST["product_type"]) && isset($_POST["product_description"]) && isset($_POST["product_brand"])) {
    
		for ($x = 0; $x < count($_POST["product_id"]); $x++) {
            $product_id = $_POST["product_id"][$x];
            $product_name = $_POST["product_name"][$x];
            $product_price = $_POST["product_price"][$x];
            $product_type = $_POST["product_type"][$x];
            $product_description = $_POST["product_description"][$x];
            $product_brand = $_POST["product_brand"][$x];
            
            echo $company_id . "<br>";
            echo $product_id . "<br>";
            echo $product_name . "<br>";
            echo $product_price . "<br>";
            echo $product_type . "<br>";
            echo $product_description . "<br>";
            echo $product_brand . "<br><hr>";
            
            $insert_company_product = $conn->query("INSERT INTO product VALUES ('$company_id', '$product_id', '$product_name', '$product_price', '$product_type', '$product_description', '$product_brand')");
        } 
	} 
	
	productPop();

	$conn->close();
?>