<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Validate username
if(!isset($_POST['username'])) {sendError('username missing', __LINE__);}
if(strlen($_POST['username'])>50) {sendError('username must be less than 50 characters', __LINE__);}

// Validate password
if(!isset($_POST['password'])) {sendError('password missing', __LINE__);}
if(strlen($_POST['password'])>50) {sendError('password must be less than 50 characters', __LINE__);}

require_once(__DIR__.'/../protected/database.php');
require_once(__DIR__.'/../audit/create-audit.php');

try {
    $query = $db->prepare('SELECT * FROM users WHERE User_name = :username AND User_password = :password');
    $query->bindValue('username', $_POST['username']);
    $query->bindValue('password', $_POST['password']);
    $query->execute();

    // No results, username or password incorrect
    if (!$query->rowCount()) {
        // Check user exists
        $query = $db->prepare('SELECT * FROM users WHERE User_name = :username');
        $query->bindValue('username', $_POST['username']);
        $query->execute();

        // If user doesn't exist, username wrong
        if (!$query->rowCount()) {
            echo '{"status":1, "message":"user credentials denied", "data":"username does not exist"}';
        } else {
            echo '{"status":1, "message":"user credentials denied", "data":"password was incorrect"}';
        }
        exit();
    }

    $row = $query->fetch();

    // Add login audit log
    createLog($db, $_POST['username'], "logged in");

    echo '{"status":1, "message":"user credentials accepted", "data":{"username":"'.$_POST['username'].'", "admin":"'.$row['User_admin'].'"}}';
    exit();
} catch (PDOException $ex) {
    sendError('error executing query', __LINE__);
}



// Debug is line number error occured
function sendError($message = 'error', $debug = 0) {
    echo '{
        "status":0,
        "messsage":"'.$message.'",
        "debug":'.$debug.'
    }';
    exit();
}
