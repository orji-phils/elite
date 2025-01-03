<?php // studentInfo.php
require_once "../helperFiles/allFileImport.php";

// check login status
loginStatus($_SESSION["userName"], "Please login to proceed.");
$student = cleanInput($_SESSION["studentUserName"]);

// fetch other user data from the profile table
$studentInfo = sqlFunctions("SELECT firstName, lastName, picture FROM profile WHERE userName = ?", [$student], "", "Unable to retrieve extra information for {$student} now, please try again later.", $pdo);
$studentInfo = $studentInfo->fetch();

// sanitize the entire retrieved data
foreach ($studentInfo as $key => $value) {
    $studentInfo[$key] = cleanInput($value);
}

// display the html page
pageHeader("{$student}'s Result", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");

if (!$studentInfo) {
    $error[] = "Student information wasn't retrieved, please try again later.";
} else {
    // display student's profile picture if present
    if ($studentInfo["picture"]) {
        echo <<<html
        <div>
            <p>Profile picture:</p>
            <img src="{$studentInfo["picture"]}" alt="{$student}'s profile picture">
        </div>
        html;
    } else {
        $error[] = "{$student} haven't uploaded a picture yet.";
    }
    
    // display other student's information.
    echo <<<html
    <section>
        <p>First name: {$studentInfo["firstName"]}</p>
        <p>Last name: {$studentInfo["lastName"]}</p>
        <p>User name: {$student}</p>
    </section>
    html;
}

// result management
echo <<<html
<p>Please select from the options below what you'll love to do with {$student}'s result.</p>
<ul>
    <li><a href="record.php">Record a result for {$student}</a></li>
    <li><a href="update.php">Update {$student}'s result</a></li>
    <li><a href="view.php">View {$student}'s result</a></li>
</ul>
html;
?>

</body>
</html>