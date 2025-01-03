<?php // profile.php
// import the entire files needed.
require_once '../helperFiles/allFileImport.php';

$formField = [
    "Personal Information" => ["start"],
    "First name" => ["text", "firstName", "required"],
    "Last name" => ["text", "lastName", "required"],
    "User name" => ["text", "userName", "disabled"],
    "Gender" => ["select", "gender", "SELECT your gender", "Male", "Female", "Other", "required"],
    "Type" => ["select", "type", "Select your role", "admin", "teacher", "student", "staff", "disabled"],
    "Class" => ["select", "class", "Select your class", "Basic1", "Basic2", "Basic3", "Basic4", "Basic5", "Basic6", "required"],
    "Date of Birth" => ["date", "date"],
    "Age" => ["number", "age", "disabled"],
    "Profile picture" => ["file", "profilePicture"],
    "end personal info" => ["end"],
    "Contact Information" => ["start"],
    "Phone" => ["tel", "phone"],
    "Email" => ["email", "email", "required"],
    "end contact info" => ["end"]
];

// check if the user is logged in
if (isset($_SESSION["userName"])) {
    // retrieve all profile info stored in the database for this user
    $retrieveProfile = sqlFunctions("SELECT * FROM profile WHERE userName = ?", [$_SESSION["userName"]],
    "", "Unable to retrieve user's profile info now. Please try again later.", $pdo);
    $retrieveProfile = $retrieveProfile ? $retrieveProfile->fetch() : "";
    
    if ($retrieveProfile) {
        // initialize the userInfo array with the information retrieved
        foreach ($retrieveProfile as $key => $value) {
            $fields[$key] = cleanInput($value);
        }

        $fields["age"] = date_diff(date_create($fields["date"] ?? "now"), date_create("now"))->y;
    }
} else {
    $_SESSION["error"] = "Please login to view and edit profile.";
    header("Location: login.php");
    exit();
}

// process the posted profile info
if (isset($_POST["firstName"])) {
    foreach ($_POST as $key => $value) {
        switch ($key) {
            case "email":
                $fields[$key] = validate_email(cleanInput($value));
                break;
            
            case "phone":
                $fields[$key] = validate_phone(cleanInput($value));
                break;

            case "class":
                $fields[$key] = validate_userName(cleanInput($value));
                break;

            default:
                $fields[$key] = validate_name($key, cleanInput($value));
                break;
        }
    }

    // try uploading the profile picture and retrieving the file path
    $allowedType = ["png", "jpg", "gif", "jpeg"];
    $fields["profilePicture"] = $_FILES["profilePicture"] ? fileUpload("profilePicture", $allowedType, 3, "Profile picture uploaded successfully.") : "";

    // notification parameter arguements
    $title= "Profile update";
    $content = "{$fields["userName"]} updated their profile information.";
    // data to be transacted
    $transactionData = [
        "UPDATE profile SET firstName = ?, lastName = ?, gender = ?, date = ?, phone = ?, email = ?, class = ?, picture = ? Where userName = ?" =>
        [[$fields["firstName"], $fields["lastName"], $fields["gender"], $fields["date"], $fields["phone"], $fields["email"], $fields["class"], $fields["profilePicture"], $fields["userName"]],
        "Profile updated successfully. Thank you", "Can't update profile now, please try again later. Thank you"],
        "INSERT INTO notifications (title, content, status) VALUES(?, ?, ?)" =>
        [[$title, $content, "unread"],
        null, null]
    ];
    
    // transact prepared data.
    pdoTransaction($transactionData, "Sorry! An error occured and we can't update your profile information now. Please try again later.", $pdo);
}
// start the html output
pageHeader("Profile", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo '<p>Please fill up an accurate information in the form below to create your profile.</p>';

// output the form to be filled
htmlForms("profile.php", $formField, $fields, "Update Profile");
?>

    <a href="logout.php">Logout</a><br>
    <a href="confirmDelete.php">Delete account</a>

    <script type="text/javascript" src="../../javaScript/profile.js?v=1"></script>

</body>
</html>