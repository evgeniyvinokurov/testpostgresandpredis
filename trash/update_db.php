<?php
require 'vendor/autoload.php';
include_once("libs/pg.php");

$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
]);

$db = new DB();
$db->connect("host=localhost dbname=postgres user=postgres password=abbCdd12");

function processQueue($redis, $queueName, $db) {
    $result = $redis->blpop($queueName, 10);    
    if ($result) {
        $taskData = json_decode($result[1], true);
        $db->insertOrder($taskData); 
        return true;
    }    
    return false;
}

$res = FALSE;
while (!$res) {    
    $res = processQueue($redis, 'queue', $db);    
}    

?>