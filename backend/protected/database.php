<?php

header('Content-Type: application/json');

try {
    $userName = 'root';
    $password = '';
    $connection = 'mysql:host=localhost; dbname=police; charset=utf8';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // enables try-catch of SQL statements
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // outputs associative array which is convertable to JSON
    ];
    $db = new PDO( $connection, $userName, $password, $options);


} catch(PDOException $ex) {
    echo '{
        "status":0,
        "message":"cannont connect to database",
        "debug":'.__LINE__.'
    }';
    exit();
}