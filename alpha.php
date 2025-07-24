<?php
include_once("libs/pg.php");
require 'vendor/autoload.php';

// Создаем клиент Redis
$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
]);

try {
    // Добавляем задачу в очередь (список jobs)
    $redis->lPush('jobs', 'update db');
    echo "Задача добавлена в очередь\n";

    $test = False;
    // Воркер для обработки задач
    while (!$test) {
        // Блокирующее получение задачи (ждем до 30 секунд)
        $task = $redis->brPop('jobs', 30);
        
        if ($task) {
            // $task будет массивом [ключ, значение]
            $command = $task[1];
            echo "Обрабатываю задачу: $command\n";
            
            $db = new DB();
            $db->connect();

            $products = $db->getProducts();
            $product = intval($products["ids"][strval(array_rand($products["ids"]))]);

            $db->insertOrder(["id" => random_int(0, 9999999), "product" => $product]); 
            
            // Имитация обработки
            // sleep(3);
            $test = TRUE;
        }
    }
} catch (Exception $e) {
    die("Ошибка подключения к Redis: " . $e->getMessage());
}



?>