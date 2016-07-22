<?php

require_once 'db_connect.php';

class CustomerPush {
    
    private $db;
    private $order_id;
    private $company_id;
    private $company_name;
    
    public function __construct(DatabaseConnect $conn) {
     $this->db = $conn;
    }
    
    public function insertOrder() {
      
      $companyId = $this->db->query("SELECT company_id FROM client ORDER BY company_id DESC LIMIT 1");
        
      while($result = mysqli_fetch_row($companyId)) {
            $this->company_id = $result[0];
      }
        
      $companyName = $this->db->query("SELECT company_name FROM client WHERE company_id = '$this->company_id'");
        
      while($result2 = mysqli_fetch_row($companyName)) {
            $this->company_name = $result2[0];
      }
       echo 'comp name: ' .  $this->company_name;
      //Take the user's name from the HTML hidden form input and insert into order table, get back that record's order_id
	  if (isset($_POST["name"])) {
	  	$name = $_POST["name"];
	  	
        $this->db->query("INSERT INTO `" . $this->company_name . "_clientcustomerorder` (customer_name) VALUES ('$name')");
	  	
	  }
    }
    
    public function insertOrderline() {
        
        $orderId = $this->db->query("SELECT order_id FROM `" . $this->company_name . "_clientcustomerorder` ORDER BY order_id DESC LIMIT 1");
        
        while($result = mysqli_fetch_row($orderId)) {
            $this->order_id = $result[0];
        }
        
        //Insert form inputs along with collected order_id in to orderline
	    if(isset($_POST["company_ids"]) && isset($_POST["product_ids"]) && isset($_POST["product_names"]) && isset($_POST["product_brands"]) && isset($_POST["product_descriptions"]) && isset($_POST["product_prices"]) && isset($_POST["product_amounts_ordered"])) {
        
	    	for ($x = 0; $x <count($_POST["product_names"]); $x++) {
	    
	    		$company_id = $_POST["company_ids"][$x];
                $product_id = $_POST["product_ids"][$x];
                $product_brand = $_POST["product_brands"][$x];
                $product_name = $_POST["product_names"][$x];
                $product_description = $_POST["product_descriptions"][$x];
                $product_price = $_POST["product_prices"][$x];
                $product_quantity = $_POST["product_amounts_ordered"][$x];
	    		
	    		//build insert query to put all of customer's orders in to the database
	    		$this->db->query( "INSERT INTO `" . $this->company_name . "_clientcustomerorderline` (company_id, order_id, product_id, product_brand, product_name, product_description, product_price, quantity)  VALUES ('$company_id', '$this->order_id', '$product_id', '$product_brand', '$product_name', '$product_description', '$product_price', '$product_quantity') " );
    
	    	} 
	    }
    }
    
    //Build the php page that contains the table of customer orders and place it in the client's subdirectory. buildClientPortal() makes an ajax call to this newly inserted php page and generates a dynamic portal html page for the client to monitor their customer orders
    public function buildClientPortalTable() {
        
        $queryString =  "SELECT " . "`" . $this->company_name . "_clientcustomerorder`.customer_name," .
                                    "`" . $this->company_name . "_clientcustomerorder`.order_id," .
                                    "`" . $this->company_name . "_clientcustomerorderline`.product_id," .
                                    "`" . $this->company_name . "_clientcustomerorderline`.product_brand," .
                                    "`" . $this->company_name . "_clientcustomerorderline`.product_name," .
                                    "`" . $this->company_name . "_clientcustomerorderline`.product_description," .
                                    "`" . $this->company_name . "_clientcustomerorderline`.product_price," .
                                    "`" . $this->company_name . "_clientcustomerorderline`.quantity," .
                                    "`" . $this->company_name . "_clientcustomerorderline`.filled" .
                                    " FROM " .  "`" . $this->company_name . "_clientcustomerorder` ". 
                                    "JOIN " .  "`" . $this->company_name . "_clientcustomerorderline` ". "ON " . 
                                    "`" . $this->company_name . "_clientcustomerorder`.order_id " . "=" .
                                    "`" . $this->company_name . "_clientcustomerorderline`.order_id " . " WHERE " .
                                    "`" . $this->company_name . "_clientcustomerorderline`.filled = 0" ." AND " .
                                    "`" . $this->company_name . "_clientcustomerorderline`.company_id =" . $this->company_id;
            
        ob_start();
        
        echo "<?php ";
        echo "require_once '../../db_connect.php';";
        echo "$" . "conn " . "= new DatabaseConnect('localhost','root', 'root', 'senior_project');";
        echo "/*SQL to build table for clerk's view of the order's that have been input in to the database*/";
        
        echo "$" . "grabSQL" . "=$" . "conn->query(" . '"' . $queryString . '"' .");";
        
        echo " echo 'ORDERS<br><table>';";
        echo " echo '<tr><td>NAME</td><td>ORDER ID</td><td>PRODUCT ID</td><td>PRODUCT BRAND</td><td>PRODUCT DESCRIPTION</td><td>PRODUCT PRICE</td><td>PRODUCT AMOUNT</td><td>PRODUCT QUANTITY</td></tr>'; ";
    
        echo "while ($" . "row = mysqli_fetch_array(" . "$" . "grabSQL)) {";
        //echo " echo '<div id=responsecontainer\>'; ";
        echo " echo '<tr>'; ";
        echo " echo '<td>' . " . "$" . "row['customer_name']" . " . '</td>';";
        echo " echo '<td id=order_id>' . " . "$" . "row['order_id']" . ".  '</td>';";
        echo " echo '<td id=product_id>' . " . "$" . "row['product_id']" . " . '</td>';";
        echo " echo '<td>' . " . "$" . "row['product_brand']" . " . '</td>';";
        echo " echo '<td>' . " . "$" . "row['product_name']" . " . '</td>';";
        echo " echo '<td>' . " . "$" . "row['product_description']" . " . '</td>';";
        echo " echo '<td>' . " . "$" . "row['product_price']" . " . '</td>';";
        echo " echo '<td>' . " . "$" . "row['quantity']" . " . '</td>';";
        echo " echo '<td style=border: none; background-color=blue;><a href=# class=remove_order>Remove</a></td>'; ";
        echo " echo '</tr>'; ";
        echo " echo '</div>'; ";
        echo " } ";
        echo " echo '</table>'; ";
        echo "?>";
        
        file_put_contents("uploads/$this->company_name" . "/" . $this->company_name . "-client_portal_table.php", ob_get_clean());
        
    }
    
