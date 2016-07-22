<?php 
echo "hello ";
if (isset($_POST['name'])) echo $_POST['name'];
require_once 'customer_push_oop.php'; 

$custPush = new CustomerPush(new DatabaseConnect('localhost', 'root', 'root', 'senior_project'));

$custPush->insertOrder();
$custPush->insertOrderline();
$custPush->buildClientPortalTable();
$custPush->buildSetFlagPhp();
$custPush->buildClientPortal();

?>