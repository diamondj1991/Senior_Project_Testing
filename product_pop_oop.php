<?php

require_once 'db_connect.php';
require '../PHPMailer/PHPMailerAutoload.php';
//require_once 'product_push_oop.php';

class ClientPop {
    
    private $db;
    private $company_id;
    private $company_name;
    private $result_set;
    private $productTypes = array();
    private $target;
    
    public function __construct(DatabaseConnect $conn) {
        $this->db = $conn;
    }
    
    public function buildCustomerNamePage() {
        
        $companyId = $this->db->query("SELECT company_id FROM product ORDER BY company_id DESC LIMIT 1");
        
        while($result = mysqli_fetch_row($companyId)) {
                $this->company_id = $result[0];
        }
      
        $companyName = $this->db->query("SELECT company_name FROM client WHERE company_id = '$this->company_id'");
        
        while($result2 = mysqli_fetch_row($companyName)) {
                $this->company_name = $result2[0];
        } 
      
        //this varable gets passed to javascript below so that within customer_name.js we can navigate to different product's pages when a user clicks their corresponding buttons.
        $cn = addslashes($this->company_name);
        
        $products = $this->db->query("SELECT DISTINCT product_type FROM product WHERE company_id = '$this->company_id'");
        
        while ($result2 = mysqli_fetch_row($products)) {
            array_push($this->productTypes, $result2[0]);
        }

        echo $this-company_name;
        ob_start();
        
        echo("
            
            <!DOCTYPE html>
            <html>
            <head>
            <meta charset='utf-8'/>
            <title>Customer Name Entry</title>
            <link href='../../css/customer_name_entry.css' rel='stylesheet'/>
            <link href='../../css/jquery-ui.min.css' rel='stylesheet'/>
            <script type='text/javascript'>var companyName = '$cn';</script>
            <script type='text/javascript' src='../../js/jquery-2.1.4.min.js'></script>
	        <script type='text/javascript' src='../../js/jquery-ui.min.js'></script>
	        <script type='text/javascript' src='../../js/customer_name_entry.js'></script>
	        <style type='text/css'>
            body
	        {
		      font-family: Segoe UI,Helvetica,Arial,sans-serif;
		      color: white;
		      background-image:url('../../images/background.jpg');
	        }
            </style>
            </head>
            <body>
            <br><br>
            <form method = 'post' action = ''>
               
		       <!--Touch screen keyboard code goes here --> 	
		       <div id='textbox'><label>Name:  </label><textarea id='write' rows='1' cols='30' name='name'></textarea><label id='p1'></label></div>
		       <div id='container'>
		       
                 <ul id='keyboard'>
                   <li class='symbol'><span class='off'>`</span><span class='on'>~</span></li>
                   <li class='symbol'><span class='off'>1</span><span class='on'>!</span></li>
                   <li class='symbol'><span class='off'>2</span><span class='on'>@</span></li>
                   <li class='symbol'><span class='off'>3</span><span class='on'>#</span></li>
                   <li class='symbol'><span class='off'>4</span><span class='on'>$</span></li>
                   <li class='symbol'><span class='off'>5</span><span class='on'>%</span></li>
                   <li class='symbol'><span class='off'>6</span><span class='on'>^</span></li>
                   <li class='symbol'><span class='off'>7</span><span class='on'>&amp;</span></li>
                   <li class='symbol'><span class='off'>8</span><span class='on'>*</span></li>
                   <li class='symbol'><span class='off'>9</span><span class='on'>(</span></li>
                   <li class='symbol'><span class='off'>0</span><span class='on'>)</span></li>
                   <li class='symbol'><span class='off'>-</span><span class='on'>_</span></li>
                   <li class='symbol'><span class='off'>=</span><span class='on'>+</span></li>
                   <li class='delete lastitem'>delete</li>
                   <li class='tab'>tab</li>
                   <li class='letter'>q</li>
                   <li class='letter'>w</li>
                   <li class='letter'>e</li>
                   <li class='letter'>r</li>
                   <li class='letter'>t</li>
                   <li class='letter'>y</li>
                   <li class='letter'>u</li>
                   <li class='letter'>i</li>
                   <li class='letter'>o</li>
                   <li class='letter'>p</li>
                   <li class='symbol'><span class='off'>[</span><span class='on'>{</span></li>
                   <li class='symbol'><span class='off'>]</span><span class='on'>}</span></li>
                   <li class='symbol lastitem'><span class='off'>\</span><span class='on'>|</span></li>
                   <li class='capslock'>caps lock</li>
                   <li class='letter'>a</li>
                   <li class='letter'>s</li>
                   <li class='letter'>d</li>
                   <li class='letter'>f</li>
                   <li class='letter'>g</li>
                   <li class='letter'>h</li>
                   <li class='letter'>j</li>
                   <li class='letter'>k</li>
                   <li class='letter'>l</li>
                   <li class='symbol'><span class='off'>;</span><span class='on'>:</span></li>
                   <li class='symbol'><span class='off'>'</span><span class='on'>&quot;</span></li>
                   <li class='return lastitem'>return</li>
                   <li class='left-shift'>shift</li>
                   <li class='letter'>z</li>
                   <li class='letter'>x</li>
                   <li class='letter'>c</li>
                   <li class='letter'>v</li>
                   <li class='letter'>b</li>
                   <li class='letter'>n</li>
                   <li class='letter'>m</li>
                   <li class='symbol'><span class='off'>,</span><span class='on'>&lt;</span></li>
                   <li class='symbol'><span class='off'>.</span><span class='on'>&gt;</span></li>
                   <li class='symbol'><span class='off'>/</span><span class='on'>?</span></li>
                   <li class='right-shift lastitem'>shift</li>
                   <li class='space lastitem'>&nbsp;</li>
                 </ul>
               </div>
	         </form> <!--Touch screen keyboard code ends here --> 
             <div id='rest'>
             <h2>Choose a Product:</h2>
        ");
        
        foreach($this->productTypes as $key => $value) {
            echo "<div>
                  <button class='product'>$value</button>
                  </div>";
        }
        
        echo "</body>
             </html>";

        
        mkdir("./uploads/$this->company_name",0777,true);
       
        file_put_contents("uploads/$this->company_name" . "/" . $this->company_name . "-customer_name_entry" . ".html", ob_get_clean());
        
    }
    
    public function buildIndexPage() {
        $cn = addslashes($this->company_name);
        
        $dir = "uploads/$this->company_name";
        $regex = '/^.*\.(jpg|jpeg|png|gif)$/i';

        // Open a directory, and read its contents
        if (is_dir($dir)){
          if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){ 
              if(preg_match($regex,$file)) {
                  $this->target = addslashes($file);
              }
            }
            closedir($dh);
          }
        }
        
        ob_start();
        
        echo("
        
            <!DOCTYPE html>
            <html>
            <head>
            <meta charset='utf-8'/>
            <title>$this->company_name: Home</title>
            <!--<link href='../../css/customer_name_entry.css' rel='stylesheet'/>-->
            <link href='../../css/jquery-ui.min.css' rel='stylesheet'/>
            <script type='text/javascript'>var companyName = '$cn';</script>
            <script type='text/javascript' src='../../js/jquery-2.1.4.min.js'></script>
	        <script type='text/javascript' src='../../js/jquery-ui.min.js'></script>
	        <script type='text/javascript' src='../../js/index.js'></script>
            <style type='text/css'>
        	*
        	{
        		font-family: Segoe UI,Helvetica,Arial,sans-serif;
        		margin: 0; 
        		padding: 0;
        	}
        	body
        	{
                background-image:url('../../images/background.jpg');
        	}
        	div.header
        	{
        		width: 100%;
        		height: 10%;
        		color: #ffffff;
        		background-color: #696969;
        		border-bottom: 1px solid #b8ec79;
        	}
        	div.middle 
        	{
        		position: fixed;
        		background-image:url('$this->target');
        		background-size: 100% 100%;
        
        		top: 50%;
        		left: 50%;
        		border-radius: 25px;
        		border: 2px solid #b8ec79;
        		padding: 20px; 
        		width: 75%;
        		height: 65%;    
        		/*margin-left: -40%;*/
        		/*margin-top: -22%;*/
        		transform: translate(-50%, -50%)
        	}
        	#start
        	{
        		position:absolute;
        		bottom: 0%;
        		left: 45%;
        	}
        </style>
        </head>
        
        <body>
        	<div class='header'>
        		<h1>Welcome!...Touch Start to begin</h1>
        	</div>
        	<div class='middle'>
        	</div>
        	<button id='start'>Start</button>
        
        </body>
        </body>
        
        ");
        
        file_put_contents("uploads/$this->company_name" . "/" . $this->company_name . "-index.html", ob_get_clean());
    }
     
    public function buildProductPages() {
        //this varable gets passed to javascript below so that within customer_name.js we can navigate to different product's pages when a user clicks their corresponding buttons.
        $cn = addslashes($this->company_name);
        
        foreach($this->productTypes as $key => $value) {
            $temp = $this->db->query("SELECT * FROM product WHERE company_id = $this->company_id AND product_type = '$value'"); 
            
            $temp2 = $this->db->query("SELECT DISTINCT product_name FROM product WHERE company_id = $this->company_id AND product_type = '$value'");
             
            $productNames = array();
            $productSubTypes = array();
            $productTypes = array();
            $productIds = array();
            $productPrices = array();
            $productDescriptions = array();
            $productBrands = array();
            
            while ($result2 = mysqli_fetch_array($temp2)) {
                array_push($productNames, $result2["product_name"]);
            }
            while ($result = mysqli_fetch_array($temp)) {
                array_push($productSubTypes, $result["product_name"]);
                array_push($productTypes, $result["product_type"]);
                array_push($productIds, $result["product_id"]);
                array_push($productPrices, $result["product_price"]);
                array_push($productDescriptions, $result["product_description"]);
                array_push($productBrands, $result["product_brand"]);
            }
            
            ob_start();
            echo("
                
                <!DOCTYPE html>
                <html>
                <head></head>
                <title>$this->company_name-$value</title>
                <link href='../../css/customer_name_entry.css' rel='stylesheet'/>
                <link href='../../css/jquery-ui.min.css' rel='stylesheet'/>
                <script type='text/javascript'>var companyName = '$cn';</script>
                <script type='text/javascript' src='../../js/jquery-2.1.4.min.js'></script>
                <script type='text/javascript' src='../../js/jquery-ui.min.js'></script>
                <script type='text/javascript' src='../../js/product_page.js'></script>
                <style type='text/css'>
                            body
                	        {
                		      font-family: Segoe UI,Helvetica,Arial,sans-serif;
                		      color: white;
                		      background-image:url('../../images/background.jpg');
                	        }
                            </style>
                <body>
                
                <div id='accordion'>
            ");
            
            for ($i = 0; $i < count($productNames); $i++) {
                echo "<h3>" . $productNames[$i] . "</h3>"; 
                echo "<div>";
                echo "<table>";
                for ($j = 0; $j < count($productSubTypes); $j++) {
                    if ($productSubTypes[$j] == $productNames[$i]) {
                        echo "<tr>";
                         echo "<td class='company_id' style='display:none;'>" . $this->company_id . "</td>";
                        echo "<td class='product_id' style='display:none;'>" . $productIds[$j] . "</td>";
                        echo "<td class='product' style='display:none;'>" . $productSubTypes[$j] . "</td>";
                        echo "<td class='brand'>" . $productBrands[$j] . "</td>";
                        echo "<td class='description'>" . $productDescriptions[$j] . "</td>";
                        echo "<td class='price'>" . $productPrices[$j] . "</td>"; 
                        echo "<td><label>Amount: </label><input type='number' class='quantity' min='0.25' max='10' step='0.25'></td>";
	                    echo "<td><input type='button' class='add_btn' value='Add'><label class='confirm-selection'></label></td>";
                        echo "</tr>";
                    } 
                }
                echo "</table>";
                echo "</div>";
            } 
            
            echo "</div>";
            echo "<p></p><br>";
            echo "<p></p><br>";
            
            foreach($this->productTypes as $k => $v) {
                if($v !== $value) {
                   echo "<div>
                  <button class='product'>$v</button>
                  </div>"; 
                } 
            }
            
            echo "<div><button class='product'>View Cart</button></div>";
            
            echo "</body>";
            echo "</html>";
            
            file_put_contents("uploads/$this->company_name" . "/" . $this->company_name . "-" . $value . ".html", ob_get_clean());
        }
    } 
    
    public function buildProductSubmissionPage() {
        $cn = addslashes($this->company_name);
        
        ob_start();
        echo("
            <!DOCTYPE html>
            <html>
            <head>
            <meta charset='utf-8'/>
            <title>$this->company_name: View Cart</title>
            <!--<link href='../../css/customer_name_entry.css' rel='stylesheet'/>-->
            <link href='../../css/jquery-ui.min.css' rel='stylesheet'/>
            <script type='text/javascript'>var companyName = '$cn';</script>
            <script type='text/javascript' src='../../js/jquery-2.1.4.min.js'></script>
	        <script type='text/javascript' src='../../js/jquery-ui.min.js'></script>
	        <script type='text/javascript' src='../../js/view_cart.js'></script>
            	<style type='text/css'>
            			*
            	    {
            			font-family: Segoe UI,Helvetica,Arial,sans-serif;
            			margin: 0; 
            			padding: 0;
            	    }
            		body
            		{
            			color: white;
            			background-image:url('../../images/background.jpg');
            		}
            		div.header
            	   {
            			display: inline-block;
            			width: 100%;
            			height: 10%;
            			color: #ffffff;
            			background-color: #696969;
            			border-bottom: 1px solid #b8ec79;
            	   }
            	   #continue
            	   {
            			float: left;
            	   }
            	   #btns
            	   {
            			float: left;
            			margin-top: 2px;
            	   }
            	   div.middle 
            	   {
            		position: fixed;
            		background-color: #696969;
            		background-size: 100% 100%;
            		top: 50%;
            		left: 50%;
            		border-radius: 25px;
            		border: 2px solid #b8ec79;
            		padding: 20px; 
            		width: 75%;
            		height: 65%;    
            		/*width: 90%;*/
            		/*height: 65%;*/
            		/*margin-left: -48%;*/
            		/*margin-top: -22%;*/
            		transform: translate(-50%, -50%);
            	    }
            		#finalOrder 
            		{
            			margin: auto;
            			margin-top: 5px;
            			border-collapse: collapse;
            			border-style: none;
            		}
            		input[type='submit']
            		{
            			left: 45%;
            		}
            		a
            		{
            			color: #b8ec79;
            			padding-left: 1px;
            		}
            		a:hover
            		{
            			color: red;
            		}
            	</style>
            </head>
            <body>
            	<div class='header'>
            		<div id='continue'><h1>Continue Shopping: &nbsp &nbsp </h1></div>
                    <div id='btns'>
        ");
            		
        foreach($this->productTypes as $key => $value) {
            echo "<button class='product'>$value</button>";
        }  
        
        $companyName = $this->company_name . "-index.html";
        //$companyName = htmlspecialchars($companyName);
            		
        echo("  
                    </div>
            	</div>
            	<div class='middle'>
        ");
        
        echo    '<form id="form1" action=' . '"' . $companyName . '">';
        //echo '<form id="form1" method="post" action="http://localhost:88/senior_project_testing/customer_main.php">';
            
        echo("
            	<div >
            		<h3 style = 'text-align: center; margin-bottom: 5px;'><script>document.write('Hi ' + sessionStorage.getItem('name') + ', this is your shopping cart:');</script></h3><hr>
            		<table id = 'finalOrder' border='1'>
            		<tbody id = 'head'>
            			<tr>
            				<th class = 'foodCell'>Product Name</td>
            				<th class = 'foodCell'>Product Brand</th>
            				<th class = 'foodCell'>Product Description</th>
                            <th class = 'foodCell'>Product Price</th>
                            <th class = 'foodCell'>Amount Ordered</th>
            			</tr>
            		</tbody>
            		<table>
            		<h3 style = 'text-align: center;' id='total'></h3>
            		<input type='hidden' id='n' name='name'><script>document.getElementById('n').setAttribute('value', sessionStorage.getItem('name'));</script> <!--Get user name and hide in submission form input-->
            	</div><br><br>
            	<input type='submit'  id='submit1' name='submit1' value='Submit Order'>
            	</form>
            	</div>
            </body>
            </html>
        
        ");
        
        file_put_contents("uploads/$this->company_name" . "/" . $this->company_name . "-View Cart.html", ob_get_clean());
    }
    
    public function buildClientTables() {
        $table_string = "CREATE TABLE IF NOT EXISTS" . "`" . $this->company_name . "_clientcustomerorder` (
                         `order_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                         `customer_name` varchar(20) NOT NULL
                         ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";

        $this->db->query($table_string);
        
        $table_string2 = "CREATE TABLE IF NOT EXISTS" . "`" . $this->company_name . "_clientcustomerorderline`  (
                         `company_id` int(11) NOT NULL,
                         `order_id` int(11) NOT NULL,
                         `product_id` varchar(20) NOT NULL,
                         `product_brand` varchar(60) NOT NULL,
                         `product_name` varchar(20) NOT NULL,
                         `product_description` varchar(60) NOT NULL,
                         `product_price` double(4,2) NOT NULL,
                         `quantity` double(4,2) NOT NULL,
                         `filled` int(1) DEFAULT '0',
                         PRIMARY KEY (`company_id`, `order_id`, `product_id`)
                         ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        
        $this->db->query($table_string2);
    }
    
     public function emailIndexAddress() {
        
        $clientEmail;
       
        $index = $_SERVER['HTTP_HOST'] ."/senior_project_testing/uploads/$this->company_name/$this->company_name-index.html";
        
        $query = $this->db->query("SELECT company_email FROM client where company_id = $this->company_id");
        
        while($result = mysqli_fetch_row($query)) {
                $clientEmail = $result[0];
        }
        
        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'jake.b.diamond@gmail.com';                 // SMTP username
        $mail->Password = 'armpit7711';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        
        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress('jake.b.diamond@gmail.com', 'Jake Diamond');     // Add a recipient
        $mail->addAddress($clientEmail);               // Name is optional
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');
        
        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML
        
        $mail->Subject = '"' . $this->company_name . '"' . ' Home Page';
        $mail->Body    = 'Your new homepage has been created and can be accessed at:<br><br>' . $index;
        $mail->AltBody = 'Your new homepage has been created and can be accessed at:' . "'" . $index;
        
        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo "<script type='text/javascript'>alert('The URL of your new website has been sent to your provided email address!'); window.location.href = 'http://localhost:88/senior_project_testing';</script>";
        }
    }

}

}

?>