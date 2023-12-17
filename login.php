<?php

session_start();

if(isset($_SESSION['username'])){
    header('location:index.php');
}

include 'config/config.php';
include 'config/users.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    //fetch user from file
    if (isset($users[$username])) {
        if (password_verify($password, $users[$username])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $users_roles[$username];
            header('location:index.php');
        } else {
            $errored = true;
        }
    } elseif (LDAP_HOST != NULL) {
        if (login($username, $password)) {
            $_SESSION['username'] = $username;
            if (in_array($username, $users_roles)) {
                $_SESSION['role'] = $users_roles[$username];
            } else {
                $_SESSION['role'] = 'users';
            }
            header('location:index.php');
        } else {
            $errored = true;
        }
    } else {
        $errored = true;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - <?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php if (isset($errored)) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Nom d'utilisateur ou mot de passe incorrect
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php } ?>
    <center>
        <h1 style="margin-top: 10%;">Connexion</h1>
        <div class="card" style="width: 20rem; text-align: start; box-shadow: 5px 5px 5px grey;">
            <div class="card-body">
                <form action="login.php" method="post">
                    <div class="mb-3">
                        <label for="usernameField" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" name="username" id="usernameField" placeholder="Nom d'utilisateur" required />
                    </div>
                    <div class="mb-3">
                        <label for="passwordField" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="password" id="passwordField" placeholder="Mot de passe" required />
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Connexion</button>
                    </div>
                </form>
            </div>
        </div>
    </center>
</body>
</html>