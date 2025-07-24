<?php

class DB {
    private $conn;

    function connect($connString){
        $this->conn = pg_connect($connString) or die('Could not connect: ' . pg_last_error());
    }

    //// inserts

    function insertCategory($cat){
        $result = FALSE;        

        if (!empty($cat)) {
            $query = "insert into categories (id, name) values (".$cat["id"].", '".$cat["name"]."');";
            $result = pg_query($this->conn, $query) or die('Query failed: ' . pg_last_error());
        }

        return $result;
    }

    function insertProduct($product){
        $result = FALSE;        

        if (!empty($product)) {
            $query = "insert into products (name, id, price, category) values ('".$product["name"]."', ".$product["id"].", ".$product["price"].", ".$product["category"].");";
            $result = pg_query($this->conn, $query) or die('Query failed: ' . pg_last_error());
        }

        return $result;
    }

    function insertOrder($order){
        $result = FALSE;        

        if (!empty($order)) {
            $query = "insert into orders (id, product, time) values (".$order["id"].", ".$order["product"].", NOW());";
            $result = pg_query($this->conn, $query) or die('Query failed: ' . pg_last_error());
        }

        return $result;
    }

    function insertStaticstics($stat){

        $result = FALSE;        
        $query = "select * from statistics where category ='".$stat["category"]."'";
        $result = pg_query($this->conn, $query) or die('Query failed: ' . pg_last_error());
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            return FALSE;
        }

        $result = FALSE;        

        if (!empty($stat)) {
            $query = "insert into statistics (category, delta) values ('".$stat["category"]."', ".$stat["delta"].");";
            $result = pg_query($this->conn, $query) or die('Query failed: ' . pg_last_error());
        }

        return $result;
    }


    //// getters

    function getProducts(){
        $query = "select * from products;";
        $result = pg_query($this->conn, $query) or die('Query failed: ' . pg_last_error());
        $ids = [];

        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $ids[] = $line["id"];
        }

        return ["ids" => $ids];
    }

    function updateStatistics(){
        $query = "select o.time, c.name from orders o 
            left join products p on o.product = p.id 
            left join categories c  on c.id = p.category 
            order by o.time desc
            limit 100;";

        $result = pg_query($this->conn, $query) or die('Query failed: ' . pg_last_error());
        $count = 0;
        $counts = [];
        $lastline = [];

        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            if ($count === 0) {
                $time = $line["time"];
            }        

            if (empty($counts[$line["name"]])){
                $counts[$line["name"]] = 0;
                $counts[$line["name"]] += 1;
            } else {
                $counts[$line["name"]] += 1;
            }

            $count++;
            $lastline = $line;
        }

        $endTime = $lastline["time"];
        
        $date1 = new DateTime($time); 
        $date2 = new DateTime($endTime); 

        $interval = $date1->diff($date2);
        $deltaInSeconds = ($interval->days * 86400) + 
                  ($interval->h * 3600) + 
                  ($interval->i * 60) + 
                  $interval->s;


        $str = "";
        ksort($counts);
        foreach($counts as $key => $value){
            $str .= $key." ".$value."  ";
        }

        $str = trim($str);               
        
        $result = ["category" => $str, "delta" => $deltaInSeconds]; 
        $this->insertStaticstics($result);

        return $result;
    }
}



?>