<?php
// Пример с exec()
exec('php 1.php');

// Пример с shell_exec()
$output = shell_exec('php 2.php');
echo $output;
?>