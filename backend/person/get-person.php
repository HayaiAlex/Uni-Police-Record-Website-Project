<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once(__DIR__.'/../protected/database.php');
require_once(__DIR__.'/../audit/create-audit.php');

// Check that either a name or licence number is provided
if (!isset($_GET['name']) and !isset($_GET['licence'])) {sendError('please give a name or licence', __LINE__);}

$username = NULL;
if (isset($_GET['username'])) {
    $username = $_GET['username'];
}

// If a licence number provided use that
if (isset($_GET['licence'])) {
    try {
        $query = $db->prepare('SELECT * FROM people WHERE People_licence LIKE "%":licence"%"');
        $query->bindValue('licence', $_GET['licence']);
        $query->execute();
        $row = $query->fetchAll();
        if (!$row){sendError('user not found',__LINE__);}

        // If user found. Get their vehicles
        foreach ($row as &$person) {
            $vehicles = getVehicles($db, $person["People_ID"]);
            $person["Vehicles"] = $vehicles;
        }

        // Add search audit log
        createLog($db, $username, "searched people by licence", $_GET['licence']);

        echo '{"status":1, "data":'.json_encode($row).'}';
        exit();
    } catch(PDOException $ex) {
        sendError('error executing query', __LINE__);
    }
} else {
    // Trying searching by name. May result in more than one record
    try {
        $query = $db->prepare('SELECT * FROM people WHERE People_name LIKE "%":name"%"');
        $query->bindValue('name', $_GET['name']);
        $query->execute();
        $rows = $query->fetchAll();
        if (!$rows){sendError('user not found',__LINE__);}

        // If user found. Get their vehicles
        foreach ($rows as &$person) {
            $vehicles = getVehicles($db, $person["People_ID"]);
            $person["Vehicles"] = $vehicles;
        }

        // Add search audit log
        createLog($db, $username, "searched people by name", $_GET['name']);

        echo '{"status":1, "data":'.json_encode($rows).'}';
        exit();
    } catch(PDOException $ex) {
        sendError('error executing query', __LINE__);
    }
}

function getVehicles($db, $id) {
    $query = $db->prepare('SELECT vehicle.Vehicle_ID, vehicle.Vehicle_make, vehicle.Vehicle_model, vehicle.Vehicle_colour, vehicle.Vehicle_licence FROM people JOIN ownership ON people.People_ID = ownership.People_ID JOIN vehicle ON vehicle.Vehicle_ID = ownership.Vehicle_ID WHERE people.People_ID = :personID;');
    $query->bindValue('personID', $id);
    $query->execute();
    $vehicleRows = $query->fetchAll();
    return $vehicleRows;
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