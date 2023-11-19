<?php

include 'config/config.php';

session_start();

if (!isset($_SESSION['username'])) {
    header('location:login.php');
}

if ($_SESSION['role'] == "users") {
    header('location:index.php');
}

if (isset($_GET['path'])) {
    $path = IMAGES_PATH . urldecode($_GET['path']);
} else {
    $path = IMAGES_PATH;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['path'])) {
        $uploadpath = IMAGES_PATH . $_POST['path'];
    } else {
        $uploadpath = IMAGES_PATH;
    }

    $uploaded = array();

    foreach ($_FILES['formFile']['name'] as $position => $file) {
        if (move_uploaded_file($_FILES['formFile']['tmp_name'][$position], $uploadpath . $_FILES['formFile']['name'][$position])) {
            $uploaded[] = $_FILES['formFile']['name'][$position] . " envoyé avec succès !\n";
        } else {
            $uploaded[] = "Echec de l'envoi de " . $_FILES['formFile']['name'][$position] . "\n";
        }
    }
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
            <a class="navbar-brand" href="index.php">Photos</a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Accueil</a>
                <?php if ($_SESSION['role'] != "users") { ?><a class="nav-link active" aria-current="page" href="upload.php">Upload</a><?php } ?>
                <a class="nav-link" href="logout.php">Déconnexion</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <?php if ($_SERVER['REQUEST_METHOD'] == 'GET') { ?>
            <form enctype="multipart/form-data" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
                <div class="mb-3">
                    <input type="hidden" name="path" value="<?php echo str_replace(IMAGES_PATH, "", $path); ?>">
                    <label for="formFile" class="form-label">Sélectionnez une image à uploader</label>
                    <input class="form-control" type="file" id="formFile" name="formFile[]" multiple>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        <?php } else {
            foreach ($uploaded as $item) {
                echo "<div class='alert alert-primary' role='alert'>" . $item . "</div>";
            }
            echo "<a href='index.php?path=" . str_replace(IMAGES_PATH, "", $path) . "' class='btn btn-primary'>Retour</a>";
        } ?>
    </div>
</body>

</html>