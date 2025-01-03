<?php // recordResult.php
// import the entire files needed.
require_once "../helperFiles/allFileImport.php";

// instantiate the formField array variable
$formFields = [
    "Test" => ["number", "test", "Enter the student's test score", "required"],
    "Exam" => ["number", "exam", "Enter the student's exam score", "required"],
    "Average" => ["text", "average", "Enter the student's average score", "required"],
    "Total" => ["number", "total", "Enter the student's total score", "required"],
    "Position" => ["number", "position", "Enter the student's position score", "required"],
    "Teacher's remark" => ["textarea", "remark", "Enter teacher's remark here, not more than 100 characters", "required"]
];

// check login status
loginStatus($_SESSION["userName"], "Please login to record this result.");
$userName = cleanInput(ucfirst($_SESSION["userName"]));

// set the subjects in the session array
if (!isset($_SESSION["subject"])) {
    $_SESSION["subject"] = $subjects;
    $_SESSION["current_subject"] = 0;
}

// ensure the term and session values are properly stored in the session
if (isset($_SESSION["term"], $_SESSION["session"])) {
    $values = [$_SESSION["studentUserName"], $_SESSION["term"], $_SESSION["session"], $_SESSION["subject"][$_SESSION["current_subject"]]];

    // query the subjects stored in result2 table
    $result = sqlFunctions("SELECT subject FROM result2 WHERE userName = ? AND term = ? AND session = ? AND subject = ?", $values,
    "", "Error fetchin resultts, please come back later.", $pdo);
    $result = $result->fetch();
} else {
    // user is logged in but, can't find term and session values, So we send them back to retrieve necessary data
    header("Location: record.php");
    exit();
}

// navigation control code
if (isset($_POST["previousSubject"]) && $_SESSION["current_subject"] > 0) {
    $resultInfo = [];
            --$_SESSION["current_subject"];
} elseif (isset($_POST["nextSubject"]) && $_SESSION["current_subject"] < count($subjects)) {
    $resultInfo = [];
            ++$_SESSION["current_subject"];
} elseif (isset($_POST["response"])) {
    $_SESSION["current_subject"] = 0;
    header("Location: record.php");
    exit();
}

// process posted data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) {
    $isEmpty = FALSE;
    foreach ($_POST as $key => $value) {
        if ($key != "remark") {
            $resultInfo[$key] = validate_score(cleanInput($value), $key, get_scores($key)); // sanitize, validate and store numeric data
        } else {
            $resultInfo[$key] = cleanInput($value); // sanitize and store non-numeric data
        }

        // restrict empty records
        if (empty($value)) {
            $error[] = ($key === "remark") ? "Teacher's " . $key . " is required." : "Student's " . $key . " is required.";
            $isEmpty = TRUE;
        }
    }

    // if no field is empty
    if (!$isEmpty) {
        // if the result haven't been recorded
        if (!$result) {
            $resultValues = [$_SESSION["studentUserName"], $_SESSION["subject"][$_SESSION["current_subject"]], $resultInfo["test"], $resultInfo["exam"],
            $resultInfo["total"], $resultInfo["average"], $resultInfo["position"], $resultInfo["remark"], $_SESSION["term"], $_SESSION["session"]];
            $title = "New Result Posted";
                $content = "The {$_SESSION["session"]} {$_SESSION["term"]} have been released.";

            // prepare data to be transacted
            $transactionData = [
                "INSERT INTO result2 (userName, subject, test, exam, total, average, position, remark, term, session) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" =>
                [$resultValues, "{$userName}'s {$_SESSION["subject"][$_SESSION["current_subject"]]} result has been recorded successfully.",
                "An error occured and we couldn't record {$userName}'s {$_SESSION["subject"][$_SESSION["current_subject"]]} result. Please try again later."],
                "INSERT INTO notifications (title, content, status) VALUES(?, ?, ?)" =>
                [[$title, $content, "unread"], null, null]
            ];

            if (pdoTransaction($transactionData, "Error submitting result. Please try again later.", $pdo)) {
                // empty user's input and navigate to next subject
                $resultInfo = [];
                $_SESSION["current_subject"]++;
            }
        } else {
            $error[] = ucfirst($_SESSION["subject"][$_SESSION["current_subject"]]) . "result has been recorded already. However, you
            can click <a href='update.php'>here</a> if you'll like to update it.";
            // header("Location: recordResult.php?success=1");
            $resultInfo = [];
            $_SESSION["current_subject"]++;
        }
    }
}

// display html page
pageHeader("Record Result", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p><strong>{$_SESSION["term"]} {$_SESSION["session"]}</strong></p>";

// If there are subjects in the database, display a heading and a form to fill the result.
if ($_SESSION["current_subject"] < count($_SESSION["subject"])) {
    echo "<h2>Record {$_SESSION["subject"][$_SESSION["current_subject"]]} result for {$_SESSION["studentUserName"]}</h2>";
    htmlForms("recordResult.php", $formFields, $fields, "Record Result");
} else {
    $error[] = "All subjects has been recorded successfully. Click the button below if you'll like to record another result for this user.";

    // a new form for recording another result for the same student if applicable.
    echo <<<html
    <form action="recordResult.php" method="post">
        <input type="submit" name="response" value="Record Another Result">
    </form>
    html;
}

// form for forward and backward navigations
echo <<<html
<form action="recordResult.php" method="post">
    <input type="submit" name="previousSubject" value="Previous Subject">
    <input type="submit" name="nextSubject" value="Next Subject">
</form>
html;
?>

<script type="text/javascript" src="../../javaScript/calculateScores.js"></script>
</body>
</html>