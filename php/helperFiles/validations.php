<?php // validations.php
require_once 'configurationFile.php';

// function to validate the name
function validate_name($name, $str) {
    if ($name != "" && preg_match("/^[a-zA-Z]+$/", $name)) {
        return $str;
    }

    $_SESSION["error"] = "Invalid $str name entery. Please enter only alphabets.<br>";
    return null;
}

// function to validate the username
function validate_userName($userName) {
    if ($userName != "" && preg_match("/^[a-zA-Z0-9_-]+/", $userName)) {
        return $userName;
    }

    $_SESSION["error "]= "Invalid user name entery. User name can contain alphabets, alphanumeric keys including dash and underscore.";
    return null;
}

// function to validate the email
function validate_email($email) {
    if ($email != "" || filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $email;
    }

    $_SESSION["error"] = "Invalid email entery. Please enter your valid email.";
    return null;
}

// function to validate the password
function validate_password($password) {
    if (preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        return $password;
    }

    $_SESSION["error"] = "Invalid password entery. Password must contain at least a capital letter, a small letter, and  a number.";
    return null;
}

// function to validate phone number
function validate_phone($phone) {
    return $phone;
}

// function to validate scores
function validate_score($score, $str, $max) {
    if ($score != "" || preg_match("/\d/", $score)) {
        if ($score <= $max) {
            return $score;
        }

        $_SESSION["error"] = ucfirst($str) . " score must between 0 to " . $max . ".";
        return null;
    } else {
        $_SESSION["error"] = "Invalid $str score entery. $str score entery must be digits.";
        return null;
    }
}
?>