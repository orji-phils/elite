<?php // login.php
// import the entire files needed.
require_once "../helperFiles/allFileImport.php";

// array to hold the form fields
$formFields = [
    "User name" => ["text", "userName", "required"],
    "Password" => ["password", "password", "required"]
];

// process user's input.
if (isset($_POST["userName"])) {
    foreach ($_POST as $key => $value) {
        $fields[$key] = cleanInput($value);
    }

    // retrieve user's password and type if stored
    $retrievePassword = sqlFunctions("SELECT userName, type, email, password, class FROM signup NATURAL JOIN profile WHERE userName = ?",
    [$fields["userName"]], null, "Unable to retrieve user credentials now, please try again later", $pdo);
    $retrievePassword = $retrievePassword ? $retrievePassword->fetch() : "";

    if (!empty($retrievePassword["type"])) {
        switch (strtolower($retrievePassword["type"])) {
            case 'admin':
                $dashboard = "adminDashboard.php";
                break;
            case 'teacher':
                $dashboard = "teacherDashboard.php";
                break;
            default:
                $dashboard = "studentDashboard.php";
                break;
        }
    }

    if (!isset($retrievePassword["userName"])) {
        $_SESSION["error"] = "User name not found. Please <a href='signup.php'>signup</a>.";
    } else {
        if (!password_verify($fields["password"], $retrievePassword["password"])) {
            $_SESSION["error"] = "Incorrect password. Please try again.";
        } else {
            $_SESSION["success"] = "Welcome " . cleanInput($fields["userName"]) . "! You are logged in.";
            session_regenerate_id(TRUE);
            $_SESSION["userName"] = $retrievePassword["userName"];
            $_SESSION["type"] = $retrievePassword["type"];
            $_SESSION["class"] = $retrievePassword["class"];
            $_SESSION["dashboard"] = $dashboard;

            // redirect user to their unique dashboard page.
            header("Location: ../dashboardManagement/{$dashboard}");
            exit();
        }
    }
}

// display the html page
pageHeader("Login", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p>Welcome back! Fill up the form below to login</p>";

htmlForms("login.php",$formFields, $fields, "Login");
?>

OR <a href="signup.php">Signup</a><br>
<a href="forgotPassword.php">Forgot password?</a>
</body>
</html>