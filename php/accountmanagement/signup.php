<?php // signup.php
// import the entire files needed.
require_once "../helperFiles/allFileImport.php";

$formFields = array(
    "Email" => ["email", "email", "required"],
    "User name" => ["text", "userName", "required"],
    "Signup as" => ["select", "type", "Select your role", "admin", "student", "teacher", "staff", "required"],
    "Password" => ["password", "password", "required"],
    "Confirm password" => ["password", "confirmPassword", "required"]
);

// process posted data
if (isset($_POST["email"])) {
    $fields = [
        "email" => validate_email(cleanInput($_POST["email"])),
        "userName" => validate_userName(cleanInput($_POST["userName"])),
        "type" => validate_name("type", cleanInput($_POST["type"])),
        "password" => checkPassword($_POST["password"], $_POST["confirmPassword"])
    ];

    // search for empty fields
    $isEmpty = in_array("", $fields) ? TRUE : FALSE;

    // proceed if there's no empty field
    if (!$isEmpty) {
        // check if the user data exist in the signup table
        $retrieveData = sqlFunctions("SELECT userName FROM signup WHERE userName = ?",
        [$fields["userName"]], null, "Unable to proceed with signup as user data couldn't be validated.", $pdo)->fetch();

        // if user record already exists
        if (isset($retrieveData["userName"])) {
            $_SESSION["error"] = "Sorry! An account is already registered with this user name. Please
            click <a href='login.php'>here</a> to log into your account.";
        } else {
            // notification arguements
$title = "New Signup Alert";
$content = "A new user just registered as a {$fields["type"]}. Please click the link below to accept or decline this user's request.<br>
<a href=\"../accountManagement/confirmSignup.php\">View new registeration</a>";

            // data to be inserted into their respective database tables
            $transactionData = [
                "INSERT INTO temp_signup (email, userName, type, password) VALUE(?, ?, ?, ?)" =>
                [[$fields['email'], $fields['userName'], $fields['type'], $fields["password"]],
                "Dear " . $fields["userName"] . "! Your signup information was stored successfully.", "An error occured from the server and we couldn't  store your signup information. Please try again later"],
                "INSERT INTO notifications (title, content, userName, status) VALUES(?, ?, ?, ?)" =>
                [[$title, $content, $fields["userName"] ?? "", "unread"],
                " Registeration alert sent successfully.", "Unable to send registeration alert. Please try again later."]
            ];
            
            if (pdoTransaction($transactionData, "Sorry! The signup couldn't be completed at this time, please try again later.", $pdo)) {
                // redirect user to the temp signup table
                header("Location: tempSignup.php");
                exit();
            }
        }
    }
}

// starts the html output
pageHeader("Signup", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<h2>Welcome to Elite Path International</h2>
    <p>Welcome our distinct parent/guardian! Please fill the form below to register your child in Elite Path International School.</p>";

// display the signup form
htmlForms("signup.php", $formFields, $fields, "Signup");
?>

<p>Already have an account? <a href="login.php">Login</a> instead.</p>

<script type="text/javascript" src="../../javaScript/signup.js"></script>

</body>
</html>