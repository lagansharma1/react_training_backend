<?php

// Note: This file should be included first in every PHP page.
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Define constants for paths and URLs
define('BASE_PATH', dirname(dirname(__FILE__)));
define('APP_FOLDER', 'simpleadmin');
define('CURRENT_PAGE', basename($_SERVER['REQUEST_URI']));
define('BASEURL', 'https://training.test');

// Include necessary files
require_once BASE_PATH . '/lib/MysqliDb/MysqliDb.php';
require_once BASE_PATH . '/helpers/helpers.php';
require_once BASE_PATH . '/config/db.php';


// Define database constants (make sure they are defined before any usage)
define('DB_HOST', "localhost");
define('DB_USER', "root");
define('DB_PASSWORD', "");
define('DB_NAME', "training");

/**
 * Get instance of DB object
 */
function getDbInstance() {
    return new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
}
