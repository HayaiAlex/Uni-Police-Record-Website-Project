<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once(__DIR__.'/../protected/database.php');

// Check there is an id in the request and that it is valid
if (!isset($_GET['id'])) {sendError('id missing', __LINE__);}
if (!ctype_digit($_GET['id'])) {sendError('id is not a digit', __LINE__);}

try {
    $query = $db->prepare('SELECT Incident_Date, Incident_Report, People_name, People_address, People_licence, Vehicle_type, Vehicle_colour, Vehicle_licence, Fine_Amount, Fine_Points FROM incident JOIN people ON people.People_ID = incident.People_ID JOIN vehicle ON vehicle.Vehicle_ID = incident.Vehicle_ID JOIN fines ON fines.Incident_ID = incident.Incident_ID WHERE incident.Incident_ID = :id;
    ');
    $query->bindValue('id', $_GET['id']);
    $query->execute();
    $row = $query->fetch();
    if (!$row){sendError('user not found',__LINE__);}
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