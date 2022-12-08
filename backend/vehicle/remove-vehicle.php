<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once(__DIR__.'/../protected/database.php');

// Check there is an id in the request and that it is valid
if (!isset($_GET['id'])) {sendError('id missing', __LINE__);}
if (!ctype_digit($_GET['id'])) {sendError('id is not a digit', __LINE__);}

try {
    // First remove vehicle from ownership table
    $query = $db->prepare('DELETE FROM ownership WHERE Vehicle_ID = :id');
    $query->bindValue('id', $_GET['id']); 
    $query->execute();

    // Delete vehicle
    $query = $db->prepare('DELETE FROM vehicle WHERE Vehicle_ID = :id');
    $query->bindValue('id', $_GET['id']); 
    $query->execute();
    // If no rows deleted then send error
    if(!$query->rowCount()) {sendError('vehicle doens\'t exists',__LINE__);}

    echo '{"status":1, "message":"vehicle deleted", "data":1}';
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