    public function buildSetFlagPhp() {
        
        $queryString = "UPDATE " . "`" . $this->company_name . "_clientcustomerorderline` SET filled = 1 WHERE order_id = '$" . "oid'" . " AND product_id = '$" . "pid'";
        
        ob_start();
        
        echo "<?php ";
        echo "require_once '../../db_connect.php';";
        echo "$" . "conn " . "= new DatabaseConnect('localhost','root', 'root', 'senior_project');";
        echo "$" . "oid = $" . "_GET['order_id'];";
        echo "$" . "pid = $" . "_GET['product_id'];";
        echo "$" . "conn->query(" . '"' . $queryString . '"' .");";
        echo "?> ";
        
        file_put_contents("uploads/$this->company_name" . "/" . $this->company_name . "-setflag.php", ob_get_clean());
    }
    
    public function buildClientPortal() {
        
        $urlString = '"' . $this->company_name . '-client_portal_table.php' . '"';
        $urlString2 = '"' . $this->company_name . '-setflag.php?order_id=' . '"' . '+oid+' . '"' . '&product_id=' . '"' . '+pid';
        
        ob_start();
        
        echo("
        
            <!DOCTYPE html>
            <html>
               <head>
                  <meta charset = 'utf-8'>
                  <title>$this->company_name: Order Portal</title>
               <style type = 'text/css'>
                     body  { 
                        font-family: sans-serif;
                        background-color: lightyellow; 
                     } 
                     table { 
                        border-collapse: collapse; 
                        border: none; 
                     }
                     td    { padding: 5px; border: 1px solid gray;}
            		 td:nth-child(9) {
            			background-color: lightyellow; 
            			border: none;
                     }
                     tr:nth-child(odd) {
                             background-color: lightblue; 
                     }
                     tr:nth-child(even) {
                             background-color: lightgreen; 
                     }
                  </style>
            	  <script type='text/javascript' src='../../js/jquery-2.1.4.min.js'></script>
            	  <script type='text/javascript'>
            	  $(document).ready(function () {
            
            		var ajaxCall = function () {
            			$.ajax({    
            				type: 'GET',
            				url: $urlString,             
            				dataType: 'html',   //expect html to be returned                
            				success: function(response){                    
            				$('#responsecontainer').html(response); 
            
            				$('.remove_order').click(function() {
            				
            					var row = $(this).closest('tr');
            					var oid = row.find('#order_id').text();
            					var pid = row.find('#product_id').text();
            				
            					$.ajax({
            						type: 'GET',
            						url: $urlString2
            					});
            				
            			    });
            				
            
            				}
            			});
            		}
            		setInterval(ajaxCall, 3000);
            
            	  
            	  });
            	  </script>
               </head>
            <body>
              <div id='responsecontainer'></div>
            </body>
            </html>
        
        ");
        
        file_put_contents("uploads/$this->company_name" . "/" . $this->company_name . "-client_portal.html", ob_get_clean());
    }
}

?>