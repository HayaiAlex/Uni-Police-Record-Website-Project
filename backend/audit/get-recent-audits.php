<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once(__DIR__.'/../protected/database.php');

// Default limit is 10
$limit = 10;
if (isset($_GET['limit'])) {
    $limit = $_GET['limit'];
}

try {
    // Get simple audits
    $query = $db->prepare('SELECT Audit_ID, Audit_timestamp, Audit_username, Audit_action, Audit_search_term FROM audit WHERE Audit_People_History_ID IS NULL AND
    Audit_Vehicle_History_ID IS NULL AND
    Audit_Incident_History_ID IS NULL AND
    Audit_Fines_History_ID IS NULL AND
    Audit_Ownership_History_ID IS NULL AND
    Audit_Users_History_ID IS NULL
    ORDER BY Audit_timestamp DESC LIMIT :limit');
    $query->bindValue('limit', $limit, PDO::PARAM_INT);
    $query->execute();
    $simpleRows = $query->fetchAll();

    // Get people history audits
    $query = $db->prepare('SELECT Audit_ID, Audit_timestamp, Audit_username, Audit_action, Audit_People_people_ID, Audit_People_old_name, Audit_People_old_address, Audit_People_old_licence, Audit_People_new_name, Audit_People_new_address, Audit_People_new_licence FROM audit 
    JOIN audit_people_history ON audit_people_history.Audit_People_History_ID = audit.Audit_People_History_ID
    ORDER BY Audit_timestamp DESC LIMIT :limit');
    $query->bindValue('limit', $limit, PDO::PARAM_INT);
    $query->execute();
    $peopleRows = $query->fetchAll();

    // Get vehicle history audits
    $query = $db->prepare('SELECT Audit_ID, Audit_timestamp, Audit_username, Audit_action, Audit_Vehicle_vehicle_ID, Audit_Vehicle_old_make, Audit_Vehicle_old_model, Audit_Vehicle_old_colour, Audit_Vehicle_old_licence, Audit_Vehicle_new_make, Audit_Vehicle_new_model, Audit_Vehicle_new_colour, Audit_Vehicle_new_licence FROM audit 
    JOIN audit_vehicle_history ON audit_vehicle_history.Audit_Vehicle_History_ID = audit.Audit_Vehicle_History_ID
    ORDER BY Audit_timestamp DESC LIMIT :limit');
    $query->bindValue('limit', $limit, PDO::PARAM_INT);
    $query->execute();
    $vehicleRows = $query->fetchAll();

    // Get report history audits
    $query = $db->prepare('SELECT Audit_ID, Audit_timestamp, Audit_username, Audit_action, Audit_Incident_incident_ID, Audit_Incident_old_vehicle_ID, Audit_Incident_old_people_ID, Audit_Incident_old_date, Audit_Incident_old_report, Audit_Incident_old_offence_ID, Audit_Incident_new_vehicle_ID, Audit_Incident_new_people_ID, Audit_Incident_new_date, Audit_Incident_new_report, Audit_Incident_new_offence_ID FROM audit 
    JOIN audit_incident_history ON audit_incident_history.Audit_Incident_History_ID = audit.Audit_Incident_History_ID
    ORDER BY Audit_timestamp DESC LIMIT :limit');
    $query->bindValue('limit', $limit, PDO::PARAM_INT);
    $query->execute();
    $reportRows = $query->fetchAll();
    
    // Get fines history audits
    $query = $db->prepare('SELECT Audit_ID, Audit_timestamp, Audit_username, Audit_action, Audit_Fines_fine_ID, Audit_Fines_old_amount, Audit_Fines_old_points, Audit_Fines_new_amount, Audit_Fines_new_points, Audit_Fines_incident_ID FROM audit 
    JOIN audit_fines_history ON audit_fines_history.Audit_Fines_History_ID = audit.Audit_Fines_History_ID
    ORDER BY Audit_timestamp DESC LIMIT :limit');
    $query->bindValue('limit', $limit, PDO::PARAM_INT);
    $query->execute();
    $fineRows = $query->fetchAll();

    // Get ownership history audits
    $query = $db->prepare('SELECT Audit_ID, Audit_timestamp, Audit_username, Audit_action, Audit_Ownership_old_people_ID, Audit_Ownership_old_vehicle_ID, Audit_Ownership_new_people_ID, Audit_Ownership_new_vehicle_ID FROM audit 
    JOIN audit_ownership_history ON audit_ownership_history.Audit_Ownership_History_ID = audit.Audit_Ownership_History_ID
    ORDER BY Audit_timestamp DESC LIMIT :limit');
    $query->bindValue('limit', $limit, PDO::PARAM_INT);
    $query->execute();
    $ownershipRows = $query->fetchAll();
    
    // Get users history audits
    $query = $db->prepare('SELECT Audit_ID, Audit_timestamp, Audit_username, Audit_action, Audit_Users_old_username, Audit_Users_old_password, Audit_Users_old_admin, Audit_Users_new_username, Audit_Users_new_password, Audit_Users_new_admin FROM audit 
    JOIN audit_users_history ON audit_users_history.Audit_Users_History_ID = audit.Audit_Users_History_ID
    ORDER BY Audit_timestamp DESC LIMIT :limit');
    $query->bindValue('limit', $limit, PDO::PARAM_INT);
    $query->execute();
    $userRows = $query->fetchAll();

    $allRows = array_merge($simpleRows, $peopleRows, $vehicleRows, $reportRows, $fineRows, $ownershipRows, $userRows);

    usort($allRows, function($a, $b) {
        $a_time = strtotime($a['Audit_timestamp']);
        $b_time = strtotime($b['Audit_timestamp']);
        
        if($a_time > $b_time) return -1;
        if($a_time < $b_time) return 1;
        return 0;
    });

    // Finally limit the final array to send to the frontend
    $allRows = array_slice($allRows, 0, $limit);

    echo '{"status":1, "data":'.json_encode($allRows).'}';
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