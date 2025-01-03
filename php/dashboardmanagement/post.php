<?php // post.php
require_once "../helperFiles/allFileImport.php";

// check the login status
loginStatus($_SESSION["userName"], "Please login to view and create posts.");
$userName = cleanInput(ucfirst($_SESSION["userName"]));

// retrieve the recent posts stored
$retrievePost = sqlFunctions("SELECT * FROM posts ORDER BY created_date DESC LIMIT 10", [], null, "Unable to retrieve recent posts, please try again later.", $pdo);

// instantiate the formFields
$formFields = [
    "Create a new post" => ["textarea", "post", "required"],
    "Attach an optional picture" => ["file", "picture"]
];

// process the post input
if (isset($_POST["post"])) {
    $fields["post"] = cleanInput($_POST["post"]);
    $fields["picture"] = $_POST["picture"];

    $isEmpty = FALSE;
    if (empty($fields["post"])) {
        $_SESSION["error"] = "{$key} is required.";
        $isEmpty = TRUE;
    }

    // try uploading the picture and retrieving the file path
    $allowedType = ["png", "jpg", "gif", "jpeg"];
    $fields["picture"] = fileUpload("picture", $allowedType, 3, "Picture uploaded successfully.");

    if (!$isEmpty) {
        // post notification parameter arguement
        $title = "Latest Post Update";
            $content = "New post from {$userName}.";
            
            // prepare data to be transacted
        $transactionData = [
            "INSERT INTO notifications (title, content, status) VALUES(?, ?, ?)" =>
            [[$title, $content, "unread"],
            null, null],
            "INSERT INTO POSTS (post, picture, userName) VALUES(?, ?, ?)" =>
            [[$fields["post"], $fields["picture"], $userName],
            "Posted successfully.", "Can't post now. Please try again later."]
        ];

        // transact the prepared data
        pdoTransaction($transactionData, "Error uploading posts. Please try again later.", $pdo);
    }
}

// display the html page
pageHeader("Posts", $_SESSION["success"] ?? "", $_SESSION["error"] ?? "", null);
echo "<p>Hello $userName! Make new public post by typing and optionally adding pictures.</p>";

// display the html form
htmlForms("post.php", $formFields, $fields, "Post");

// display the recent posts
echo "<h2>Recent posts</h2>";
if ($retrievePost) {
    while ($post = $retrievePost->fetch()) {
        // sanitize the result
        foreach ($post as $key => $value) {
            $post[$key] = cleanInput($value);
        }

        // Format dates
        $postedDate = date("F j, Y, g:i a", strtotime($post["created_date"]));

        // display the recent posts
        echo <<<_html
        <h4>public post by {$post["userName"]}</h4>
        <p>Posted on {$postedDate}</p>
        <p>{$post["post"]}</p>
        _html;
    }
} else {
    echo "<p>No new post yet. When posts are made, they are shown here.</p>";
}

?>

</body>
</html>