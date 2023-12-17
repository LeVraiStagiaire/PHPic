<?php

include 'config/users.php';

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
                continue;
            }
        }
    }
    fwrite($dirindex, ");\n\n?>");
    fclose($dirindex);
}
