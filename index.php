<?php

include 'config/config.php';

session_start();

if (!isset($_SESSION['username'])) {
    header('location:login.php');
}

if (isset($_GET['path'])) {
    $path = IMAGES_PATH . urldecode($_GET['path']);
} else {
    $path = IMAGES_PATH;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <img src="img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
            <a class="navbar-brand" href="index.php">Photos</a>
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
                <?php if ($_SESSION['role'] != "users") { ?><a class="nav-link" href="upload.php?path=<?php echo str_replace(IMAGES_PATH, "", $path); ?>">Upload</a><?php } ?>
                <a class="nav-link" href="logout.php">Déconnexion</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <?php $cutted_path = explode("/", str_replace(IMAGES_PATH, "", $path));
                if (count($cutted_path) > 1) {
                    echo '<li class="breadcrumb-item"><a href="index.php?path=">Racine</a></li>';
                    foreach ($cutted_path as $key => $value) {
                        if ($value != "") {
                            if ($key == array_key_last($cutted_path) - 1) {
                                echo '<li class="breadcrumb-item active" aria-current="page">' . $value . '</li>';
                            } else {
                                echo '<li class="breadcrumb-item"><a href="index.php?path=' . urlencode(implode("/", array_slice($cutted_path, 0, $key + 1)) . "/") . '">' . $value . '</a></li>';
                            }
                        }
                    }
                } else {
                    echo '<li class="breadcrumb-item active" aria-current="page">Racine</li>';
                }
                ?>
            </ol>
        </nav>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            if (isset($_GET['page'])) {
                $current_page = $_GET['page'];
            } else {
                $current_page = 1;
            }

            $all_images = glob($path . "*.{jpg,png,gif}", GLOB_BRACE);
            usort($all_images, function ($a, $b) {
                return filemtime($b) - filemtime($a);
            });
            $all_subfolders = glob($path . "*", GLOB_ONLYDIR);

            $subfolders = array_slice($all_subfolders, ($current_page - 1) * 9, 9);
            $images = array_slice($all_images, ($current_page - 1) * 9, 9 - count($subfolders));

            foreach ($subfolders as $subfolder) {
                echo '<div class="col">';
                echo '<div class="card">';
                echo '<a href="index.php?path=' . urlencode(str_replace(IMAGES_PATH, "", $subfolder) . "/") . '"><img src="img/folder.png" class="card-img-top" alt="' . basename($subfolder) . '"></a>';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . basename($subfolder) . '</h5>';
                echo '<p class="card-text">Dossier</p>';
                echo '<a href="index.php?path=' . urlencode($subfolder) . '" class="btn btn-primary">Ouvrir</a>';
                if ($_SESSION['role'] != "users" && $_SESSION['role'] != "uploaders") {
                    echo '&nbsp;<a href="move.php?path=' . urlencode($subfolder) . '" class="btn btn-warning">Déplacer</a>';
                }
                if ($_SESSION['role'] != "users" && $_SESSION['role'] != "uploaders") {
                    echo '&nbsp;<a href="delete.php?path=' . urlencode($subfolder) . '" class="btn btn-danger">Supprimer</a>';
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            foreach ($images as $image) {
                $imagick = new Imagick($image);
                $imagick->thumbnailImage(0, 300);
                $image64 = base64_encode($imagick->getImageBlob());
                $file_date = date("d/m/Y H:i", filemtime($image));
                echo '<div class="col">';
                echo '<div class="card">';
                echo '<a href="image.php?image=' . urlencode(str_replace(IMAGES_PATH, "", $image)) . '"><img src="data:image/jpeg;base64,' . $image64 . '" class="card-img-top" alt="' . basename($image) . '"></a>';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . basename($image) . '</h5>';
                echo '<p class="card-text">Date : ' . $file_date . '</p>';
                echo '<a href="image.php?image=' . urlencode(str_replace(IMAGES_PATH, "", $image)) . '" class="btn btn-primary">Voir</a>';
                if ($_SESSION['role'] != "users" && $_SESSION['role'] != "uploaders") {
                    echo '&nbsp;<a href="move.php?image=' . urlencode(str_replace(IMAGES_PATH, "", $image)) . '" class="btn btn-warning">Déplacer</a>';
                }
                if ($_SESSION['role'] != "users" && $_SESSION['role'] != "uploaders") {
                    echo '&nbsp;<a href="delete.php?image=' . urlencode(str_replace(IMAGES_PATH, "", $image)) . '" class="btn btn-danger">Supprimer</a>';
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }

            ?>
        </div><br />
        <nav aria-label="Défilement des pages">
            <ul class="pagination justify-content-center">
                <?php
                $pages = ceil((count($all_images) + count($all_subfolders)) / 9) - 1;
                if ($current_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="index.php?path=' . urlencode(str_replace(IMAGES_PATH, "", $path)) . '&page=' . ($current_page - 1) . '">Précédent</a></li>';
                } else {
                    echo '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a></li>';
                }
                for ($i = 1; $i <= $pages; $i++) {
                    if ($i == $current_page) {
                        echo '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="index.php?path=' . urlencode(str_replace(IMAGES_PATH, "", $path)) . '&page=' . $i . '">' . $i . '</a></li>';
                    }
                }
                if ($current_page < $pages) {
                    echo '<li class="page-item"><a class="page-link" href="index.php?path=' . urlencode(str_replace(IMAGES_PATH, "", $path)) . '&page=' . ($current_page + 1) . '">Suivant</a></li>';
                } else {
                    echo '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Suivant</a></li>';
                }
                ?>
            </ul>
        </nav>

    </div>
</body>