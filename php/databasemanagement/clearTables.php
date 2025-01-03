<?php // clearTables.php
// requires the connection file to connect to the server
require_once "queryFunctions.php";

// create an array that contains the table names
$tables = ["result2", "signup", "temp_signup", "profile", "posts", "assignments", "resources", "news", "events", "notifications", "temp_signup"];

foreach ($tables as $table) {
    $t = ucfirst($table);
    // empty the entire tables data
sqlFunctions("TRUNCATE TABLE {$table}", [], "{$t} table records emptied successfully.", "Unable to empty {$table} table records.",  $pdo);
}
?>