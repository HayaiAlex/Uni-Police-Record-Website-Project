<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if(!isset($_POST['name'])) {sendError('name missing', __LINE__);}
if(strlen($_POST['name'])<2) {sendError('name must be at least 2 characters', __LINE__);}
if(strlen($_POST['name'])>50) {sendError('name must be less than 50 characters', __LINE__);}

$name = $_POST['name'];
$address = NULL;
$licence = NULL;

if(isset($_POST['address'])) {
    $address = $_POST['address'];
    if(strlen($address)>50) { sendError('address must be less than 50 characters', __LINE__);}
}

if(isset($_POST['licence'])) {
    $licence = $_POST['licence'];
    if(strlen($licence)>16) { sendError('address must be less than 16 characters', __LINE__);}
}

require_once(__DIR__.'/../protected/database.php');

try {
    $query = $db->prepare('INSERT INTO people (People_name, People_address, People_licence)
    VALUES (:name, :address, :licence)');
    $query->bindValue('name', $name);
    $query->bindValue('address', $address);
    $query->bindValue('licence', $licence);
    $query->execute();
    $userId = $db->lastInsertId();

    echo '{"status":1, "message":"user created", "id":"'.$userId.'","data":"'.$userId.'"}';
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

// "People_ID": "2", NOT NULL AUTO_INCREMENT
// "People_name": "Jennifer Allen", NOT NULL
// "People_address": "46 Bramcote Drive, Nottingham",
// "People_licence": "ALLEN88K23KLR9B3"