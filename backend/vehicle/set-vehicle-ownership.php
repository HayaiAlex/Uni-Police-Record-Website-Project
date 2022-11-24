<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if(!isset($_POST['vehicle-id'])) {sendError('vehicle id missing', __LINE__);}
if (!ctype_digit($_POST['vehicle-id'])) {sendError('vehicle id is not a digit', __LINE__);}

if(!isset($_POST['person-id'])) {sendError('person id missing', __LINE__);}
if (!ctype_digit($_POST['person-id'])) {sendError('person id is not a digit', __LINE__);}

require_once(__DIR__.'/../protected/database.php');

try {
    $query = $db->prepare('INSERT INTO ownership (People_ID, Vehicle_ID)
    VALUES (:person, :vehicle)');
    $query->bindValue('person', $_POST['person-id']); 
    $query->bindValue('vehicle', $_POST['vehicle-id']); 
    $query->execute();

    echo '{"status":1, "message":"ownership created", "data":1}';
    exit();
} catch (PDOException $ex) {
    sendError('error executing query using '.$_POST['person-id'].' and '.$_POST['vehicle-id'], __LINE__);
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
