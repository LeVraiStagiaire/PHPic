<?php

include 'config/config.php';

session_start();

if (!isset($_SESSION['username'])) {
    header('location:login.php');
}

//get next and previous image
include IMAGES_PATH . urldecode(dirname($_GET['image'])) . "/dirindex.php";
$next = false;
$previous = false;
while (key($files) !== basename(urldecode($_GET['image']))) {
    next($files);
}

if (next($files) !== false) {
    $next = key($files);
}

reset($files);

while (key($files) !== basename(urldecode($_GET['image']))) {
    next($files);
}

if (prev($files) !== false) {
    $previous = key($files);
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?php echo basename($_GET['image']); ?> - <?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body style="background-color: black;">
    <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
            <img src="img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
            <a class="navbar-brand" href="index.php"><?php echo SITE_TITLE; ?></a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Accueil</a>
                <?php if ($_SESSION['role'] != "users") { ?><a class="nav-link" href="upload.php">Upload</a><?php } ?>
                <?php if ($_SESSION['role'] == "administrators") { ?><a class="nav-link" href="admin.php">Admin</a><?php } ?>
                <a class="nav-link" href="logout.php">Déconnexion</a>
            </div>
        </div>
    </nav>
    <div style="text-align: center;">
        <?php if ($previous !== false) { ?>
            <a style="position: absolute; top: 50%; left: 0; padding: 10px; color: white; font-size: 50px; text-decoration: none;" href="?image=<?php echo urlencode(dirname($_GET['image']) . "/" . $previous); ?>"><</a>
                <?php } ?>
                <img src="<?php echo IMAGES_PATH . urldecode($_GET['image']); ?>" alt="<?php echo basename(urldecode($_GET['image'])); ?>" style="height: 94vh;">
                <?php if ($next !== false) { ?>
                    <a style="position: absolute; top: 50%; right: 0; padding: 10px; color: white; font-size: 50px; text-decoration: none;" href="?image=<?php echo urlencode(dirname($_GET['image']) . "/" . $next); ?>">></a>
                <?php } ?>
    </div>
    <div style="position: absolute; bottom: 0; right: 0;">
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="<?php echo IMAGES_PATH . urldecode($_GET['image']); ?>" download="<?php echo basename(urldecode($_GET['path'])); ?>" class="btn btn-secondary">Télécharger</a>
            <a type="button" class="btn btn-secondary" href="index.php?path=<?php echo urlencode(dirname($_GET['image']) . "/"); ?>">Retour</a>
        </div>
</body>

</html>