<?php // notifications.php
require_once "../helperFiles/allFileImport.php";

// check the login status
loginStatus($_SESSION["userName"], "Please login to proceed.");
$userName = $_SESSION["userName"];

// table names and respective url
$table_names = [
    "news" => "{$_SESSION["dashboard"]}",
    "events" => "{$_SESSION["dashboard"]}",
    "assignments" => "viewAssignments.php",
    "resources" => "viewResources.php",
    "result2" => "../resultManagement/viewResult.php",
    "posts" => "post.php"
];
$category_count = [];
$notification_count = 0;

// query and retrieve results
foreach ($table_names as $key => $value) {
    $count = sqlFunctions("SELECT count(*) FROM notifications WHERE status = 'unread'", [], null, "Unable to retrieve the count of {$key} records. Please try again later.", $pdo);
    $count = $count ? (int)$count->fetch() : 0;
    $category_count[$key] = [$count, $value];
    $notification_count += $count;
}

// display the html page
pageHeader("Notifications", null, null, null );
echo "<h2>Hello {$userName}! You have {$notification_count} notifications</h2>";

// display the unique notification
if ($notification_count > 0) {
    foreach ($category_count as $key => $value) {
        if ($value[0] > 0) {
            echo <<<html
            <div id="notification_item">
            <h3>You have {$value[0]} unread {$key}.</h3>
            <a href="{$value[1]}">Click to view</a>
            </div>
            html;
        }
    }
} else {
    echo "<p>You've got no notification at the moment.</p>";
}

// update the notifications table
sqlFunctions("UPDATE notifications SET status = 'read' WHERE status = 'unread' AND userName = ?", [$userName], null, null, $pdo);
?>

</body>
</html>