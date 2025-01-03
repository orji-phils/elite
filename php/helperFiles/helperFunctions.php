<?php // helperFunctions.php
require_once "configurationFile.php";

// function to sanitize user input
function cleanInput($userInput) {
    return htmlspecialchars(htmlentities(stripcslashes(trim($userInput))));
}

// function to check user's login status
function loginStatus($userName, $errorMessage) {
    if (empty($userName)) {
        $_SESSION["error"] = $errorMessage;
        header("Location: ../accountManagement/login.php");
        exit();
    }
}

// function to compare, validate and hash user's password.
function checkPassword($password, $confirm) {
    if ($password !== $confirm) {
        $_SESSION["error"] = "Sorry! passwords do not match. Please check the passwords and try again.";
        return "";
    }
    
    if (empty($password) || empty($confirm)) {
        $_SESSION["error"] = "The password and confirm password fields are required to proceed.";
        return "";
    }

    if (!empty(validate_password($password))) {
        return password_hash(validate_password($password), PASSWORD_DEFAULT);
    }

    return "";
}

// function to get the max value for recording and updating result
function get_scores($value) {
    $key = [
        "test" => 40,
        "exam" => 60,
        "total" => 100,
        "average" => 50,
        "position" => 50
    ];

    return isset($key[$value]) ? $key[$value] : "";
}

// function to render sections
function render_sections($data, $section_name, $section_fields) {
    if (!empty($data)) {
        echo "<h4>{$section_fields[0]}</h4>";
        foreach ($data as $row) {
            if ($row[$section_fields[1]]) {
                $postDate = date("F j, Y, g:i a", strtotime($row[$section_fields[1]]));
                echo "<p>{$postDate}</p>";
            }

            for ($i = 2; $i < count($section_fields); $i++) {
                echo "<p>{$row[$section_fields[$i]]}</p>";
            }
        }
    } else {
        echo "<p>No {$section_name} available at the moment. Latest {$section_name} will be posted here when available.</p>";
    }
    
}

// create the transaction function
function pdoTransaction($sqlQuery, $errorMessage, $pdo) {
    try {
        $pdo->beginTransaction();

        // insert records into multiple database tables
        foreach ($sqlQuery as $key => $value) {
            sqlFunctions($key, $value[0], $value[1], $value[2], $pdo);
        }
        $pdo->commit();
        return TRUE;
    } catch (PDOException $e) {
        $pdo->rollBack();
        showLogError($errorMessage, $e);
        return FALSE;
    }
}
?>