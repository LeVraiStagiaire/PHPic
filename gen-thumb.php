<?php

include 'config/config.php';

function generateThumbnails($image)
{
    if (file_exists(IMAGES_PATH . dirname($image) . "/thumb/") == false) {
        mkdir(IMAGES_PATH . dirname($image) . "/thumb/", 0755, true);
    }
    $path = IMAGES_PATH . $image;
    $thumbnail = new Imagick($path);
    $thumbnail->thumbnailImage(0, 300);
    $thumbPath = IMAGES_PATH . dirname($image) . "/thumb/" . basename($image);
    $thumbnail->writeImage($thumbPath);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['image'])) {
        $image = $_GET['image'];
        generateThumbnails($image);
    }
}
