<?php // sqlConnection.php
require_once "configurationFile.php";

$opts = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

try {
    $pdo = new PDO($attr, $username, $password, $opts);
} catch (PDOException $e) {
    showLogError4("Database connection failed, please try again later.");
}
?>