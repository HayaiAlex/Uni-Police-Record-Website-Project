<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


// Validate username
if(!isset($_POST['username'])) {sendError('username missing', __LINE__);}
if(strlen($_POST['username'])>50) {sendError('username must be no more than 50 characters', __LINE__);}

// Validate current password
if (!isset($_POST['currentPassword'])) {sendError('current password missing', __LINE__);}
if(strlen($_POST['currentPassword'])>50) {sendError('currentPassword must be no more than 50 characters', __LINE__);}

// Validate new password
if (!isset($_POST['newPassword'])) {sendError('new password missing', __LINE__);}
if(strlen($_POST['newPassword'])>50) {sendError('newPassword must be no more than 50 characters', __LINE__);}


require_once(__DIR__.'/../protected/database.php');

try {
    $query = $db->prepare('UPDATE users
    SET User_password = :newPassword
    WHERE User_name = :username
    AND User_password = :currentPassword;');
    $query->bindValue('username', $_POST['username']);
    $query->bindValue('currentPassword', $_POST['currentPassword']);
    $query->bindValue('newPassword', $_POST['newPassword']);
    $query->execute();

    if (!$query->rowCount()) {
        echo '{"status":1, "message":"no rows changed. either password incorrect or new password same as current", "data":0}';
    } else {
        echo '{"status":1, "message":"password updated","data":1}';
    }

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
