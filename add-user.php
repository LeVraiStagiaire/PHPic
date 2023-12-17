<?php

include 'config/users.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $usersFile = fopen("config/users.php", "w");
    fwrite($usersFile, "<?php\n");
    fwrite($usersFile, "\n");
    fwrite($usersFile, "\$users = array(\n");
    foreach ($users as $username => $password) {
        fwrite($usersFile, "    '" . $username . "' => '" . $password . "',\n");
    }
    fwrite($usersFile, "    '" . $_POST['username'] . "' => '" . password_hash($_POST['password'], PASSWORD_DEFAULT) . "'\n");
    fwrite($usersFile, ");\n");
    fwrite($usersFile, "\n");
    fwrite($usersFile, "\$users_roles = array(\n");
    foreach ($users_roles as $username => $role) {
        fwrite($usersFile, "    '" . $username . "' => '" . $role . "',\n");
    }
    fwrite($usersFile, "    '" . $_POST['username'] . "' => '" . $_POST['role'] . "'\n");
    fwrite($usersFile, ");\n");
    fwrite($usersFile, "?>");
    fclose($usersFile);
    header('location: admin.php?tab=users');
}

?>