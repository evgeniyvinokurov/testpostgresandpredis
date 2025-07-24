<?php
include_once("pg.php");

$db = new DB();
$db->connect("host=localhost dbname=postgres user=postgres password=abbCdd12");

$categories = [    
    ["name" => "продукты", "id" => 1],
    ["name" => "инструменты", "id" => 2],
    ["name" => "посуда", "id" => 3]
];

$products = [    
    ["name" => "картофель", "id" => 1, "price" => 10.25, "category" => 1],
    ["name" => "пасатижи", "id" => 2, "price" => 30.25, "category" => 2],
    ["name" => "отвертка", "id" => 3, "price" => 35.25, "category" => 2],
    ["name" => "кружка", "id" => 4, "price" => 15.45, "category" => 3],
    ["name" => "тыква", "id" => 5, "price" => 10.55, "category" => 1],
    ["name" => "ложка", "id" => 6, "price" => 5.55, "category" => 3],
    ["name" => "тарелка", "id" => 7, "price" => 20.00, "category" => 3]
];

foreach($categories as $c) {
    $db->insertCategory($c);
}

foreach($products as $p) {
    $db->insertProduct($p);
}


?>