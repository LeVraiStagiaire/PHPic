<?php

include 'config/config.php';

$path = IMAGES_PATH . $_POST['path'] . $_POST['folder_name'];

mkdir($path);

?>