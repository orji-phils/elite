<?php // view.php
require_once "../helperFiles/allFileImport.php";

// check login status
loginStatus($_SESSION["userName"], "Please login to view results.");

// determin the user name for data retrieval
$userName = $_SESSION["type"] == "student" ?
$_SESSION["userName"] : $_SESSION["studentUserName"];

// retrieve the term and session from the result2 table
$retrieveInfo = sqlFunctions("SELECT DISTINCT term, session FROM result2 WHERE userName = ?", [$userName],
"", "Unable to retrieve the term and session data to proceed, please try again later.", $pdo);

// retrieve the term and session values obtained from the server
$termSession = [];
while ($retrieve = $retrieveInfo->fetch()) {
    $termSession[] = $retrieve["term"] . "_" . $retrieve["session"];
}

// instantiate the formfields array
$formFields = [
    "Term _ Session" => array_merge(["radio", "term_session"], $termSession)
];

if (isset($_POST["term_session"])) {
    list($_SESSION["term"], $_SESSION["session"]) = explode("_", cleanInput($_POST["term_session"]));

    // move to select subject to view page
    header("Location: viewResult.php");
    exit();
}

// display the html page
pageHeader("view Result", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<div>Select the term and session of the result you want to view.</div>";

// checks if the user selects a term and session before displaying the form
if ($termSession) {
    htmlForms("view.php", $formFields, $fields, "Next");
} else {
    echo "<p>Sorry! No result has been recorded, please record a result before updating.</p>";
}
?>