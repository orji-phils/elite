<?php // studentList.php
require_once "../helperFiles/allFileImport.php";
/* * allows the teacher select a particular student to record, or update their result. */

// check login status
loginStatus($_SESSION["userName"], "Please login to proceed.");

// retrieve the current user's class
$userClass = sqlFunctions("SELECT class, type FROM profile WHERE userName = ?", [$_SESSION["userName"]], "", "Unable to retrieve your class info. Please try again later.", $pdo);
$userClass = $userClass->fetch();

// retrieve the student's class info
$studentInfo = $_SESSION["type"] === "admin" ?
sqlFunctions("SELECT userName FROM profile WHERE type = 'student'", [], null, "Unable to retrieve students class info. Please try again later.", $pdo) :
sqlFunctions("SELECT userName FROM profile WHERE class = ? AND type = 'student'", [$userClass["class"]], null, "Unable to retrieve students class info. Please try again later.", $pdo);;

// store the student information retrieved in a variable
$studentNames = [];
while ($info = $studentInfo ? $studentInfo->fetch() : "") {
    $studentNames[] = cleanInput($info["userName"]);
}

// instantiate the form field array
$formFields = [
    "Select a Student" => array_merge(["radio", "studentUserName"], $studentNames)
];

// process the selected data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["studentUserName"])) {
    $_SESSION["studentUserName"] = cleanInput($_POST["studentUserName"]);

    header("Location: studentInfo.php");
    exit();
}

// display the page
pageHeader("Student List", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo '<p>The list of students in ' . $userClass["class"] . ' are:</p>';

// display the form with radio buttons
if (empty($studentNames)) {
    echo $_SESSION["type"] == "teacher" ? "Sorry! They are no student registered in {$userClass["class"]} now. When new students are registered, you'll find them here." : "Sorry! They are no student registered in Elite Path International School now. When new students are registered, you'll find them here.";
} else {
    htmlForms("studentList.php", $formFields, $studentNames, "Next");
}
?>

</body>
</html>