<?php

include 'config/users.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $usersFile = fopen("config/users.php", "w");
    fwrite($usersFile, "<?php\n");
    fwrite($usersFile, "\n");
    fwrite($usersFile, "\$users = array(\n");
    foreach ($users as $username => $password) {
        if ($username != $_POST['username']) {
            fwrite($usersFile, "    '" . $username . "' => '" . $password . "',\n");
        }
    }
    fwrite($usersFile, ");\n");
    fwrite($usersFile, "\n");
    fwrite($usersFile, "\$users_roles = array(\n");
    foreach ($users_roles as $username => $role) {
        if ($username != $_POST["username"]) {
            fwrite($usersFile, "    '" . $username . "' => '" . $role . "',\n");
        }
    }
    fwrite($usersFile, ");\n");
    fwrite($usersFile, "?>");
    fclose($usersFile);
    header('location: admin.php?tab=users');
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Supprimer un utilisateur</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Supprimer un utilisateur</h1>
        <p>Voulez-vous vraiment supprimer <?php echo $_GET['username']; ?> ?</p>
        <form action="delete-user.php" method="post">
            <input type="hidden" name="username" value="<?php echo $_GET['username']; ?>">
            <input type="submit" class="btn btn-danger" value="Supprimer">
        </form>
    </div>
</body>

</html>