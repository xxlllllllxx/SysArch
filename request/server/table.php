<?php
include_once("../../include/connect.php");

$query = "SELECT * from tbl_user";
$stmt = $con->prepare($query);
$stmt->execute();

header('Content-Type: application/json');
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
