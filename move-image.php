<?php

include 'config/config.php';

copy(IMAGES_PATH . $_POST['image'], IMAGES_PATH . $_POST['path'] . basename($_POST['image']));
unlink(IMAGES_PATH . $_POST['image']);

$path = IMAGES_PATH . dirname($_POST['path']) . "/";
include $path . "dirindex.php";
//Rewrite dirindex.php
$dirindex = fopen($path . "dirindex.php", "w");
fwrite($dirindex, "<?php\n\n\$files = array(\n");
foreach ($files as $file => $value) {
    fwrite($dirindex, "\t\"" . $file . "\" => \"" . $value . "\",\n");
}
fwrite($dirindex, "\t\"" . basename($_POST['image']) . "\" => \"" . base64_encode(file_get_contents(IMAGES_PATH . $_POST['path'] . basename($_POST['image']))) . "\",\n");
//Write new file
$thumbnail = new Imagick(IMAGES_PATH . $_POST['path'] . basename($_POST['image']));
$thumbnail->thumbnailImage(0, 300);
fwrite($dirindex, "\t\"" . basename($_POST['image']) . "\" => \"" . base64_encode($thumbnail->getImageBlob()) . "\",\n");
fwrite($dirindex, ");\n\n?>");

//Remove old file from dirindex.php
$dirindex = fopen(IMAGES_PATH . dirname($_POST['image']) . "dirindex.php", "w");
fwrite($dirindex, "<?php\n\n\$files = array(\n");
foreach ($files as $file => $value) {
    if ($file != basename($_POST['image'])) {
        fwrite($dirindex, "\t\"" . $file . "\" => \"" . $value . "\",\n");
    }
}
fwrite($dirindex, ");\n\n?>");
