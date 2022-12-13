<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if(!isset($_POST['amount'])) {sendError('amount missing', __LINE__);}
if(!isset($_POST['points'])) {sendError('points missing', __LINE__);}
if(!isset($_POST['fineId'])) {sendError('fine id missing', __LINE__);}

$username = NULL;

if(isset($_POST['username'])) {
    $username = $_POST['username'];
}

require_once(__DIR__.'/../protected/database.php');
require_once(__DIR__.'/../audit/create-audit.php');

try {
    // Save the old fine data
    $selectquery = $db->prepare('SELECT * FROM fines WHERE Fine_ID = :id');
    $selectquery->bindValue('id', $_POST['fineId']);
    $selectquery->execute();
    $old = $selectquery->fetch();

    // Edit the fine
    $query = $db->prepare('UPDATE fines SET Fine_Amount = :amount, Fine_Points = :points WHERE Fine_ID = :fineId');
    $query->bindValue('amount', $_POST['amount']);
    $query->bindValue('points', $_POST['points']);
    $query->bindValue('fineId', $_POST['fineId']);
    $query->execute();

    // Add audit log
    require_once(__DIR__.'/../audit/create-audit.php');
    createFineLog($db, $username, "Editted fine", $_POST['fineId'], $old['Incident_ID'], $_POST['amount'], $_POST['points'], $old['Fine_Amount'], $old['Fine_Points']);

    echo '{"status":1, "message":"fine editted", "id":"'.$_POST['fineId'].'"}';
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
