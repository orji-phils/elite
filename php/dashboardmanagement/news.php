<?php // news.php
require_once "../helperFiles/allFileImport.php";

// check the login status
loginStatus($_SESSION["userName"], "Please login to post a news");
$userName = cleanInput(ucfirst($_SESSION["userName"]));

$formFields = [
    "News title" => ["text", "title", "required"],
    "News content" => ["text", "content", "required"],
    "Optional picture" => ["file", "picture"]
];

// process the news input
if (isset($_POST["title"])) {
    $isEmpty = FALSE;
    foreach ($_POST as $key => $value) {
        $fields[$key] = cleanInput($value);

        if (empty($value) && $key !== "picture") {
            $_SESSION["error"] = "{$ke} is required.";
            $isEmpty = TRUE;
            break;
        }
    }

    // try uploading the picture and retrieving the file path
    $allowedType = ["png", "jpg", "gif", "jpeg"];
    $fields["picture"] = fileUpload("picture", $allowedType, 3, "Picture uploaded successfully.");

    // news notification parameter arguements
    $title = "Latest News Update";
    $content = "New news posted on the {$_SESSION["create_date"]}.";

    // prepare the transaction data
    $transactionData = [
        "INSERT INTO notifications (title, content, status) VALUES(?, ?, ?)" =>
        [[$title, $content, "unread"],
        null, null],
        "INSERT INTO news (title, content, userName, picture) VALUES(?, ?, ?, ?)" =>
        [[$fields["title"], $fields["content"], $userName, $fields["picture"]],
        "News posted successfully.", "Cannot post news at the moment. Please try again later."]
    ];

    // transact the prepared data
    pdoTransaction($transactionData, "Error uploading news content. Please try again later.", $pdo);
}

// display the html page
pageHeader("News", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", "formStyle.css");
echo "<p>Please fill up the form with optional picture to create a news.</p>";

// display the page form
htmlForms("news.php", $formFields, $fields, "Post News");
?>

</body>
</html>