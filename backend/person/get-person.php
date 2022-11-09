<?php

header('Content-Type: application/json');

require_once(dirname(__DIR__,1).'/protected/database.php');

try {
    $query = $db->prepare('SELECT * FROM people WHERE People_ID = 3');
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