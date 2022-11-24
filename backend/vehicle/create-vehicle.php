<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if(!isset($_POST['make'])) {sendError('make missing', __LINE__);}
if(strlen($_POST['make'])>20) {sendError('make must be less than 21 characters', __LINE__);}

if(!isset($_POST['model'])) {sendError('model missing', __LINE__);}
if(strlen($_POST['model'])>20) {sendError('model must be less than 21 characters', __LINE__);}

$make = $_POST['make'];
$model = $_POST['model'];
$colour = NULL;
$licence = NULL;

if(isset($_POST['colour'])) {
    $colour = $_POST['colour'];
    if(strlen($colour)>20) { sendError('colour must be less than 21 characters', __LINE__);}
}
if(isset($_POST['licence'])) {
    $licence = $_POST['licence'];
    if(strlen($licence)>7) { sendError('licence must be less than 8 characters', __LINE__);}
}

require_once(__DIR__.'/../protected/database.php');

try {
    $query = $db->prepare('INSERT INTO vehicle (Vehicle_make, Vehicle_model, Vehicle_colour, Vehicle_licence)
    VALUES (:make, :model, :colour, :licence)');
    $query->bindValue('make', $make); 
    $query->bindValue('model', $model); 
    $query->bindValue('colour', $colour);
    $query->bindValue('licence', $licence);
    $query->execute();
    $vehicleId = $db->lastInsertId();

    echo '{"status":1, "message":"vehicle created", "id":"'.$vehicleId.'","data":"'.$vehicleId.'"}';
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
