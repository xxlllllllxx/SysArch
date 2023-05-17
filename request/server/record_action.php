<?php
include_once("../../include/connect.php");

if (isset($_POST["add_new_record"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $firstName = $_POST["firstName"];
    $middleName = $_POST["middleName"];
    $lastName = $_POST["lastName"];
    $dateOfBirth = $_POST["dateOfBirth"];

    if (empty($username) || empty($email) || empty($firstName) || empty($lastName) || empty($dateOfBirth)) {
        $response = [
            "status" => "failed",
            "message" => "Please fill in all the required fields"
        ];
        echo json_encode($response);
        exit;
    }

    $query = "INSERT INTO tbl_user (username, email, first_name, middle_name, last_name, date_of_birth) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->execute([$username, $email, $firstName, $middleName, $lastName, $dateOfBirth]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success", "message" => "New record added successfully"]);
    } else {
        echo json_encode(["status" => "failed", "message" => "Failed to add new record"]);
    }
}



if (isset($_POST["delete_id"])) {
    $deleteId = $_POST["delete_id"];
    $query = "DELETE FROM tbl_user WHERE user_id=?";
    $stmt = $con->prepare($query);
    $stmt->execute([$deleteId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success", "message" => "User '{$_POST['username']}' has been deleted"]);
    } else {
        echo json_encode(["status" => "failed", "message" => "Failed to delete user '{$_POST['username']}'"]);
    }
}

if (isset($_POST["edit_id"])) {
    $editId = $_POST["edit_id"];
    $query = "UPDATE tbl_user SET username=?, email=?, first_name=?, middle_name=?, last_name=?, date_of_birth=? WHERE user_id=?";
    $stmt = $con->prepare($query);
    $stmt->execute([$_POST["username"], $_POST["email"], $_POST["firstName"], $_POST["middleName"], $_POST["lastName"], $_POST["dateOfBirth"], $editId]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success", "message" => "User information has been updated successfully"]);
    } else {
        echo json_encode(["status" => "failed", "message" => "Failed to update user '{$_POST['username']}' information"]);
    }
}
