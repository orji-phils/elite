<?php //events.php
require_once "../helperFiles/allFileImport.php";

// check login status
loginStatus($_SESSION["userName"], "Please login to view or post new events.");

// initialize the formFields
$formFields = [
    "Title" => ["text", "title", "required"],
    "Content" => ["textarea", "content", "required"],
    "Event Date" => ["date", "event_date", "required"]
];

// process the data entered
if (isset($_POST["title"])) {
    $isEmpty = FALSE;
    foreach ($_POST as $key => $value) {
        $fields[$key] = cleanInput($value);

        // restrict empty fields
        if (empty($value)) {
            echo "{$key} is required.";
            $isEmpty = TRUE;
            break;
        }
    }

    if (!$isEmpty) {
        // events notification parameter arguements
        $title = "Latest Event Update";
            $content = "New event taking place on the {$_SESSION["event_date"]}.";

            // Prepare transaction data
        $transactionData = [
            "INSERT INTO notifications (title, content, status) VALUES(?, ?, ?)" =>
            [[$title, $content, "unread"],
            null, null],
            "INSERT INTO events (title, content, event_date) VALUES(?, ?, ?)" =>
            [[$fields["title"], $fields["content"], $fields["event_date"]],
"Event posted successfully.", "Unable to post event now, please try again later."]
        ];

        // transact prepared data
        pdoTransaction($transactionData, "Error uploading events. Please try again later.", $pdo);
    }
}

// display the html page
pageHeader("Post Event", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p>Fill up the fields below with approprate data to post an event.</p>";

// display the form
htmlForms("events.php", $formFields, $fields, "Post Event");
?>

</body>
</html>