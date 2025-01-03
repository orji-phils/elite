<?php // deleteTables.php
require_once "queryFunctions.php";

// create an array that contains the table names
$tables = ["result2", "signup", "temp_signup", "profile", "posts", "assignments", "resources", "news", "events", "notifications", "temp_signup"];

foreach ($tables as $table) {
    $t = ucfirst($table);

    // delete the entire tables
sqlFunctions("DROP TABLE {$table}", [], "{$t} table deleted successfully.", "Unable to delete {$table} table. Please try again",  $pdo);
}
?>