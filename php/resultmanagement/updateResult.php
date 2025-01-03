<?php // updateResult.php
require_once "../helperFiles/allFileImport.php";

// verify user login
loginStatus($_SESSION["userName"], "Please login to update this result.");
$userName = cleanInput(ucfirst($_SESSION["userName"]));

// create the form fields for display
$result = $_SESSION["result"];
$formFields = [
    "Test Score" => ["text", "test", "required"],
    "Exam Score" => ["text", "exam", "required"],
    "Average Score" => ["text", "average", "required"],
    "Total Score" => ["text", "total", "required"],
    "Position in Class" => ["text", "position", "required"],
    "Teacher's Remark" => ["textarea", "remark", "required"]
];

// retrieve the result values.
foreach ($_SESSION["result"] as $key => $value) {
    $fields[$key] = cleanInput($value);
}

// process the posted data for updating result
if ($_POST["test"] == "POST") {
    $isEmpty = FALSE;
    foreach ($_POST as $key => $value) {
        // sanitize the fields
        if ($key == "remark" || $key == "subject") {
            $fields[$key] = cleanInput($value);
        } else {
            $fields[$key] = validate_score(cleanInput($value), $key, get_scores($key));
        }

        // restrict empty values
        if (empty($value)) {
            $error[] = ($key == "remark") ? "Teacher's " . $key . " is required." : ucfirst($key) . " score is required.";
            $isEmpty = TRUE;
            break;
        }
    }

    // all fields are properly filled, so submit the updated result
    if (!$isEmpty) {
        $values =         [$fields["test"], $fields["exam"], $fields["total"], $fields["average"], $fields["position"],
        $fields["remark"], $_SESSION["studentUserName"], $_SESSION["term"], $_SESSION["session"], $_POST["subject"]];
        $title = "Result Update";
            $content = "The {$_SESSION["term"]} {$_SESSION["session"]} was updated at " . new Date("Y-m-d h:i:sa");

        // prepare the transaction data
        $transactionData = [
            "UPDATE result2 SET test = ?, exam = ?, total = ?, average = ?, position = ?, remark = ? WHERE userName = ? AND term = ? AND session = ? AND subject = ?" =>
            [$values, "{$userName}'s {$_POST["subject"]} result has been updated successfully. Thank you.", "Sorry! we couldn't update {$userName}'s {$_POST["subject"]} result now. Please try again later."],
            "INSERT INTO notifications (title, content, status) VALUES(?, ?, ?)" =>
            [[$title, $content, "unread"], null, null]
        ];

        if (pdoTransaction($transactionData, "Error Updating results. Please try again later.", $pdo)) {
            // move back to the select subject page
            header("Location: selectSubject.php");
            exit();
        }
    }
}

// display the html page
pageHeader("Update Result", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p>Welcome instructor {$_SESSION["userName"]} </p>
<p>{$_SESSION["term"]} {$_SESSION["session"]}</p>";

// display some information and the form for updating result if there's a result.
if (isset($result)) {
    echo <<<html
    <h2>Update {$_SESSION["subject"]} result for {$_SESSION["studentUserName"]}</h2>
    <p>Carefully fill up the fields with the approprate result grades and comment, then click or press enter on the button to submit.</p>
    html;

    htmlForms("updateResult.php", $formFields, $fields, "Update Result");
}

echo "<p>Nor result found for {$studentUserName}, thank you.</p>";
?>

<script type="text/javaScript" src="../../javaScript/calculateScores.js"></script>

</body>
</html>