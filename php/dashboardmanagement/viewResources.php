<?php // viewResources.php
require_once "../helperFiles/allFileImport.php";

// check the login status
loginStatus($_SESSION["userName"], "Please login to view and download resources.");

// retrieve the resources posted.
$retrieveResources = sqlFunctions("SELECT * FROM resources WHERE class = ? ORDER BY create_date DESC LIMIT 5", [$_SESSION["class"]], null, "Unable to retrieve the resources. Please try again.", $pdo);

// display the html page
pageHeader("View Resources", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", null);
echo "<p>Below are the resources uploaded for your class. Click on the download link to save the chosen file to your computer.</p>";

// display the file and it's info if available.
if ($retrieveResources) {
    echo "<h2>The available Resources Are:</h2>";
    while ($resource = $retrieveResources->fetch()) {
        // sanitize the data retrieved.
        foreach ($resource as $key => $value) {
            if ($key != "path") {
                $resource[$key] = cleanInput($value);
            }

            $resource[$key] = htmlspecialchars($value);
        }

        // format date
        $postedDate = date("F j, Y, g:i a", strtotime($resource["create_date"]));

        echo "
        <h3>Title: {$resource["title"]}</h3>
        <p>Resource posted on {$postedDate}</p>
        <p>{$resource["description"]}</p>
            <a href=\"{$resource["path"]}\" download>download {$resource["title"]}</a>
            ";
    }
} else {
    echo "<p>No resource available for now. Please check back later.</p>";
}

?>

</body>
</html>