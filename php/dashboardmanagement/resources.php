<?php // resources.php
require_once "../helperFiles/allFileImport.php";

// check login status
loginStatus($_SESSION["userName"], "Please login to upload resources.");

// initialize the formfields array
$formFields = [
    "Resource Title" => ["text", "title", "required"],
   "Resource description" => ["textarea", "description", "required"],
   "Resource Content" => ["file", "path", "required"]
];

// process the submitted fields
if (isset($_POST["title"])) {
    $isEmpty = FALSE;
    foreach ($_POST as $key => $value) {
        // sanitize the user's input.
        if ($key != "path") {
            $fields[$key] = cleanInput($value);
        }
        $fields["path"] = htmlspecialchars($_POST["path"]);

        // restrict empty fields
        if (!$value) {
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
        if (empty($fields["path"])) {
            $error[] = "Please attach a file to be uploaded.";
        } else {
            $transactionDate = [
                "INSERT INTO resources (title, description, path, class) VALUES(?, ?, ?, ?)" =>
                [[$fields["title"], $fields["description"], $fields["path"], $_SESSION["class"]],
                "Resource uploaded successfully.", "Error uploading resource. Please try again later."],
                "INSERT INTO notifications (title, content, status) VALUES(?, ?, ?)" =>
                [[$title, $content, "unread"], null, null]
            ];

            // transact the prepared data
            pdoTransaction($transactionDate, "Error uploading resource. Please try again later.", $pdo);
        }
    }
}

// display the html page
pageHeader("Upload Resources", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p>Please follow the prompt here to upload a resource.</p>";

// display the html form
htmlForms("resources.php", $formFields, $fields, "Post Resource");
?>

</body>
</html>