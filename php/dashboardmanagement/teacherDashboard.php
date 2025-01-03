<?php // teacherDashboard.php
require_once "../helperFiles/allFileImport.php";

// check the login status
loginStatus($_SESSION["userName"], "Sorry! You must be logged in to continue.");
$userName = cleanInput(ucfirst($_SESSION["userName"]));

// sql query statements
$sqlStatements = [
    "news" => ["SELECT * FROM news ORDER BY created_date LIMIT 3", []],
    "events" => ["SELECT * FROM events ORDER BY post_date LIMIT 3", []]
];
$query_results = [];

// retrieve query results for the entire tables used.
foreach ($sqlStatements as $key => $value) {
    $query_results[$key] = sqlFunctions($value[0], $value[1], null, "Unable to retrieve new {$key}'s data now. Please try again later.", $pdo)->fetchAll(PDO::FETCH_ASSOC);
}

// display the html
pageHeader("{$userName}'s Dashboard", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "dashboard.css");
echo "
<header>
    <h2>Hello Instructor {$userName}! Welcome to your dashboard page.</h2>
</header>

    <main>
        <section class=\"news\">
            <h3>Latest News</h3>
            ";

// retrieve the news result
render_sections($query_results["news"], "news", ["userName", "created_date", "title", "content"]);
            
            echo <<<html
            </section>
        
            <section class="events">
            <h3>Up Coming Events</h3>
            html;

            // fetch and display the upcoming events.
            render_sections($query_results["events"], "events", ["userName", "event_date", "title", "content"]);

        echo <<<html
        </section>

        <section class="assignments">
            <h3>Assignments</h3>
            <a href="assignments.php">Post new assignment</a><br>
            <a href="">View submitted Assignments</a>
        </section>

        <section class="results">
            <h3>Results</h3>
            <p>Record student results here.</p>
            <a href="../resultManagement/studentList.php">Manage students result</a>
        </section>

        <section class="resources">
            <h3>Resources</h3>
            <p>Upload new helpful resources for your students.</p>
            <a href="resources.php">Upload resources</a>
        </section>

        <section class="profile">
            <h3>Profile</h3>
            <p>Click the link below to view and edit your profile.</p>
            <a href="../accountManagement/profile.php">Profile</a>
        </section>

        <section class="chat">
            <h3>Chat Room</h3>
            <a href="post.php">Go to Chat room</a>
        </section>

        <h3>Logout</h3>
        <a href="../accountManagement//logout.php">Logout</a>
    </main>
html;
?>

</body>
</html>