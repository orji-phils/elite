<?php // examUpload.php
require_once "../helperFiles/allFileImport.php";

// check login status
loginStatus($_SESSION["userName"], "Please login to upload new exam questions.");

// instantiate the formFields variable
$formFields = [
    "Subject" => ["text", "subject", "required"],
    "Exam Date" => ["date", "exam_date", "required"],
    "Start Time" => ["time", "start_time", "required"],
    "End Time" => ["time", "end_time", "required"],
    "Upload Exam File (Microsoft Office Word/docx)" => ["file", "exam_file", "required"]
];

// process user input
if ($_POST["subject"]) {
    // sanitize and restrict empty values
    $isEmpty = FALSE;
    foreach ($_POST as $key => $value) {
        if (empty($value)) {
            $_SESSION["error"] = "{$key} is required to upload exam questions."; 
            $isEmpty = TRUE;
            break;
        }
        
        // sanitize the inputs
        if ($key !== "exam_file") {
            $fields[$key] = cleanInput($value);
        }

        $fields["exam_file"] = htmlspecialchars($value);
    }

    // try to upload information to the database
    if (!$isEmpty) {
        // exam notification parameter arguement
        $title = "New Exam Posted";
        $content = "The {$fields["subject"]} exam has been posted. The Exam will be taking place on the {$fields["exam_date"]} at {$fields["start_time"]} prompt. Do well to take your exam online.";

        
// prepare the data to be transacted
        $transactionData = [
            "INSERT INTO exam (subject, exam_date, start_time, end_time, exam_file) VALUE(?, ?, ?, ?, ?)" =>
            [[$fields["subject"], $fields["exam_date"], $fields["start_time"], $fields["end_time"], $fields["exam_file"]]
            "Exam uploaded successfully.", "Unable to upload exam. Please try again later."],
            "INSERT INTO notifications (title, content, status) VALUES(?, ?, ?)" =>
            [[$title, $content, "unread"]
            null, null]
        ];

        // transact the prepared data
        pdoTransaction($transactionData, "Error posting exam questions. Try again later.", $pdo)
    }
}

// display the html page
pageHeader("Upload Exam", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p>Please carefully follow the instructions here to upload your exam questions.</p>";

// display the html forms
htmlForms("examUpload.php", $formFields, $fields, "Upload Exam");
?>

</body>
</html>