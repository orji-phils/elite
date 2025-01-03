<?php // record.php
// import the entire files needed.
require_once '../helperFiles/allFileImport.php';

// check the login status
loginStatus($_SESSION["userName"], "Please login to proceed with result recording.");

// setup the sessions
$start_session = strtotime("2020-11");
$end_session = strtotime("+1 year", $start_session);
$total_sessions = [];

while ($start_session < strtotime("this year")) {
    $total_sessions[] = "" . date("Y", $start_session) . "-" . date("Y", $end_session);
    $start_session = strtotime("+1 year", $start_session);
    $end_session = strtotime("+1 year", $end_session);
}

// initialise the formFields variable
$formFields = [
    "Term" => ["select", "term", "Select Term", "First Term", "Second Term", "Third Term", "required"],
    "Session" => array_merge(["select", "session", "Select Session"], $total_sessions, ["required"])
];

// process the submitted data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // update the session array
    $_SESSION["term"] = $_POST["term"];
    $_SESSION["session"] = $_POST["session"];

    // redirect teacher to the recordResult page
    header("Location: recordResult.php");
    exit();
}

// display the html page
pageHeader("Record Result", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p>Please enter the term and session for the result you want to record.</p>";

// display the form
htmlForms("record.php", $formFields, $fields, "Continue");
?>

</body>
</html>