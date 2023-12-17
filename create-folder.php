<?php

include 'config/config.php';

$path = IMAGES_PATH . $_POST['path'] . $_POST['folder_name'];

mkdir($path);

$dirindex = fopen($path . "/dirindex.php", "w");
fwrite($dirindex, "<?php\n\n\$files = array(\n");
fwrite($dirindex, ");\n\n?>");
fclose($dirindex);

?>