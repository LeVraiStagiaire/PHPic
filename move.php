<?php

include 'config/config.php';

session_start();

if (!isset($_SESSION['username'])) {
    header('location:login.php');
}

if ($_SESSION['role'] == "users" || $_SESSION['role'] == "uploaders") {
    header('location:index.php');
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
    <title>Upload</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <img src="img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
            <a class="navbar-brand" href="index.php"><?php echo SITE_TITLE; ?></a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Accueil</a>
                <?php if ($_SESSION['role'] != "users") { ?><a class="nav-link active" aria-current="page" href="upload.php">Upload</a><?php } ?>
                <?php if ($_SESSION['role'] == "administrators") { ?><a class="nav-link" href="admin.php">Admin</a><?php } ?>
                <a class="nav-link" href="logout.php">Déconnexion</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="hstack">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <?php $cutted_path = explode("/", str_replace(IMAGES_PATH, "", $path));
                    if (count($cutted_path) > 1) {
                        echo '<li class="breadcrumb-item"><a href="move.php?path=/&image=' . $_GET['image'] . '">Racine</a></li>';
                        foreach ($cutted_path as $key => $value) {
                            if ($value != "") {
                                if ($key == array_key_last($cutted_path) - 1) {
                                    echo '<li class="breadcrumb-item active" aria-current="page">' . $value . '</li>';
                                } else {
                                    echo '<li class="breadcrumb-item"><a href="move.php?path=' . urlencode(implode("/", array_slice($cutted_path, 0, $key + 1)) . "/") . '&image=' . $_GET['image'] . '">' . $value . '</a></li>';
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
                <button type="button" class="btn btn-success" onclick="move();">Déplacer ici</button>
                <a href="index.php?path=<?php echo urlencode(str_replace(IMAGES_PATH, "", $path) . "/"); ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
        <div class="list-group">
            <?php
            $dirs = glob($path . '*', GLOB_ONLYDIR);
            foreach ($dirs as $dir) {
                echo '<a href="move.php?path=' . urlencode(str_replace(IMAGES_PATH, "", $dir) . "/") . '&image=' . $_GET['image'] . '" class="list-group-item list-group-item-action"><img src="img/folder.png" height="24" />&nbsp;' . basename($dir) . '</a>';
            }
            ?>
            <a href="#" class="list-group-item list-group-item-action" aria-current="true" data-bs-toggle="modal" data-bs-target="#newFolder">
                <img src="img/new_folder.png" height="24" />&nbsp;Nouveau dossier
            </a>
        </div>
    </div>

    <div class="modal fade" id="newFolder" tabindex="-1" aria-labelledby="newFolderLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newFolderLabel">Nouveau dossier</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="folderName" class="form-label">Nom du dossier</label>
                    <input type="text" class="form-control" id="folderName" placeholder="Nom du dossier">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="createFolder();">Créer</button>
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

        function move() {
            var path = "<?php echo str_replace(IMAGES_PATH, "", $path); ?>";
            var image = "<?php echo $_GET['image']; ?>";
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "move-image.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    window.location.href = "index.php?path=" + path;
                }
            };
            xhr.send("path=" + path + "&image=" + image);
        }
    </script>
</body>

</html>