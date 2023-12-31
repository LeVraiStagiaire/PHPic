<?php

include 'config/config.php';

session_start();

if (!isset($_SESSION['username'])) {
    header('location:login.php');
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['image'])) {
        $path = IMAGES_PATH . urldecode($_POST['image']);
        if (file_exists($path)) {
            unlink($path);
        }
    }
    $path = dirname($_POST['image']) . "/";
    include 'check-files.php';
    check($path);
    header('location:index.php?path=' . urlencode(str_replace(IMAGES_PATH, "", $path)));
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Supprimer - <?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a href="index.php"><img src="img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"></a>
            <a class="navbar-brand" href="index.php">Photos</a>
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
                <?php if ($_SESSION['role'] != "users") { ?><a class="nav-link" href="upload.php?path=<?php echo str_replace(IMAGES_PATH, "", $path); ?>">Upload</a><?php } ?>
                <?php if ($_SESSION['role'] == "administrators") { ?><a class="nav-link" href="admin.php">Admin</a><?php } ?>
                <a class="nav-link" href="logout.php">Déconnexion</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1>Suprimer <?php echo basename(urldecode($_GET['image'])); ?></h1>
        <p>Êtes-vous sûr(e) de vouloir supprimer cette photo ?</p>
        <form action="delete.php" method="post">
            <input type="hidden" name="image" value="<?php echo $_GET['image']; ?>">
            <button type="submit" class="btn btn-danger">Supprimer</button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>

</html>