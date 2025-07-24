<?php
include_once("libs/pg.php");

$db = new DB();
$db->connect("host=localhost dbname=postgres user=postgres password=abbCdd12");

$products = $db->getProducts();
$product = intval($products["ids"][strval(array_rand($products["ids"]))]);

$db->insertOrder(["id" => random_int(0, 9999999), "product" => $product]); 
 
?>