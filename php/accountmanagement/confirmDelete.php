<?php // confirmDelete.php
require_once "../helperFiles/allFileImport.php";

// check login status
loginStatus($_SESSION["userName"], "Please login to continue.");

// initialise the formFields array
$formFields = [
    "Are you sure you want to delete your account?" => ["radio", "response", "yes", "no", "required"]
];

// process the user input
if (isset($_POST["response"])) {
    if ($_POST["response"] == "yes") {
        // move the user to the next page to confirm with password and delete their account
        header("Location: authenticateDelete.php");
        exit();
    } else {
        // move user back to the previous page
        header("Location: profile.php");
        exit();
    }
}

// display the html page
pageHeader("Confirm Account Deletion", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p>Note: You will loose your personal data, group data and other information stored in this site.</p>";

// display the form
htmlForms("confirmDelete.php", $formFields, $fields, "Proceed");
?>

</body>
</html>