<?php

include 'config/config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['path'])) {
        $dlpath = __DIR__ . '/' . $_GET['path'];
    } else {
        $dlpath = __DIR__ . IMAGES_PATH;
    }

    // cut the last / if it exists
    if (substr($dlpath, -1) == '/') {
        $dlpath = substr($dlpath, 0, -1);
    }

    $zip = new ZipArchive();
    $archive_path = __DIR__ . '/public/tmp/tmp.zip';

    if ($zip->open($archive_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        die("Impossible de créer l'archive");
    }

    // Add the entire tree to the archive
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dlpath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    echo 'Compression en cours...';

    foreach ($iterator as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($dlpath) + 1);
            if (basename($filePath) == 'dirindex.php' || str_contains($relativePath, '@eaDir')) {
                continue;
            }
            $zip->addFile($filePath, $relativePath);
            echo '.';
        }
    }

    $zip->close();

    echo 'Compression terminée.';
    echo 'Téléchargement en cours...';

    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="images.zip"');
    header('Content-Length: ' . filesize($archive_path));
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: 0');
    header('Pragma: no-cache');

    ob_clean();
    flush();
    $file = fopen($archive_path, 'rb');
    while (!feof($file)) {
        echo fread($file, 8192);
        flush();
    }
    fclose($file);
}
