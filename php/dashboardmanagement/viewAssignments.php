<?php // viewAssignments.php
require_once "../helperFiles/allFileImport.php";

// check the login status
loginStatus($_SESSION["userName"], "Please login to view and download assignments.");

// retrieve the assignments posted.
$retrieveAssignments = sqlFunctions("SELECT * FROM assignments WHERE class = ? ORDER BY create_date DESC LIMIT 5", [$_SESSION["class"]], null, "Unable to retrieve the assignments. Please try again.", $pdo);

// display the html page
pageHeader("View assignments", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", null);
echo "<p>Below are the assignments uploaded for your class. Click on the download link to save the chosen file to your computer.</p>";

// display the file and it's info if available.
if ($retrieveAssignments) {
    echo "<h2>The available assignments Are:</h2>";
    while ($assignment = $retrieveAssignments->fetch()) {
        // sanitize the data retrieved.
        foreach ($assignment as $key => $value) {
            if ($key != "path") {
                $assignment[$key] = cleanInput($value);
            }

            $assignment[$key] = htmlspecialchars($value);
        }

        // Format dates
        $postedDate = date("F j, Y, g:i a", strtotime($assignment["create_date"]));
        $deadline = date("F j, Y, g:i a", strtotime($assignment["end_date"]));

        echo "
        <h3>Subject: {$assignment["subject"]} Assignment</h3>
        <p>Title: {$assignment["title"]}</p>
        <p>Posted on {$postedDate}</p>
        <p>To be submitted on {$deadline}</p>
        <p>{$assignment["assignment"]}</p>
        <a href=\"{$assignment["path"]}\" download>download {$assignment["title"]}</a>
        ";
    }
} else {
    echo "<p>No assignment available for now. Please check back later.</p>";
}

?>

</body>
</html>