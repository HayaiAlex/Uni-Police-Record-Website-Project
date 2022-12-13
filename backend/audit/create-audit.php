<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require(__DIR__.'/../protected/database.php');

function createLog($db, $username, $action, $searchTerm = NULL, $affectedCategory = NULL, $categoryId = NULL) {

    try {
        // Add audit log
        $query = $db->prepare('INSERT INTO audit (Audit_timestamp, Audit_username, Audit_action, Audit_search_term, Audit_People_History_ID, Audit_Vehicle_History_ID, Audit_Incident_History_ID, Audit_Fines_History_ID, Audit_Ownership_History_ID, Audit_Users_History_ID)
        VALUES (now(), :username, :action, :searchTerm, :peopleId, :vehicleId, :incidentId, :finesId, :ownershipId, :usersId)');
        
        $categories = ["people", "vehicle", "incident", "fines", "ownership", "users"];
        foreach ($categories as $category) {
            if ($category == $affectedCategory) {
                $query->bindValue($category.'Id', $categoryId);
            } else {
                $query->bindValue($category.'Id', NULL);
            }
        }
        $query->bindValue('searchTerm', $searchTerm);
        $query->bindValue('username', $username);
        $query->bindValue('action', $action);
        $query->execute();
        $auditID = $db->lastInsertId();
    } catch (PDOException $ex) {
        sendError('error executing query', __LINE__);
    }
}

function createUsersLog($db, $username, $action, $newUser, $newPass, $newAdmin, $oldUser = NULL, $oldPass = NULL, $oldAdmin = NULL) {
    try {
        $query = $db->prepare('INSERT INTO audit_users_history (Audit_Users_old_username, Audit_Users_old_password, Audit_Users_old_admin, Audit_Users_new_username, Audit_Users_new_password, Audit_Users_new_admin) VALUES (:oldUsername, :oldPassword, :oldAdmin, :newUsername, :newPassword, :newAdmin)');
        $query->bindValue('oldUsername', $oldUser);
        $query->bindValue('oldPassword', $oldPass);
        $query->bindValue('oldAdmin', $oldAdmin);
        $query->bindValue('newUsername', $newUser);
        $query->bindValue('newPassword', $newPass);
        $query->bindValue('newAdmin', $newAdmin);
        $query->execute();
        $historyID = $db->lastInsertId();
    } catch (PDOException $ex) {
        sendError('error executing query', __LINE__);
    }

    // Create basic log
    createLog($db, $username, $action, NULL, "users", $historyID);
}

function createVehicleLog($db, $username, $action, $vehicleId, $newMake, $newModel, $newColour, $newLicence, $oldMake = NULL, $oldModel = NULL, $oldColour = NULL, $oldLicence = NULL) {
    try {
        $query = $db->prepare('INSERT INTO audit_vehicle_history (Audit_Vehicle_vehicle_ID, Audit_Vehicle_old_make, Audit_Vehicle_old_model, Audit_Vehicle_old_colour, Audit_Vehicle_old_licence, Audit_Vehicle_new_make, Audit_Vehicle_new_model, Audit_Vehicle_new_colour, Audit_Vehicle_new_licence) VALUES (:id, :oldMake, :oldModel, :oldColour, :oldLicence, :newMake, :newModel, :newColour, :newLicence)');
        $query->bindValue('id', $vehicleId);
        $query->bindValue('oldMake', $oldMake);
        $query->bindValue('oldModel', $oldModel);
        $query->bindValue('oldColour', $oldColour);
        $query->bindValue('oldLicence', $oldLicence);
        $query->bindValue('newMake', $newMake);
        $query->bindValue('newModel', $newModel);
        $query->bindValue('newColour', $newColour);
        $query->bindValue('newLicence', $newLicence);
        $query->execute();
        $historyID = $db->lastInsertId();
    } catch (PDOException $ex) {
        sendError('error executing query', __LINE__);
    }

    // Create basic log
    createLog($db, $username, $action, NULL, "vehicle", $historyID);
}

function createOwnershipLog($db, $username, $action, $newPeopleId, $newVehicleId, $oldPeopleId = NULL, $oldVehicleId = NULL) {
    try {
        $query = $db->prepare('INSERT INTO audit_ownership_history (Audit_Ownership_old_people_ID, Audit_Ownership_old_vehicle_ID, Audit_Ownership_new_people_ID, Audit_Ownership_new_vehicle_ID) VALUES (:oldPeopleId, :oldVehicleId, :newPeopleId, :newVehicleId)');
        $query->bindValue('oldPeopleId', $oldPeopleId);
        $query->bindValue('oldVehicleId', $oldVehicleId);
        $query->bindValue('newPeopleId', $newPeopleId);
        $query->bindValue('newVehicleId', $newVehicleId);
        $query->execute();
        $historyID = $db->lastInsertId();
    } catch (PDOException $ex) {
        sendError('error executing query', __LINE__);
    }

    // Create basic log
    createLog($db, $username, $action, NULL, "ownership", $historyID);
}

function createReportLog($db, $username, $action, $incidentId, $newDate, $newReport, $newPeopleId, $newVehicleId, $newOffenceId, $oldDate = NULL, $oldReport = NULL, $oldPeopleId = NULL, $oldVehicleId = NULL, $oldOffenceId = NULL) {
    try {
        $query = $db->prepare('INSERT INTO audit_incident_history (Audit_Incident_incident_ID, Audit_Incident_old_vehicle_ID, Audit_Incident_old_people_ID, Audit_Incident_old_date, Audit_Incident_old_report, Audit_Incident_old_offence_ID, Audit_Incident_new_vehicle_ID, Audit_Incident_new_people_ID, Audit_Incident_new_date, Audit_Incident_new_report, Audit_Incident_new_offence_ID)
        VALUES (:incidentId, :oldVehicleId, :oldPeopleId, :oldDate, :oldReport, :oldOffenceId, :newVehicleId, :newPeopleId, :newDate, :newReport, :newOffenceId)');
        $query->bindValue('incidentId', $incidentId);
        $query->bindValue('oldVehicleId', $oldVehicleId);
        $query->bindValue('oldPeopleId', $oldPeopleId);
        $query->bindValue('oldDate', $oldDate);
        $query->bindValue('oldReport', $oldReport);
        $query->bindValue('oldOffenceId', $oldOffenceId);
        $query->bindValue('newVehicleId', $newVehicleId);
        $query->bindValue('newPeopleId', $newPeopleId);
        $query->bindValue('newDate', $newDate);
        $query->bindValue('newReport', $newReport);
        $query->bindValue('newOffenceId', $newOffenceId);
        $query->execute();
        $historyID = $db->lastInsertId();
    } catch (PDOException $ex) {
        sendError('error executing query', __LINE__);
    }

    // Create basic log
    createLog($db, $username, $action, NULL, "incident", $historyID);
}

function createFineLog($db, $username, $action, $fineID, $incidentID, $newAmount = NULL, $newPoints = NULL, $oldAmount = NULL, $oldPoints = NULL) {
    try {
        $query = $db->prepare('INSERT INTO audit_fines_history (Audit_Fines_fine_ID, Audit_Fines_incident_ID, Audit_Fines_old_amount, Audit_Fines_old_points, Audit_Fines_new_amount, Audit_Fines_new_points)
        VALUES (:fineId, :incidentId, :oldAmount, :oldPoints, :newAmount, :newPoints)');
        $query->bindValue('fineId', $fineID);
        $query->bindValue('incidentId', $incidentID);
        $query->bindValue('oldAmount', $oldAmount);
        $query->bindValue('oldPoints', $oldPoints);
        $query->bindValue('newAmount', $newAmount);
        $query->bindValue('newPoints', $newPoints);
        $query->execute();
        $historyID = $db->lastInsertId();
    } catch (PDOException $ex) {
        sendError('error executing query', __LINE__);
    }

    // Create basic log
    createLog($db, $username, $action, NULL, "fines", $historyID);
}

function createPersonLog($db, $username, $action, $peopleID, $newName = NULL, $newAddress = NULL, $newLicence = NULL, $oldName = NULL, $oldAddress = NULL, $oldLicence = NULL) {

    // Create history log
    try {
        $query = $db->prepare('INSERT INTO audit_people_history (Audit_People_people_ID, Audit_People_old_name, Audit_People_new_name, Audit_People_old_address, Audit_People_new_address, Audit_People_old_licence, Audit_People_new_licence)
        VALUES (:id, :old_name, :new_name, :old_address, :new_address, :old_licence, :new_licence)');
        $query->bindValue('id', $peopleID);
        $query->bindValue('old_name', $oldName);
        $query->bindValue('old_address', $oldAddress);
        $query->bindValue('old_licence', $oldLicence);
        $query->bindValue('new_name', $newName);
        $query->bindValue('new_address', $newAddress);
        $query->bindValue('new_licence', $newLicence);
        $query->execute();
        $historyID = $db->lastInsertId();
    } catch (PDOException $ex) {
        sendError('error executing query', __LINE__);
    }

    // Create basic log
    createLog($db, $username, $action, NULL, "people", $historyID);

}
