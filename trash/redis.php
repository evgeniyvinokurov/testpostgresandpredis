<?php 
require 'vendor/autoload.php';

$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
]);

// Обработка задач из очереди
function processQueue($redis, $queueName) {
    // Блокирующее получение элемента из очереди (ждем до 10 секунд)
    $result = $redis->blpop($queueName, 10);
    
    if ($result) {
        // $result - это массив, где [0] - имя очереди, [1] - значение
        $taskData = json_decode($result[1], true);
        
        // Обработка задачи
        echo "Обрабатываю задачу: " . print_r($taskData, true) . "\n";
        
        // Здесь должна быть логика обработки задачи
        // Например, отправка email, обработка изображения и т.д.
        
        return true;
    }
    
    echo "Нет задач в очереди\n";
    return false;
}

// Пример обработки
while (true) {
    processQueue($redis, 'queue');
    // Можно добавить небольшую паузу между проверками
    sleep(1);
}



?>