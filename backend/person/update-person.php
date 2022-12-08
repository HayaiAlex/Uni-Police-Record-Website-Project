<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Validate id
if(!isset($_POST['id'])) {sendError('id missing', __LINE__);}
if(!ctype_digit($_POST['id'])) {sendError('id must be a number', __LINE__);}

// Validate name
if(!isset($_POST['name'])) {sendError('name missing', __LINE__);}

$id = $_POST['id'];
$name = $_POST['name'];
$address = NULL;
$licence = NULL;

if(isset($_POST['address'])) {
    $address = $_POST['address'];
}
if(isset($_POST['licence'])) {
    $licence = $_POST['licence'];
}

require_once(__DIR__.'/../protected/database.php');

try {
    $query = $db->prepare('UPDATE people SET People_name = :name, People_address = :address, People_licence = :licence WHERE People_ID = :id');
    $query->bindValue('id', $_POST['id']);
    $query->bindValue('name', $_POST['name']);
    $query->bindValue('address', $address);
    $query->bindValue('licence', $licence);
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