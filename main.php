<?php

//require_once 'db_connect.php'
require_once 'product_push_oop.php'; 
require_once 'product_pop_oop.php'; 

$push = new ClientPush(new DatabaseConnect('localhost', 'root', 'root', 'senior_project'));
$push->insertClient();
$push->insertProducts();

$pop = new ClientPop(new DatabaseConnect('localhost', 'root', 'root', 'senior_project')); 
$pop->buildCustomerNamePage();
//call this after buildCustomerNamePage() b/c this func is where each subdir is created for the companies, ergo tht dir must exist before insertClientLogo() inserts it in to the subdir
$push->insertClientLogo();
$pop->buildIndexPage();
$pop->buildProductPages();
$pop->buildProductSubmissionPage();
$pop->buildClientTables();
$pop->emailIndexAddress();
?>

