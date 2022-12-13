<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once(__DIR__.'/../protected/database.php');
require_once(__DIR__.'/../audit/create-audit.php');

// Check there is an id in the request and that it is valid
if (!isset($_GET['plateNumber'])) {sendError('plate number is missing', __LINE__);}

$username = NULL;
if (isset($_GET['username'])) {
    $username = $_GET['username'];
}

try {
    $query = $db->prepare('SELECT vehicle.Vehicle_ID, vehicle.Vehicle_make, vehicle.Vehicle_model, vehicle.Vehicle_colour, vehicle.Vehicle_licence, people.People_ID, people.People_name, people.People_address, people.People_licence FROM `vehicle`
    LEFT JOIN ownership ON ownership.Vehicle_ID = vehicle.Vehicle_ID
    LEFT JOIN people ON people.People_ID = ownership.People_ID
    WHERE Vehicle_licence LIKE "%":plateNumber"%"');
    $query->bindValue('plateNumber', $_GET['plateNumber']); // Placeholder protects against SQL injection
    $query->execute();
    $rows = $query->fetchAll();
    if (!$rows){sendError('vehicle not found',__LINE__);}

    // Add search audit log
    createLog($db, $username, "searched vehicle by licence", $_GET['plateNumber']);

    echo '{"status":1, "data":'.json_encode($rows).'}';
    exit();
} catch(PDOException $ex) {
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