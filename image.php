<?php

include 'config/config.php';

session_start();

if (!isset($_SESSION['username'])) {
    header('location:login.php');
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

<body style="background-color: black;">
    <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
            <img src="img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
            <a class="navbar-brand" href="index.php">Photos</a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Accueil</a>
                <?php if ($_SESSION['role'] != "users") { ?><a class="nav-link" href="upload.php">Upload</a><?php } ?>
                <a class="nav-link" href="logout.php">Déconnexion</a>
            </div>
        </div>
    </nav>
    <div style="text-align: center;">
        <img src="<?php echo IMAGES_PATH.urldecode($_GET['image']); ?>" alt="<?php echo basename(urldecode($_GET['path'])); ?>" style="height: 94vh;">
    </div>
    <div style="position: absolute; bottom: 0; right: 0;">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-secondary" onclick="window.history.back();">Retour</button>
    </div>
</body>
</html>