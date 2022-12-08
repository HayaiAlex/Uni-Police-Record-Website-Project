<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Validate id
if(!isset($_POST['id'])) {sendError('id missing', __LINE__);}
if(!ctype_digit($_POST['id'])) {sendError('id must be a number', __LINE__);}

// Validate make + model
if(!isset($_POST['make'])) {sendError('make missing', __LINE__);}
if(!isset($_POST['model'])) {sendError('model missing', __LINE__);}

$id = $_POST['id'];
$make = $_POST['make'];
$model = $_POST['model'];
$colour = NULL;
$licence = NULL;

if(isset($_POST['colour'])) {
    $colour = $_POST['colour'];
}
if(isset($_POST['licence'])) {
    $licence = $_POST['licence'];
}

require_once(__DIR__.'/../protected/database.php');

try {
    $query = $db->prepare('UPDATE vehicle SET Vehicle_make = :make, Vehicle_model = :model, Vehicle_colour = :colour, Vehicle_licence = :licence WHERE Vehicle_ID = :id');
    $query->bindValue('id', $id);
    $query->bindValue('make', $make);
    $query->bindValue('model', $model);
    $query->bindValue('colour', $colour);
    $query->bindValue('licence', $licence);
    $query->execute();

    if (!$query->rowCount()) {
        sendError('vehicle not found', __LINE__);
    }

    echo '{"status":1, "message":"vehicle updated"}';
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