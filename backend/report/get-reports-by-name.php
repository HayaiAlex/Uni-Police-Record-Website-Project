<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Check there is an id in the request and that it is valid
if (!isset($_GET['name'])) {sendError('name missing', __LINE__);}

require_once(__DIR__.'/../protected/database.php');

try {
    $query = $db->prepare('SELECT incident.Incident_ID, Incident_Date, offence.Offence_ID, Offence_description, Offence_maxFine, Offence_maxPoints, Incident_Report, incident.People_ID, People_name, People_address, People_licence, incident.Vehicle_ID, Vehicle_make, Vehicle_model, Vehicle_colour, Vehicle_licence, Fine_ID, Fine_Amount, Fine_Points FROM incident LEFT JOIN people ON people.People_ID = incident.People_ID LEFT JOIN vehicle ON vehicle.Vehicle_ID = incident.Vehicle_ID LEFT JOIN fines ON fines.Incident_ID = incident.Incident_ID LEFT JOIN offence ON offence.Offence_ID = incident.Offence_ID WHERE People_name LIKE "%":name"%";');
    $query->bindValue('name', $_GET['name']);
    $query->execute();
    $rows = $query->fetchAll();
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