<?php
include_once("../../include/connect.php");

if (isset($_POST["user_id"])) {
        $query = "SELECT 
                username, 
                email,
                first_name,
                middle_name, 
                last_name,
                date_of_birth,
                image_id
                FROM tbl_user
                WHERE user_id=?";
        $stmt = $con->prepare($query);
        $stmt->execute([$_POST["user_id"]]);

        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)[0]);
}
