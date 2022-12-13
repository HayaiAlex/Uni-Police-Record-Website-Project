<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if(!isset($_POST['amount'])) {sendError('amount missing', __LINE__);}
if(!isset($_POST['points'])) {sendError('points missing', __LINE__);}
if(!isset($_POST['incidentId'])) {sendError('incident id missing', __LINE__);}

$username = NULL;

if(isset($_POST['username'])) {
    $username = $_POST['username'];
}

require_once(__DIR__.'/../protected/database.php');
require_once(__DIR__.'/../audit/create-audit.php');

try {
    $query = $db->prepare('INSERT INTO fines (Fine_Amount, Fine_Points, Incident_ID)
    VALUES (:amount, :points, :incidentId)');
    $query->bindValue('amount', $_POST['amount']);
    $query->bindValue('points', $_POST['points']);
    $query->bindValue('incidentId', $_POST['incidentId']);
    $query->execute();
    $fineId = $db->lastInsertId();

    // Add audit log
    createFineLog($db, $username, "Added fine", $fineId, $_POST['incidentId'], $_POST['amount'], $_POST['points']);


    echo '{"status":1, "message":"fine created", "id":"'.$fineId.'"}';
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
