<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if(!isset($_POST['amount'])) {sendError('amount missing', __LINE__);}
if(!isset($_POST['points'])) {sendError('points missing', __LINE__);}
if(!isset($_POST['fineId'])) {sendError('fine id missing', __LINE__);}

require_once(__DIR__.'/../protected/database.php');

try {
    $query = $db->prepare('UPDATE fines SET Fine_Amount = :amount, Fine_Points = :points WHERE Fine_ID = :fineId');
    $query->bindValue('amount', $_POST['amount']);
    $query->bindValue('points', $_POST['points']);
    $query->bindValue('fineId', $_POST['fineId']);
    $query->execute();

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
