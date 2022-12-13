<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once(__DIR__.'/../protected/database.php');
require_once(__DIR__.'/../audit/create-audit.php');

// Check there is an id in the request and that it is valid
if (!isset($_GET['id'])) {sendError('id missing', __LINE__);}
if (!ctype_digit($_GET['id'])) {sendError('id is not a digit', __LINE__);}

$username = NULL;
if (isset($_GET['username'])) {
    $username = $_GET['username'];
}

try {
    // Get vehicle ownership for auditting
    $selectquery = $db->prepare('SELECT * FROM ownership WHERE People_ID = :id');
    $selectquery->bindValue('id', $_GET['id']);
    $selectquery->execute();
    $old = $selectquery->fetch();
    if($query->rowCount()) {
        // If we deleted a ownership record then audit it
        createOwnershipLog($db, $username, "Removed ownership", NULL, NULL, $old['People_ID'], $old['Vehicle_ID']);
    }
    
    // First remove person from ownership table
    $query = $db->prepare('DELETE FROM ownership WHERE People_ID = :id');
    $query->bindValue('id', $_GET['id']); 
    $query->execute();

    // Get person data for auditting
    $selectquery = $db->prepare('SELECT * FROM people WHERE People_ID = :id');
    $selectquery->bindValue('id', $_GET['id']);
    $selectquery->execute();
    $old = $selectquery->fetch();

    // Delete person
    $query = $db->prepare('DELETE FROM people WHERE People_ID = :id');
    $query->bindValue('id', $_GET['id']); 
    $query->execute();
    // If no rows deleted then send error
    if(!$query->rowCount()) {sendError('person doens\'t exists',__LINE__);}

    // Create audit log
    createPersonLog($db, $username, "Removed person", $_GET['id'], NULL, NULL, NULL, $old['People_name'], $old['People_address'], $old['People_licence']);

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