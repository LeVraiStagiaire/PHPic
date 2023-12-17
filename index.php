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
            <a class="navbar-brand" href="index.php"><?php echo SITE_TITLE; ?></a>
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
                <?php if ($_SESSION['role'] != "users") { ?><a class="nav-link" href="upload.php?path=<?php echo str_replace(IMAGES_PATH, "", $path); ?>">Upload</a><?php } ?>
                <?php if ($_SESSION['role'] == "administrators") { ?><a class="nav-link" href="admin.php">Admin</a><?php } ?>
                <a class="nav-link" href="logout.php">Déconnexion</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="hstack">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">Vous êtes ici :&nbsp;
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
            <div class="button-group ms-auto">
                <?php if ($_SESSION['role'] != "users" && $_SESSION['role'] != "uploaders") { ?><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newFolder">Nouveau dossier</button><?php } ?>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            if (isset($_GET['page'])) {
                $current_page = $_GET['page'];
            } else {
                $current_page = 1;
            }

            include $path . "dirindex.php";
            $all_subfolders = glob($path . "*", GLOB_ONLYDIR);

            // Show only 9 items from folder and files per page
            $all_subfolders = array_slice($all_subfolders, ($current_page - 1) * 9, 9);
            $showing_files = array_slice($files, ($current_page - 1) * 9, 9 - count($all_subfolders));

            foreach ($all_subfolders as $subfolder) {
                echo '<div class="col">';
                echo '<div class="card">';
                echo '<a href="index.php?path=' . urlencode(str_replace(IMAGES_PATH, "", $subfolder) . "/") . '"><img src="img/folder.png" class="card-img-top" alt="' . basename($subfolder) . '"></a>';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . basename($subfolder) . '</h5>';
                echo '<p class="card-text">Dossier</p>';
                echo '<a href="index.php?path=' . urlencode(basename($subfolder) . "/") . '" class="btn btn-primary">Ouvrir</a>';
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
            foreach ($showing_files as $image => $thumbnail) {
                $file_date = date("d/m/Y H:i", filemtime($path . $image));
                echo '<div class="col">';
                echo '<div class="card">';
                echo '<a href="image.php?image=' . urlencode(str_replace(IMAGES_PATH, "", $path . $image)) . '"><img src="data:image/jpeg;base64,' . $thumbnail . '" class="card-img-top" alt="' . basename($image) . '"></a>';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . basename($image) . '</h5>';
                echo '<p class="card-text">Date : ' . $file_date . '</p>';
                echo '<a href="image.php?image=' . urlencode(str_replace(IMAGES_PATH, "", $path . $image)) . '" class="btn btn-primary">Voir</a>';
                if ($_SESSION['role'] != "users" && $_SESSION['role'] != "uploaders") {
                    echo '&nbsp;<a href="move.php?image=' . urlencode(str_replace(IMAGES_PATH, "", $path . $image)) . '" class="btn btn-warning">Déplacer</a>';
                }
                if ($_SESSION['role'] != "users" && $_SESSION['role'] != "uploaders") {
                    echo '&nbsp;<a href="delete.php?image=' . urlencode(str_replace(IMAGES_PATH, "", $path . $image)) . '" class="btn btn-danger">Supprimer</a>';
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }

            if (count($files) + count($all_subfolders) == 0) {
                echo '<div class="col"></div><p class="col fs-2 text-center">Aucun fichier ou dossier à afficher.</p>';
            }

            ?>
        </div><br />
        <div class="text-center">
            <span>Affichage de <?php echo ($current_page - 1) * 9 + 1; ?> à <?php echo ($current_page - 1) * 9 + count($all_subfolders) + count($showing_files); ?> sur <?php echo count($files) + count($all_subfolders); ?> éléments.</span>
        </div>
        <nav aria-label="Défilement des pages">
            <ul class="pagination justify-content-center">
                <?php
                $pages = ceil((count($files) + count($all_subfolders)) / 9) - 1;
                if ($current_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="index.php?path=' . urlencode(str_replace(IMAGES_PATH, "", $path)) . '&page=' . ($current_page - 1) . '">Précédent</a></li>';
                } else {
                    echo '<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a></li>';
                }
                for ($i = 1; $i <= $pages + 1; $i++) {
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
        <div class="modal fade" id="newFolder" tabindex="-1" aria-labelledby="newFolderModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newFolderModal">Nouveau dossier</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="folderName" class="form-label">Nom du dossier</label>
                            <input type="text" class="form-control" id="folderName" name="folderName">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" onclick="createFolder();">Créer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function createFolder() {
            var folderName = document.getElementById("folderName").value;
            var path = "<?php echo str_replace(IMAGES_PATH, "", $path); ?>";
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "create-folder.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    window.location.reload();
                }
            };
            xhr.send("path=" + path + "&folder_name=" + folderName);
        }
    </script>
</body>