<?php // forgotPassword.php
// import the entire files needed.
require_once '../helperFiles/allFileImport.php';

$formFields = [
    "User name" => ["text", "userName", "required"],
    "Email" => ["email", "email", "required"]
];

// process posted data
if (isset($_POST["userName"])) {
    $fields["userName"] = cleanInput($_POST["userName"]);
    $fields["email"] = cleanInput($_POST["email"]);

    // retrieve the userName stored in the database
    $retrieveUserName = sqlFunctions("SELECT userName, email FROM signup WHERE userName = ? AND email = ?", [$fields["userName"], $fields["email"]],
    null, "Unable to retrieve credentials to proceed with this action, please try again later", $pdo)->fetch();

    // if retrieved email field is empty
    if (empty($retrieveUserName["email"])) {
        $_SESSION["error"] =  "Sorry, this email is not registered for the provided username. Please enter a correct email address associated with your account.";

        if (empty($retrieveUserName["userName"])) {
            $_SESSION["error"] = "User name not found. Please <a href='signup.php'>signup</a> to create an account.";
        }
    } else {
        // email information
        $email_subject = "Password Reset";
        $email_message = "<p>Hello {$retrieveUserName["userName"]},
        We received a request to recover your account on Elite Path International's website. If you initiated this request, follow the steps below to reset your password and regain access:
        <ul>
        <li>Click on the link below to reset your password:
        <a href=\"resetPassword.php\">Reset password</a> (If the link doesn’t work, copy and paste it into your browser.)</li>
        <LI>Choose a new password and confirm it.
        If you did not request this, you can safely ignore this email. Your account is secure, and no changes have been made.</LI>
        </ul></p>
        <h2>Need help?</h2>
        <p>If you encounter any issues, feel free to contact our support team at <a href=\"C:\xampp\htdocs\elite\index.php\">support@elitepath</a> or visit our Help Center.</p>
        <p>Thank you,</p>
        <p><Elite Path Team</p>;</p>";
        $success_message = "An email with reset instructions has been sent to {$fields["email"]}.";
        $error_message = "An error prevented us from sending the password reset email. Please try again later";

        // send an email to current user
        sendMail($retrieveUserName["email"], $email_subject, $email_message, $success_message, $error_message);

        $_SESSION["userName"] = $fields["userName"];
        $_SESSION["email"] = $fields["email"];
    }   
}

// display the html page
pageHeader("Forgot your password?", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p>Please enter your userName and email in the form below and press the enter key to submit</p>";

// create the form
htmlForms("forgotPassword.php", $formFields, $fields, "Submit");
?>

</body>
</html>