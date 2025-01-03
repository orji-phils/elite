<?php // selectSubject.php
require_once "../helperFiles/allFileImport.php";

// check login status
loginStatus($_SESSION["userName"], "Please login to continue.");

// retrieve the subjects stored in the database
if (isset($_SESSION["term"], $_SESSION["session"])) {
    $queryValues = [$_SESSION["studentUserName"], $_SESSION["term"], $_SESSION["session"]];

    // fetch the stored subjects from the database                 
    $subjects = sqlFunctions("SELECT subject FROM result2 WHERE userName = ? AND term = ? AND session = ?", $queryValues,
    "", "Sorry! We experienced an issue trying to retrieve the stored subjects, please try again later.  Thank you.", $pdo);
} else {
    $error[] = "Please select the term and session result you want to update before you can proceed.";
    header("Location: update.php");
    exit();
}
// fill the subject array with the retrieved result
$subject = [];
while ($sub = $subjects->fetch()) {
    $subject[] = $sub["subject"];
}

$formFields = [
    "" => array_merge(["select", "subject", "Select a subject"], $subject)
];

// process form selection data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {
    $resultValues = [$_SESSION["studentUserName"], $_SESSION["term"], $_SESSION["session"], $_POST["subject"]];
    $_SESSION["subject"] = $_POST["subject"];

    // retrieve the stored result for the specified subject.
    $result = sqlFunctions("SELECT test, exam, total, average, position, remark FROM result2 WHERE userName = ? AND term = ? AND session = ? AND subject = ?",
    $resultValues, "", "Sorry! We encountered an error retrieving your result, please try again later.", $pdo);
    $_SESSION["result"] = $result->fetch(PDO::FETCH_ASSOC);
    header("Location: updateResult.php");
    exit();
}

// display html page
pageHeader("Update Result", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");

echo <<<html
<div>
    <p>Welcome instructor {$_SESSION["userName"]} </p>
        <h2>Select Subject</h2>
        <p>Kindly select the subject you wish to update for {$_SESSION["studentUserName"]} from the list of subjects in the drop-down menue below.</p>
    </div>
html;

if (!empty($subject)) {
    htmlForms("selectSubject.php", $formFields, $fields, "Continue");
} else {
    echo "<p>There's no result currently recorded for {$_SESSION["studentUserName"]}</p>";
}
?>
    
    </body>
    </html>