<?php

include 'config/users.php';
include 'config/config.php';

//Put all files in dirindex.php

function check($path)
{

    $files = array();
    $files = glob(IMAGES_PATH . $path . "*");
    $dirindex = fopen(IMAGES_PATH . $path . "dirindex.php", "w");
    fwrite($dirindex, "<?php\n\n\$files = array(\n");
    foreach ($files as $file) {
        if ($file != "." && $file != ".." && $file != "dirindex.php") {
            try {
                $thumbnail = new Imagick($file);
                $thumbnail->thumbnailImage(0, 300);
                fwrite($dirindex, "\t\"" . basename($file) . "\" => \"" . base64_encode($thumbnail->getImageBlob()) . "\",\n");
            } catch (Exception $e) {
                echo "Erreur : ". $e->getMessage() ."\n";
                continue;
            }
        }
    }
    fwrite($dirindex, ");\n\n?>");
    fclose($dirindex);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['path'])) {
        $path = $_GET['path'];
    } else {
        $path = "";
    }
    check($path);
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    check($path);
}
