<?php // databaseFunctions.php
require_once "../helperFiles/sqlConnection.php";
require_once "../helperFiles/configurationFile.php";

// function to manage sql queries
function sqlFunctions($query, $values, $successMessage, $errorMessage, $pdo) {
    try {
        $sqlCommand = $pdo->prepare($query);
        $sqlCommand->execute($values);
        $_SESSION["success"] = !empty($successMessage) ? $successMessage : "";
        unset($successMessage);
        return $sqlCommand;
    }catch (PDOException $e) {
        $_SESSION["error"] = showLogError($errorMessage, $e);
        unset($errorMessage);
    }
}

// function to log and show database errors
function showLogError($errorMessage, $e) {
    error_log("Database error: " . $e->getMessage() . "\n", 3, "../helperFiles/errorLog.txt");
    return $e->getMessage() . "<br>" . $errorMessage;
}
?>