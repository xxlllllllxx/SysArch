<?php
include_once("../../include/connect.php");

if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
    $tmpFilePath = $_FILES["image"]["tmp_name"];

    $fileContent = file_get_contents($tmpFilePath);
    $query = "INSERT INTO tbl_image(image_src) VALUES (?)";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $fileContent, PDO::PARAM_LOB);

    if ($stmt->execute()) {
        $imageID = $con->lastInsertId();
        $query = "UPDATE tbl_user SET image_id=? WHERE user_id=?";
        $stmt = $con->prepare($query);
        $stmt->execute([$imageID, $_POST["user_id"]]);

        $response = ["status" => "success", "message" => "Image uploaded and stored in the database successfully", "image_id" => $imageID];
        echo json_encode($response);
    } else {
        $response = ["status" => "failed", "message" => "Failed to store the image in the database"];
        echo json_encode($response);
    }
} else {
    $imageId = $_POST["image_id"];

    $query = "SELECT image_src FROM tbl_image WHERE image_id=?";
    $stmt = $con->prepare($query);
    $stmt->execute([$imageId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        echo base64_encode($result["image_src"]);
    } else {
        echo "";
    }
}
