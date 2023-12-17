<?php

include 'config/config.php';

session_start();

if ($_SESSION['role'] != 'administrators') {
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin - <?php echo SITE_TITLE; ?></title>
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
                <?php if ($_SESSION['role'] != "users") { ?><a class="nav-link" href="upload.php?path=<?php echo str_replace(IMAGES_PATH, "", $path); ?>">Upload</a><?php } ?>
                <?php if ($_SESSION['role'] == "administrators") { ?><a class="nav-link active" aria-current="page" href="admin.php">Admin</a><?php } ?>
                <a class="nav-link" href="logout.php">Déconnexion</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link <?php if ($_GET['tab'] == "general" || $_GET['tab'] == "") {
                                        echo 'active" aria-current="page';
                                    } ?>" href="?tab=general">Général</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($_GET['tab'] == "users") {
                                        echo 'active" aria-current="page';
                                    } ?>" href="?tab=users">Utilisateurs</a>
            </li>
        </ul>
        <?php if ($_GET['tab'] == 'general' || $_GET['tab'] == "") { ?>
            <form action="apply-settings.php" method="post" enctype="multipart/form-data">
                <h3>Général</h3>
                <div class="mb-3">
                    <label for="siteTitle" class="form-label">Titre du site</label>
                    <input type="text" class="form-control" id="siteTitle" name="siteTitle" value="<?php echo SITE_TITLE; ?>">
                </div>
                <div class="mb-3">
                    <label for="imagesPath" class="form-label">Chemin des images</label>
                    <input type="text" class="form-control" id="imagesPath" name="imagesPath" value="<?php echo IMAGES_PATH; ?>">
                </div>
                <div class="mb-3">
                    <label for="imagesPerPage" class="form-label">Logo du site</label><br>
                    <img src="/img/logo.png" alt="Logo" width="60" height="60" class="align-text-top">
                    <input type="file" class="form-control" id="logo" name="logo">
                </div>
                <input type="submit" class="btn btn-primary" value="Enregistrer">
            </form>
        <?php } elseif ($_GET['tab'] == 'users') { ?>
            <h3>Utilisateurs</h3>
            <a type="button" class="btn btn-primary" data-bs-toggle="collapse" href="#addUser" role="button" aria-expanded="false" aria-controls="addUser">Ajouter un utilisateur</a>
            <div class="collapse" id="addUser">
                <div class="card card-body">
                    <form action="add-user.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control" id="username" name="username">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-select" id="role" name="role">
                                <option value="administrators">Administrateur</option>
                                <option value="editors">Editeur</option>
                                <option value="uploaders">Uploaders</option>
                                <option value="users">Utilisateur</option>
                            </select>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Ajouter">
                    </form>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nom d'utilisateur</th>
                        <th scope="col">Rôle</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'config/users.php';
                    foreach ($users as $username => $password) {
                        echo '<tr>';
                        echo '<td>' . $username . '</td>';
                        echo '<td>' . $users_roles[$username] . '</td>';
                        echo '<td><a href="delete-user.php?username=' . $username . '" class="btn btn-danger">Supprimer</a></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</body>

</html>