<?php
include_once("libs/pg.php");

$db = new DB();
$db->connect("host=localhost dbname=postgres user=postgres password=abbCdd12");

$data = $db->updateStatistics();

 
?>