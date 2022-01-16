<?php
function adminer_object() {
    class AdminerSoftware extends Adminer {
        function login($login, $password) {
            global $jush;
            if ($jush == "sqlite")
                return ($login === 'admin') && ($password === 'changeme');
            return true;
        }
        function databases($flush = true) {
            if (isset($_GET['sqlite']))
                return ["/var/www/inc/db/database.db", "/var/www/inc/db/database.db.example"];
            return get_databases($flush);
        }
    }
    return new AdminerSoftware;
}

include "./adminer.php";
// ../../db/database.db