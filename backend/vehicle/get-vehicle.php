<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once(__DIR__.'/../protected/database.php');

// Check there is an id in the request and that it is valid
if (!isset($_GET['plateNumber'])) {sendError('plate number is missing', __LINE__);}

try {
    $query = $db->prepare('SELECT * FROM `vehicle`
    JOIN ownership ON ownership.Vehicle_ID = vehicle.Vehicle_ID
    JOIN people ON people.People_ID = ownership.People_ID
    WHERE Vehicle_licence = :plateNumber');
    $query->bindValue('plateNumber', $_GET['plateNumber']); // Placeholder protects against SQL injection
    $query->execute();
    $row = $query->fetch();
    if (!$row){sendError('vehicle not found',__LINE__);}
    echo '{"status":1, "data":'.json_encode($row).'}';
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