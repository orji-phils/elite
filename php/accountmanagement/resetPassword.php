<?php //resetPassword.php
// import the entire files needed.
require_once '../helperFiles/allFileImport.php';

$formFields = [
    "New password" => ["password", "password", "required"],
    "Confirm new password" => ["password", "confirmPassword", "required"]
];

// check login status
loginStatus($_SESSION["userName"], "Please login to proceed with password reset.");

// process the posted data
if (isset($_POST["password"])) {
    // use the check password method to validate and hash the password entered.
    $fields["password"] = checkPassword($_POST["password"], $_POST["confirmPassword"]);

    if (!empty($fields["password"])) {
        // update the password upon successful validation and hashing.
        sqlFunctions("UPDATE signup SET password = ? Where username = ?", [$fields["password"], $_SESSION["userName"]],
        "Password changed successfully. Thank you.", "Sorry! Unable to change your password, please try again later. Thank you.", $pdo);

        require_once "logout.php";
        $_SESSION["success"] = "Please login with your new password.";
        header("Location: login.php");
        exit();
    }
}

// display the html header
pageHeader("Reset Password", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p>Please fill up the form below to reset your password</p>";

// display the form content
htmlForms("resetPassword.php", $formFields, $fields, "Change Password");
?>

<script type="text/javascript" src="../../javaScript/resetPassword.js"></script>

</body>
</html>