<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once(__DIR__.'/../protected/database.php');

try {
    // Get simple audits
    $query = $db->prepare('SELECT COUNT(*) AS num_audits FROM audit');
    $query->execute();
    $count = $query->fetch()['num_audits'];

    echo '{"status":1, "data":'.json_encode($count).'}';
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