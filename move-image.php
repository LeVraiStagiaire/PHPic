<?php

include 'config/config.php';

copy(IMAGES_PATH . $_POST['image'], IMAGES_PATH . $_POST['path'] . basename($_POST['image']));
unlink(IMAGES_PATH . $_POST['image']);

$old_path = dirname($_POST['image']) . "/";
$path = $_POST['path'];
include 'check-files.php';
check($old_path);
check($path);

