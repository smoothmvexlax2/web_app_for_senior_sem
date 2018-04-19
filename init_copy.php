<?

/***********************
Database Connection
************************/
define('DB_SERVER','SERVER');
define('DB_USERNAME','USERNAME');
define('DB_PASSWORD','PASSWORD');
define('DB_DATABASE','DATABASE');
define('SECRET', 'q1t5so!');

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit;
}

/******************************************************************************************
Database Tables
******************************************************************************************/
define('FORM_TABLE','heitkotter_formdata');

define('ACCT_TABLE','heitkotter_accounts');

define('LOGIN_TABLE','heitkotter_logins');

/******************************************************************************************
Classes
******************************************************************************************/
require_once 'class_lib.php';  
// Wrapper for some utility functions that are useful globally.

require_once 'class_pageable_list.php';
// A class to facilitate fancy listings with pagination.

/******************************************************************************************
Native Sessions
******************************************************************************************/
session_start();


// No whitespace after the closing php tag because that generates script output.
?>