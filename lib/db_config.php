<?php

/**
 * database config
 */
define('DB_HOST', 'localhost'); //host
define('DB_USER', 'homestead'); // db username
define('DB_PASS', 'secret'); // db password
define('DB_NAME', 'misc'); // db name

require __DIR__ . "/DBManager.php";

//try {
//    $db = new DBManager();
//} catch (Exception $err) {
//    exit($err->getMessage());
//}