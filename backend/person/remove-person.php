<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once(__DIR__.'/../protected/database.php');

// Check there is an id in the request and that it is valid
if (!isset($_GET['id'])) {sendError('id missing', __LINE__);}
if (!ctype_digit($_GET['id'])) {sendError('id is not a digit', __LINE__);}

try {
    $query = $db->prepare('DELETE FROM people WHERE People_ID = :id');
    $query->bindValue('id', $_GET['id']); // Placeholder protects against SQL injection
    $query->execute();
    // If no rows deleted then send error
    if(!$query->rowCount()) {sendError('user doens\'t exists',__LINE__);}

    echo '{"status":1, "message":"user deleted"}';
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