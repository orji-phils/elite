<?php // assignments.php
require_once "../helperFiles/allFileImport.php";

// check login status
loginStatus($_SESSION["userName"], "Please login to upload assignments.");

// initialize the formfields array
$formFields = [
    "Subject:" => ["text", "subject", "required"],
    "Title" => ["text", "title", "required"],
    "Deadline:" => ["date", "end_date", "required"],
    "Type assignment (Optional)" => ["textarea", "assignment"],
    "Upload Assignment (Optional)" => ["file", "path"]
];

// process the submitted fields
if (isset($_POST["subject"])) {
    $isEmpty = FALSE;
    foreach ($_POST as $key => $value) {
        // sanitize the user's input.
        if ($key != "path") {
            $fields[$key] = cleanInput($value);
        }
        $fields["path"] = htmlspecialchars($value);

        // restrict empty fields
        if (!$value && $key != "path" && $key != "assignment") {
            $error[] = "{$key} is required.";
            $isEmpty = TRUE;
            break;
        }
    }

    // try uploading the file and retrieving it's path
    $allowedType = ["png", "jpg", "gif", "jpeg", "pdf", "txt", "docx", "rtf", "epub"];
    $fields["path"] = $_FILES["path"] ?
    fileUpload("path", $allowedType, 10, "File uploaded successfully.") : "";

    if (!$isEmpty) {
        // assignment notification parameter arguements
        $subject = ucfirst($fields["subject"]);
        $title = "Latest Assignment posted.";
        $content = "{$subject} assignment posted on the {$_SESSION["post_date"]}.";
        // prepare the transaction data
        $transactionData = [
            "INSERT INTO notifications (title, content, status) VALUES(?, ?, ?)" =>
            [[$title, $content, "unread"],
            null, null],
            "INSERT INTO assignments (subject, end_date, assignment, path, class) VALUES(?, ?, ?, ?, ?)" =>
            [[$fields["subject"], $fields["end_date"], $fields["assignment"], $fields["path"], $_SESSION["class"]],
            "File uploaded successfully.", "Error uploading file. Please try again later."]
        ];

        // transact the prepared data
        pdoTransaction($transactionData, "Error posting assignment. Please try again later.", $pdo);
    }
}

// display the html page
pageHeader("Upload assignments", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p>Please follow the prompt here to upload an assignment for the students.</p>";

// display the html form
htmlForms("assignments.php", $formFields, $fields, "Post assignment");
?>

</body>
</html>