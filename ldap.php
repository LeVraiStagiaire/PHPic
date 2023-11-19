<?php

function login($username, $password)
{

    $ldap = ldap_connect(LDAP_HOST, LDAP_PORT);
    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

    if ($ldap) {
        $ldaprdn = LDAP_BIND_DN;
        $ldappass = LDAP_BIND_PASSWORD;

        if (ldap_bind($ldap, $ldaprdn, $ldappass)) {
            $filter = "(&(objectClass=posixAccount)(uid=" . $username . "))";
            $result = ldap_search($ldap, LDAP_BASE_DN, $filter);
            $entries = ldap_get_entries($ldap, $result);

            if ($entries['count'] == 1) {
                if (ldap_bind($ldap, $entries[0]['dn'], $password)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        echo "<script>alert('Erreur de connexion au serveur LDAP')</script>";
    }
}

?>