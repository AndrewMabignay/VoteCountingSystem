<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$db_connection = 'mysql';
$db_host = 'sql309.infinityfree.com';  // Kumuha ng tamang database host mula sa cPanel
$db_port = 3306;
$db_name = 'if0_38968424_votesystem';  // Database name with epiz_ prefix
$db_user = 'if0_38968424';            // Database username na may epiz_ prefix
$db_pass = 'k0UbGNDq8Oi';            // Database password

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);

if (!$conn) {
    die('Connection Failed: ' . mysqli_connect_error());
}

// echo 'Connected Successfully!' . '<br>';
