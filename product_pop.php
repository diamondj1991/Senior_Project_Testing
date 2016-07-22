<?php

function productPop() {
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
    
    $get_latest_id = $conn->query("SELECT company_id FROM product ORDER BY company_id DESC LIMIT 1");

    if (mysqli_num_rows($get_latest_id) > 0) {
        $result = mysqli_fetch_row($get_latest_id);
        $latest_company_id = $result[0];
    }
    
    echo "Latest company id: " . $latest_company_id;
    
    $query_set = $conn->query("SELECT * FROM product WHERE company_id = '$latest_company_id'");  
    
    $query_buttons = $conn->query("SELECT DISTINCT product_name FROM product WHERE company_id = '$latest_company_id'");
    
    $query_buttons2 = $conn->query("SELECT DISTINCT product_name FROM product WHERE company_id = '$latest_company_id'");
    
    //open buffer to save table output from $query_set, which retrieves all the product data from the last company to input their product info
    ob_start();
   
    echo "<table><tr><th>Company ID</th><th>Product ID</th><th>Product Name</th><th>Product Price</th><th>Product Type</th><th>Product Description</th><th>Product Brand</th></tr>";
    while ($result_set = mysqli_fetch_array($query_set)) {
        
        echo "<tr>";
        echo "<td>" . $result_set["company_id"] . "</td>";
        echo "<td>" . $result_set["product_id"] . "</td>";
        echo "<td>" . $result_set["product_name"] . "</td>";
        echo "<td>" . $result_set["product_price"] . "</td>";
        echo "<td>" . $result_set["product_type"] . "</td>";
        echo "<td>" . $result_set["product_description"] . "</td>";
        echo "<td>" . $result_set["product_brand"] . "</td>";
        echo "</tr>";
    }
    echo "</table>"; 
    
    //under the table make buttons for each product which will redirect to the page with that type of product
    while ($result = mysqli_fetch_array($query_buttons)) {

        $product = $result["product_name"];
        $path = '"' . 'C:/xampp/htdocs/senior_project_testing/uploads/newfile' . '' . $latest_company_id. '-' . $product . '.html' . '"';
        echo "<input type='button' value='$product' onclick='window.location.href=$path'> ";

    }
    
    //place the table contents from the buffer in to a new html page in the uploads dir, close the buffer
    file_put_contents("uploads/newfile" . "" . $latest_company_id. "" . ".html", ob_get_clean());   
    
    //for each product from the latest company to enter their product info, open a buffer, put that product info into a new html page, then close the buffer - the onclick event on the buttons from the page created above will redirect to these product pages
    while ($result = mysqli_fetch_array($query_buttons2)) {

        ob_start();
        $product = $result["product_name"];
        echo "<p>" . $product . "</p>";
        file_put_contents("uploads/newfile" . "" . $latest_company_id. "-" . $product . ".html", ob_get_clean());

    }
   
    
}

?>