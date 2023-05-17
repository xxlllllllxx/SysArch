<?php

include_once("config.php");

$db_name = "dtb_student";
$uid = "root";
$pass = "";

$dsn = "mysql:host={$host};dbname={$db_name}";
try {
    $con = new PDO($dsn, $uid, $pass);
    if ($con) {
        //echo "SUCCESSFULLY CONNECTED TO DATABASE";
    } else {
        //echo "FAILED TO CONNECT TO DATABASE";
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
