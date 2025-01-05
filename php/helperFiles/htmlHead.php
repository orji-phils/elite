<?php // htmlHead.php
function pageHeader($title, $successMessage, $errorMessage, $cssFile) {
    $pages = [
        '<a href="../dashboardManagement/news.php">News</a>',
        '<a href="../dashboardManagement/events.php">Event</a>',
        '<a href="../dashboardManagement/notifications.php">Notifications</a>',
        '<a href="../dashboardManagement/assignments.php">Assignments</a>',
        '<a href="../resultManagement/studentList.php">Result</a>',
        '<a href="../dashboardManagement/resources.php">Resources</a>',
        '<a href="../accountManagement/profile.php">Profile</a>',
        '<a href="../dashboardManagement/post.php">Chat Room</a>',
        '<a href="../accountManagement/logout.php">Logout</a>'
    ];

    // student pages
    $studentPages = [
        '<a href="../dashboardManagement/notifications.php">Notifications</a>',
        '<a href="../dashboardManagement/viewAssignments.php">Assignments</a>',
        '<a href="../resultManagement/viewResult.php">Result</a>',
        '<a href="../dashboardManagement/viewResources.php">Resources</a>',
        '<a href="../accountManagement/profile.php">Profile</a>',
        '<a href="../dashboardManagement/post.php">Chat Room</a>',
        '<a href="../accountManagement/logout.php">Logout</a>'
    ];

    echo <<<html
            <!DOCTYPE html>
            <html lang="en">
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title> {$title} </title>
            <link rel="stylesheet" href="../../css/headerStyle.css">
            <link rel="stylesheet" href="../../css/{$cssFile}">
            </head>
            <body>
            <header>
            <nav>
            <ul>
            html;

            // display the navigation links
            if (isset($pages)) {
                if (isset($_SESSION["type"]) && $_SESSION["type"] == "admin") {
                    foreach ($pages as $post) {
                        echo "<li>{$post}</li>";
                    }
                } elseif (isset($_SESSION["type"]) && $_SESSION["type"] == "teacher") {
                    for ($i=2; $i < count($pages); $i++) { 
                        echo "<li>{$pages[$i]}</li>";
                    }
                } elseif (isset($_SESSION["type"]) && $_SESSION["type"] == "student") {
                    foreach ($studentPages as $page) {
                        echo "<li>{$page}</li>";
                    }
                }
            }

            echo '</ul>
        </nav>

        <h1>' . $title . '</h1>';

        if (isset($successMessage)) {
            $_SESSION["success"] = $successMessage;
            echo '<div id="success">' . $_SESSION["success"] . '</div>';
            unset($_SESSION["success"]);
        }

        if (isset($errorMessage)) {
            $_SESSION["error"] = $errorMessage;
            echo '<div id="error">' . $_SESSION["error"] . '</div>';
            unset($_SESSION["error"]);
        }

        echo '</header>';
}
?>