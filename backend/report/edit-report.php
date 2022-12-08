<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Validate incident id
if(!isset($_POST['incidentId'])) {sendError('incident id missing', __LINE__);}
if(!ctype_digit($_POST['incidentId'])) {sendError('incident id must be a number', __LINE__);}

// Validate date + report
if(!isset($_POST['date'])) {sendError('date missing', __LINE__);}
if(!isset($_POST['statement'])) {sendError('report statement missing', __LINE__);}

$incidentId = $_POST['incidentId'];
$date = $_POST['date'];
$statement = $_POST['statement'];
$vehicleId = NULL;
$personId = NULL;
$offenceId = NULL;

if(isset($_POST['vehicleId'])) {
    $vehicleId = $_POST['vehicleId'];
}
if(isset($_POST['personId'])) {
    $personId = $_POST['personId'];
}
if(isset($_POST['offenceId'])) {
    $offenceId = $_POST['offenceId'];
}

require_once(__DIR__.'/../protected/database.php');

try {
    $query = $db->prepare('UPDATE incident SET Vehicle_ID = :vehicleId, People_ID = :personId, Incident_Date = :date, Incident_Report = :statement, Offence_ID = :offenceId WHERE Incident_ID = :incidentId');
    $query->bindValue('incidentId', $incidentId);
    $query->bindValue('date', $date);
    $query->bindValue('statement', $statement);
    $query->bindValue('personId', $personId);
    $query->bindValue('vehicleId', $vehicleId);
    $query->bindValue('offenceId', $offenceId);
    $query->execute();

    if (!$query->rowCount()) {
        sendError('Report not found', __LINE__);
    }

    echo '{"status":1, "message":"report updated"}';
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

