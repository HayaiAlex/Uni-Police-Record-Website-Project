<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Validate id
if(!isset($_POST['id'])) {sendError('id missing', __LINE__);}
if(!ctype_digit($_POST['id'])) {sendError('id must be a number', __LINE__);}

// Validate name
if(!isset($_POST['name'])) {sendError('name missing', __LINE__);}
if(strlen($_POST['name'])<2) {sendError('name must be at least 2 characters', __LINE__);}
if(strlen($_POST['name'])>50) {sendError('name must be less than 50 characters', __LINE__);}

require_once(__DIR__.'/../protected/database.php');

try {
    $query = $db->prepare('UPDATE people SET People_name = :name WHERE People_ID = :id');
    $query->bindValue('id', $_POST['id']);
    $query->bindValue('name', $_POST['name']);
    // $query->bindValue('address', $address);
    // $query->bindValue('licence', $licence);
    $query->execute();

    if (!$query->rowCount()) {
        sendError('user not found', __LINE__);
    }

    echo '{"status":1, "message":"user updated"}';
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


echo '{"status":1, "message":"person updated"';