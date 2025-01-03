<?php // viewResult.php
require_once "../helperFiles/allFileImport.php";

// check login status
loginStatus($_SESSION["userName"], "Please, login to view results.");

// determin the user name for data retrieval
$userName = $_SESSION["type"] == "Teacher" ?
$_SESSION["studentUserName"] : $_SESSION["userName"];

// fetch the result data if only the session necessary values are available
if (isset($_SESSION["term"], $_SESSION["session"])) {
    // query to retrieve some personal data from the profile table
    $retrievePersonalInfo = sqlFunctions("SELECT firstName, lastName, class FROM profile WHERE userName = ?", [$userName],
null, "Unable to retrieve the required personal information for the account holder.", $pdo);
$personalInfo = $retrievePersonalInfo ? $retrievePersonalInfo->fetch() : "";

// query to retrieve the result data from result2 table
$retrieveResult = sqlFunctions("SELECT * FROM result2 WHERE userName = ?", [$userName],
null, "Unable to retrieve the result data now, please try again later.", $pdo);
} else {
    $_SESSION["error"] = "Please fetch your term and session information to continue.";
    header("Location: view.php");
    exit();
}

// display the html header
pageHeader("View Result", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "results.css");
?>

    <center>     ELITE PATH INTERNATIONAL SCHOOL<br>
        DAY CARE, TODDLER, NURSERY & PRIMARY<br>
        END OF TERM REPORT SHEET
    </center>

    <pre><p>Name: <?php echo cleanInput($personalInfo["firstName"]) . " " . cleanInput($personalInfo["lastName"]) . "\t\tTerm: " . $_SESSION["term"] . "\t\tClass: " . cleanInput($personalInfo["class"]); ?></p><br></pre>

    <center>COGNITIVE ABILITY</center>
    <table>
        <tr>
            <th>Subject</th>
            <th>CA Test</th>
            <th>Exam</th>
            <th>Total Result</th>
            <th>Class Average</th>
            <th>Subject Position</th>
            <th>Teacher's Remark</th>
        </tr>

        <?php if (isset($retrieveResult)) {
            while ($result = $retrieveResult->fetch()) {
                echo "<tr>";
                echo "<td>" . cleanInput($result["subject"]) . "</td>";
                echo "<td>" . cleanInput($result["test"]) . "</td>";
                echo "<td>" . cleanInput($result["exam"]) . "</td>";
                echo "<td>" . cleanInput($result["total"]) . "</td>";
                echo "<td>" . cleanInput($result["average"]) . "</td>";
                echo "<td>" . cleanInput($result["position"]) . "</td>";
                echo "<td>" . cleanInput($result["remark"]) . "</td>";
                echo "</tr>";
            }
        } else {
            $error[] = "Sorry! no result recorded yet, please check back later.";
        } ?>

    </table>
</body>
</html>