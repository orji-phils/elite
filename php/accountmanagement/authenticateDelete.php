<?php // AuthenticateDelete.php
// import the entire files needed.
require_once "../helperFiles/allFileImport.php";

// check the login status before proceeding
loginStatus($_SESSION["userName"], "Please login to proceed with account deletion.");
$userName = cleanInput(ucfirst($_SESSION["userName"]));

// initialize the formFields array
$formField = [
    "Enter your password" => ["password", "password", "required"]
];

// process user's input
if (isset($_POST["password"])) {
    foreach ($_POST as $key => $value) {
        $fields[$key] = cleanInput($value);
    }

    $hash = sqlFunctions("SELECT password FROM signup WHERE userName = ?",
    [$_SESSION["userName"]], null, "Unable to retrieve stored password to continue with this operation. Please try again later.", $pdo)->fetch();

    if (!empty($hash) && password_verify($fields["password"], $hash["password"])) {
        try {
            $pdo->beginTransaction();
            $tables = ["result2", "profile", "signup", "assignments"];

            foreach ($tables as $table) {
                if ($_SESSION["type"] != "student" && $table == "result2") {
                    continue;
                }

                sqlFunctions("DELETE FROM {$table} WHERE userName = ?", [$_SESSION["userName"]], "Account deleted successfully. Good bye.", "Unable to delete your account. Please try again later.", $pdo);
            }

            // post account deletion notification
            $title = "New Account Deletion";
            $content = "{$userName} from {$_SESSION["class"]} just deleted their account.";
            sqlFunctions("INSERT INTO notifications (title, content) VALUES(?, ?)", [$title, $content], "Deletion notification sent successfully.", "Unable to send deletion notification. Please try again later.", $pdo);
            $pdo->commit();

            // log user out and empty the session after successful account deletion.
            require_once "logout.php";
        } catch (PDOException $e) {
            $pdo->rollBack();
            showLogError("An error occured while deleting your account. Please try again later.", $e);
        }
    } else {
        $_SESSION["error"] = "You've entered a wrong password, please confirm the correct password and try again. Thank you.";
    }
}

// start the html output
pageHeader("Confirm Password", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p>Please enter your password to confirm you own this account</p>";

// output the form
htmlForms("authenticateDelete.php", $formField, $fields, "Delete Account");
?>

</body>
</html>