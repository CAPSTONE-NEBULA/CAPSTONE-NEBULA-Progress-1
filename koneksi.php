<?php

$host = getenv('DB_HOST') ?: 'sql211.infinityfree.com';
$username = getenv('DB_USER') ?: 'if0_38997018';
$password = getenv('DB_PASS') ?: 'AlfianAdien';
$database = getenv('DB_NAME') ?: 'if0_38997018_sekolah_sd';

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Database connection failed. Please check the configuration.");
}

mysqli_set_charset($connection, "utf8");
?>
