<?php

$N = intval($_GET["N"]);

for($i=0; $i<$N; $i++) {
    exec("php alpha2.php");
}

$data = ["status" => "ok"];
echo json_encode($data);
?>