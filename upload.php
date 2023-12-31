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
    $uploaded_file = array();

    foreach ($_FILES['formFile']['name'] as $position => $file) {
        if (move_uploaded_file($_FILES['formFile']['tmp_name'][$position], $uploadpath . $_FILES['formFile']['name'][$position])) {
            $uploaded[] = $_FILES['formFile']['name'][$position] . " envoyé avec succès !\n";
            $uploaded_file[] = $_FILES["formFile"]["name"][$position];
        } else {
            $uploaded[] = "Echec de l'envoi de " . $_FILES['formFile']['name'][$position] . ".\n";
            $uploaded_file[] = $_FILES["formFile"]["name"][$position];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Upload - <?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a href="index.php"><img src="img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"></a>
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
        <?php if ($_SERVER['REQUEST_METHOD'] == 'GET') { ?>
            <form enctype="multipart/form-data" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
                <div class="mb-3">
                    <input type="hidden" name="path" value="<?php echo str_replace(IMAGES_PATH, "", $path); ?>">
                    <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="123">
                    <label for="formFile" class="form-label">Sélectionnez une image à uploader</label>
                    <input class="form-control" type="file" id="formFile" name="formFile[]" multiple>
                </div>
                <div class="mb-3"><span>Les images seront uploadées dans le dossier <?php if ($_GET['path'] == "") {
                                                                                        echo "racine";
                                                                                    } else {
                                                                                        echo $_GET['path'];
                                                                                    } ?></span></div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        <?php } else {
            for ($i = 0; $i < count($uploaded); $i++) {
                if (file_exists($path . $uploaded_file[$i])) {
                    echo "<div class='alert alert-primary' role='alert'>" . $uploaded[$i] . "</div>";
                }
            }
            include 'check-files.php';
            check(str_replace(IMAGES_PATH, "", $path));
            echo "<a href='index.php?path=" . str_replace(IMAGES_PATH, "", $path) . "' class='btn btn-primary'>Retour</a>";
        } ?>
    </div>
</body>

</html>