<?php

require_once 'db_connect.php';

class ClientPush {
    
    private $db;
    private $company_id = 0;
    
    public function __construct(DatabaseConnect $conn) {
        $this->db = $conn;
    }
    
    public function insertClient() {
        if (isset($_POST["company_name"]) && isset($_POST["company_phone"]) && isset($_POST["company_email"])) {
            
            $company_name = mysqli_real_escape_string($this->db->getDbConnect(), $_POST["company_name"]);
            $company_phone = $_POST["company_phone"];
            $company_email = $_POST["company_email"];
            
            $this->db->query("INSERT INTO client (company_name, company_phone, company_email) VALUES ('$company_name', '$company_phone', '$company_email')");
            
            $temp = $this->db->query("SELECT company_id FROM client ORDER BY company_id DESC LIMIT 1");
            
            while($result = mysqli_fetch_row($temp)) {
                $this->company_id = $result[0];
            }  
echo 'company id: ' . $this-> company_id . '<br>'; 
       }
    }
    
    public function insertClientLogo() {
        $company_name = mysqli_real_escape_string($this->db->getDbConnect(), $_POST["company_name"]);
        $company_name = stripslashes($company_name);
        $target_dir = "uploads/$company_name/";
        $target_file = $target_dir . $company_name ."-logo." . pathinfo(basename($_FILES["company_logo"]["name"]),PATHINFO_EXTENSION);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["company_logo"]["size"] > 2000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if(strcmp(strtoupper($imageFileType),"JPG") == 1 && strcmp(strtoupper($imageFileType),"PNG") == 1 && strcmp(strtoupper($imageFileType),"JPEG") == 1) {
            echo "Sorry, only JPG, JPEG, & PNG files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["company_logo"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["company_logo"]["name"]). " has been uploaded.<br>";
                //echo "T-file: " . $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
          }
    }
    
   public function insertProducts() {
        if(isset($_POST["product_id"]) && isset($_POST["product_name"]) && isset($_POST["product_price"]) && isset($_POST["product_type"]) && isset($_POST["product_description"]) && isset($_POST["product_brand"])) {
    
		  for ($x = 0; $x < count($_POST["product_id"]); $x++) {
                $product_id = mysqli_real_escape_string($this->db->getDbConnect(), $_POST["product_id"][$x]);
                $product_name = mysqli_real_escape_string($this->db->getDbConnect(), $_POST["product_name"][$x]);
                $product_price = mysqli_real_escape_string($this->db->getDbConnect(), $_POST["product_price"][$x]);
                $product_type = mysqli_real_escape_string($this->db->getDbConnect(), $_POST["product_type"][$x]);
                $product_description = mysqli_real_escape_string($this->db->getDbConnect(), $_POST["product_description"][$x]);
                $product_brand = mysqli_real_escape_string($this->db->getDbConnect(), $_POST["product_brand"][$x]);
                
                $this->db->query("INSERT INTO product VALUES ('$this->company_id', '$product_id', '$product_name', '$product_price', '$product_type', '$product_description', '$product_brand')");
          } 
	   } 
   }
}

/*$d = new ClientPush(new DatabaseConnect('localhost', 'root', '', 'senior_project'));
$d->insertClient();
$d->insertProducts(); */
?>