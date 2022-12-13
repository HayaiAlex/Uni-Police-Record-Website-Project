<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


// Validate username
if(!isset($_POST['username'])) {sendError('username missing', __LINE__);}
if(strlen($_POST['username'])>50) {sendError('username must be no more than 50 characters', __LINE__);}

// Validate password
if (!isset($_POST['password'])) {sendError('password missing', __LINE__);}
if(strlen($_POST['password'])>50) {sendError('password must be no more than 50 characters', __LINE__);}

// Admin status 0 by default
$admin = 0;

if (isset($_POST['admin'])) {
    $admin = $_POST['admin'];
}

require_once(__DIR__.'/../protected/database.php');
require_once(__DIR__.'/../audit/create-audit.php');
// Add user already exists with this username
try {
    $query = $db->prepare('INSERT INTO users (User_name, User_password, User_admin)
    VALUES (:username,:password,:admin);');
    $query->bindValue('username', $_POST['username']);
    $query->bindValue('password', $_POST['password']);
    $query->bindValue('admin', $admin);
    $query->execute();
    $userId = $db->lastInsertId();
 
    createUsersLog($db, $_POST['loggedInUsername'], "Created account", $_POST['username'], $_POST['password'], $admin);

    echo '{"status":1, "message":"account created"}';
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
