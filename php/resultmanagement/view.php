<?php // view.php
require_once "../helperFiles/allFileImport.php";

// check login status
loginStatus($_SESSION["userName"], "Please login to view results.");

// determin the uuser name for data retrieval
$userName = $_SESSION["type"] == "Teacher" ?
$_SESSION["studentUserName"] : $_SESSION["userName"];

// retrieve the term and session from the result2 table
$retrieveInfo = sqlFunctions("SELECT DISTINCT term, session FROM result2 WHERE userName = ?", [$userName],
"", "Unable to retrieve the term and session data to proceed, please try again later.", $pdo);

// retrieve the result obtained from the database
$termSession = [];
while ($retrieve = $retrieveInfo->fetch()) {
    $termSession[] = $retrieve["term"] . "_" . $retrieve["session"];
}

// instantiate the formFields array
$formFields = [
    "Term_Session" => array_merge(["radio", "term_session"], $termSession)
];

if (isset($_POST["term_session"])) {
    list($_SESSION["term"], $_SESSION["session"]) = explode("_", cleanInput($_POST["term_session"]));

    // move to select subject to update page
    header("Location: viewResult.php");
    exit();
}

// display the html page
pageHeader("View Result", $_SESSION["success"] ??"", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p>Select the term and session of the result you want to view.</p>";

// display the html form
if ($termSession) {
    htmlForms("view.php", $formFields, $fields, "Next");
} else {
    echo "<p>Sorry! No result has been recorded, please come back later when there's a recorded result. Thank you.</p>";
}
?>

</body>
</html>