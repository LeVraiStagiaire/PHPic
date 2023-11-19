<?php

if (file_exists("config.php")) {
    header('location:index.php');
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $configFile = fopen("config/config.php", "w");
        fwrite($configFile, "<?php\n");
        fwrite($configFile, "\n");
        fwrite($configFile, "define('IMAGES_PATH', 'public/images/');\n");
        fwrite($configFile, "define('LDAP_HOST', NULL);\n");
        fwrite($configFile, "define('LDAP_PORT', NULL);\n");
        fwrite($configFile, "define('LDAP_BASE_DN', NULL);\n");
        fwrite($configFile, "define('LDAP_USER_DN', NULL);\n");
        fwrite($configFile, "define('LDAP_GROUP_DN', NULL);\n");
        fwrite($configFile, "define('LDAP_GROUP_ATTRIBUTE', NULL);\n");
        fwrite($configFile, "define('LDAP_GROUP_ADMIN', NULL);\n");
        fwrite($configFile, "define('LDAP_GROUP_USER', NULL);\n");
        fwrite($configFile, "define('LDAP_BIND_DN', NULL);\n");
        fwrite($configFile, "define('LDAP_BIND_PASSWORD', NULL);\n");
        fwrite($configFile, "\n");
        fwrite($configFile, "?>");
        fclose($configFile);

        $usersFile = fopen("config/users.php", "w");
        fwrite($usersFile, "<?php\n");
        fwrite($usersFile, "\n");
        fwrite($usersFile, "$users = array(\n");
        fwrite($usersFile, "    'admin' => '".password_hash($_POST['password'], PASSWORD_DEFAULT)."\n");
        fwrite($usersFile, ");\n");
        fwrite($usersFile, "\n");
        fwrite($usersFile, "$users_roles = array(\n");
        fwrite($usersFile, "    'admin' => 'administrators'\n");
        fwrite($usersFile, ");\n");
        fwrite($usersFile, "?>");

        echo "<script>alert('Installation terminée !');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Installation</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <center>
        <h1 style="margin-top: 10%;">Installation</h1>
        <div class="card" style="width: 25rem; text-align: start; box-shadow: 5px 5px 5px grey;">
            <div class="card-body">
                <form action="install.php" method="post">
                    <h4>Mot de passe du compte admin</h4>
                    <div class="mb-3">
                        <label for="passwordField" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="password" id="passwordField" placeholder="Mot de passe" required />
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Installer</button>
                    </div>
                </form>
            </div>
        </div>
    </center>
</body>