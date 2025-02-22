<?php

include 'config/config.php';

session_start();

if (!isset($_SESSION['username'])) {
    header('location:login.php');
}

if ($_SESSION['role'] == "users") {
    header('location:index.php');
}

if (isset($_GET['path'])) {
    $path = IMAGES_PATH . urldecode($_GET['path']);
} else {
    $path = IMAGES_PATH;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['path'])) {
        $uploadpath = IMAGES_PATH . $_POST['path'];
    } else {
        $uploadpath = IMAGES_PATH;
    }

    if (move_uploaded_file($_FILES['formFile']['tmp_name'], $uploadpath . $_FILES['formFile']['name'])) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Fichier envoyé avec succès"]);
    } else {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Erreur lors de l'envoi du fichier"]);
    }
}
