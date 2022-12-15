<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once(__DIR__.'/../protected/database.php');

// Check that either a name or licence number is provided
if (!isset($_GET['id'])) {sendError('id missing', __LINE__);}

// Trying searching by name. May result in more than one record
try {
    $query = $db->prepare('SELECT * FROM people WHERE People_ID = :id');
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