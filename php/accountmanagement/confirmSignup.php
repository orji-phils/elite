<?php // confirmSignup.php
require_once "../helperFiles/allFileImport.php";

// fetch the newly registered user data
$retrieveTemp = sqlFunctions("SELECT * FROM temp_signup", [], null, "Failed to retrieve new registeration data. Please try again.", $pdo)->fetchAll(PDO::FETCH_ASSOC);

// display the html page
pageHeader("Confirm Signup Request", null, null, "dashboard.css");
echo "<p>The newly registered users and their info are:</p>";

if ($retrieveTemp) {
    foreach ($retrieveTemp as $retrieve) {
        echo <<<html
        <h3>New User Information:</h3>
        <p>User Name: {$retrieve["userName"]}</p>
        <p>Email Address: {$retrieve["email"]}</p>
        <p>Roll: {$retrieve["type"]}</p>
        html;

        // initialise the formFields array variable
        $fields["userName"] = $retrieve["userName"];
        $formFields = [
            "Accept or Decline new user registeration" => ["radio", "response", "accept", "decline", "required"],
            "userName" => ["hidden", "userName"]
        ];

        // display the html form
        htmlForms("confirmSignup.php", $formFields, $fields, "Proceed");
        echo "<hr>";
    }
} else {
    echo "<p>No new user registeration data available. Do well to check back later.</p>";
}

// process the form
if (isset($_POST["userName"])) {
    $userName = cleanInput($_POST["userName"]);

    // retrieve the specific user data from temp_signup table
    $retrieveInfo = sqlFunctions("SELECT * FROM temp_signup WHERE userName = ?", [$userName], null, "Unable to fetch new user data. Please try again later.", $pdo)->fetch();

    if (!$retrieveInfo) {
        echo "<p>User data not found or has already been processed.</p>";
        exit();
    }

    if ($retrieveInfo && $_POST["response"] == "accept") {
        $transactionData = [
            "INSERT INTO signup (email, userName, type, password) VALUE(?, ?, ?, ?)" =>
            [[$retrieveInfo['email'], $retrieveInfo['userName'], $retrieveInfo['type'], $retrieveInfo["password"]],
            "User account verified successfully.", "Unable to verify user account at the moment. Please try again later."],
            "INSERT INTO profile (userName, email, type) VALUE(?, ?, ?)" =>
            [[$retrieveInfo["userName"], $retrieveInfo["email"], $retrieveInfo["type"]],
            null, null]
        ];

        // insert the records into their respective tables
        if (pdoTransaction($transactionData, "Sorry! An error occured and the account couldn't be verified. Please try again later.", $pdo)) {
            $email_subject = "🎉 Your Account Has Been Successfully Verified!";
            $email_message = file_get_contents("../emailManagement/accountAccepted.html");
            $successMessage = "A success message has been sent to {$retrieveInfo["email"]}.";
            $errorMessage = "Unable to send success message to {$retrieveInfo["email"]}.";
            
            // Send an email to user confirming their account verification.
            sendMail($retrieveInfo["email"], $email_subject, $email_message, $successMessage, $errorMessage);
        }
    } elseif ($_POST["response"] == "decline") {
        $email_subject = "Account Verification Declined";
        $email_message = file_get_contents("../emailManagement/accountDeclined.html");
        $successMessage = "An account declined message sent to {$retrieveInfo["email"]}.";
            $errorMessage = "Unable to send account declined message to {$retrieveInfo["email"]}.";
        
        // send the declined message to the user.
        sendMail($retrieveInfo["email"], $email_subject, $email_message, $successMessage, $errorMessage);
    }

    // delete user record from temp_signup table
    sqlFunctions("DELETE FROM temp_signup WHERE userName = ?", [$userName], null, null, $pdo);
    sqlFunctions("DELETE FROM notifications WHERE userName = ?", [$userName], null, null, $pdo);
}
?>

</body>
</html>