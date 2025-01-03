<?php
require_once '../helperFiles/sqlConnection.php';

// check if the session is set
if(isset($_SESSION["userName"])) {
    // empty and destroy the session
$_SESSION = [];
session_destroy();

// regenerate session id
session_start();
session_regenerate_id(TRUE);
} else {
    $_SESSION["success"] = "you are already logged out.";
}

// refers the user to the login page
header("Location: login.php");
exit();
?>