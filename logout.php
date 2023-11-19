<?php

session_start();

session_destroy();

echo "Vous avez été déconnecté. Redirection vers la page de connexion...";

header('Refresh: 3; URL = login.php');

?>