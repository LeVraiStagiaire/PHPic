<?php

include 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $configFile = fopen("config/config.php", "w");
    fwrite($configFile, "<?php\n");
    fwrite($configFile, "\n");
    fwrite($configFile, "define('SITE_TITLE', '" . $_POST['siteTitle'] . "');\n");
    fwrite($configFile, "define('IMAGES_PATH', '" . $_POST['imagesPath'] . "');\n");
    fwrite($configFile, "define('LDAP_HOST', '" . LDAP_HOST . "');\n");
    fwrite($configFile, "define('LDAP_PORT', '" . LDAP_PORT . "');\n");
    fwrite($configFile, "define('LDAP_BASE_DN', '" . LDAP_BASE_DN . "');\n");
    fwrite($configFile, "define('LDAP_USER_DN', '" . LDAP_USER_DN . "');\n");
    fwrite($configFile, "define('LDAP_GROUP_DN', '" . LDAP_GROUP_DN . "');\n");
    fwrite($configFile, "define('LDAP_GROUP_ATTRIBUTE', '" . LDAP_GROUP_ATTRIBUTE . "');\n");
    fwrite($configFile, "define('LDAP_GROUP_ADMIN', '" . LDAP_GROUP_ADMIN . "');\n");
    fwrite($configFile, "define('LDAP_GROUP_USER', '" . LDAP_GROUP_USER . "');\n");
    fwrite($configFile, "define('LDAP_BIND_DN', '" . LDAP_BIND_DN . "');\n");
    fwrite($configFile, "define('LDAP_BIND_PASSWORD', '" . LDAP_BIND_PASSWORD . "');\n");
    fwrite($configFile, "\n");
    fwrite($configFile, "?>");
    fclose($configFile);
    header('location: admin.php');
}
