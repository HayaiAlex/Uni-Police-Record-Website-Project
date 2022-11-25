<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if(!isset($_POST['date'])) {sendError('date missing', __LINE__);}
if(!isset($_POST['statement'])) {sendError('statement missing', __LINE__);}
if(strlen($_POST['statement'])>500) { sendError('address must be no more than 50- characters', __LINE__);}


$date = $_POST['date'];
$statement = $_POST['statement'];
$people_id = NULL;
$vehicle_id = NULL;
$offence_id = NULL;

if(isset($_POST['people-id'])) {
    $people_id = $_POST['people-id'];
}
if(isset($_POST['vehicle-id'])) {
    $vehicle_id = $_POST['vehicle-id'];
}
if(isset($_POST['offence-id'])) {
    $offence_id = $_POST['offence-id'];
}

require_once(__DIR__.'/../protected/database.php');

try {
    $query = $db->prepare('INSERT INTO incident (Vehicle_ID, People_ID, Incident_Date, Incident_Report, Offence_ID)
    VALUES (:vehicle_id, :people_id, :date, :statement, :offence_id);');
    $query->bindValue('date', $date);
    $query->bindValue('statement', $statement);
    $query->bindValue('people_id', $people_id);
    $query->bindValue('vehicle_id', $vehicle_id);
    $query->bindValue('offence_id', $offence_id);
    $query->execute();
    $reportId = $db->lastInsertId();

    echo '{"status":1, "message":"report created", "id":"'.$reportId.'","data":"'.$reportId.'"}';
    exit();
} catch (PDOException $ex) {
    sendError('error executing query', __LINE__);
}






// Debug is line number error occured
function sendError($message = 'error', $debug = 0) {
    echo '{
        "status":0,
        "messsage":"'.$message.'",
        "debug":'.$debug.',
        "data":"'.$message.'"
    }';
    exit();
}
