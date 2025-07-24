<?php
require 'vendor/autoload.php';
include_once("libs/pg.php");

$db = new DB();
$db->connect("host=localhost dbname=postgres user=postgres password=abbCdd12");

$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
]);

$products = $db->getProducts();
$product = intval($products["ids"][strval(array_rand($products["ids"]))]);

function addTaskToQueue($redis, $queueName, $taskData) {
    $task = json_encode($taskData);    
    $redis->rpush($queueName, $task);    
    return true;
}

$redis->lPush('jobs', 'php update_db.php');
addTaskToQueue($redis, 'queue', ["id" => random_int(0, 9999999), "product" => $product]);

?>