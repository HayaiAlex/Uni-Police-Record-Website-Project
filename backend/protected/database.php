<?php

header('Content-Type: application/json');

// First find where we are and use the appropriate database information
$directory = getcwd();
$path = explode("backend",$directory)[0];

$dbusername = NULL;
$dbpassword = NULL;
$connection = NULL;

if ($path == "C:\\xampp\\htdocs\\") {
    $dbusername = 'root';
    $dbpassword = '';
    $connection = 'mysql:host=localhost; dbname=police; charset=utf8';
} elseif ($path == "/lhome/psxaa48/public_html/police/") {
    $dbusername = 'psxaa48_police';
    $dbpassword = 'AMNPJF';
    $connection = 'mysql:host=mysql.cs.nott.ac.uk; dbname=psxaa48_police; charset=utf8';
}


// Create a database connection
try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // enables try-catch of SQL statements
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // outputs associative array which is convertable to JSON
    ];
    $db = new PDO($connection, $dbusername, $dbpassword, $options);


} catch(PDOException $ex) {
    echo '{
        "status":0,
        "message":"cannont connect to database",
        "debug":'.__LINE__.'
    }';
    exit();
}