<?php // forms.php
require_once "helperFunctions.php";

//  function for html selections
function htmlSelect($key, $value, $userInput) {
    if ($value[count($value) - 1] == "required" || $value[count($value) - 1] == "disabled") {
        $valueIndexes =  count($value) -1;
        $required = $value[count($value) - 1];
    } else {
        $valueIndexes =  count($value);
        $required = "";
    }    
    
    echo '<select name="' . $value[1] . '" id="' . $value[1] . '" ' . $required . '>';
    
        for ($i=2; $i < $valueIndexes; $i++) {
            $selected = $userInput[$value[1]] == $value[$i] ? "selected" : "";
            echo '<option value="' . $value[$i] . '" ' . $selected . '>' . ucfirst($value[$i]) . '</option>';
        }
    
echo '</select><br>';
}

// function for html checkBoxes and radioButtons
function htmlRadioCheck($key, $value, $userInput) {
    if (in_array("required", $value) || in_array("disabled", $value)) {
        $valueIndexes =  count($value) -1;
        $required = $value[count($value) - 1];
    } else {
        $valueIndexes =  count($value);
        $required = "";
    }    

    echo "<label for=\"{$key}\">{$key}</label><br>";
    for ($i=2; $i < $valueIndexes; $i++) {
        $checked = isset($userInput[$value[1]]) && $userInput[$value[1]] == $value[$i] ? $value[$i] : "";

        echo <<<html
        <label for="{$value[$i]}">{$value[$i]}</label>
        <input type="{$value[0]}" name="{$value[1]}" value="{$value[$i]}" id="{$value[$i]}" {$checked} {$required}><br>
        html;
    }
}

// function for other html input fields
function htmlOthers($key, $value, $userInput) {
    $required = "";
    if (in_array("required", $value)) {
        $required = "required";
    } elseif (in_array("disabled", $value)) {
        $required = "disabled";
    }

    echo '<label for="' . $value[1] . '">' . cleanInput($key) . '</label>
    <input type="' . $value[0] . '" name="' . cleanInput($value[1]) . '" value="' . ($userInput[$value[1]] ?? "") . '" id="' . $value[1] . '" ' . $required . '><br>
    <p id="' . $value[1] . 'Error"></p>';

    if (stripos($key, "password") !== false) {
        echo '<button id="password" class="toggle-password" type="button">Show Password</button><br>';
    }
}

function htmlTextArea ($key, $value, $userInput) {
    echo '
        <label for="' . cleanInput($value[1]) . '">' . cleanInput($key) . '</label>
        <textarea name="' . $value[1] . '" id="' . $value[1] . '" placeholder="Enter your comment. only 100 characters" cols="25" rows="4">' . cleanInput($userInput[$value[1]] ?? "") . '</textarea>
    ';
}

function htmlForms($url, $formFields, $userInput, $buttonName) {
    echo '<form action="' . $url . '" method="post" enctype="multipart/form-data" autocomplete="on">';
    
    foreach ($formFields as $key => $value) {
        switch ($value[0]) {
            case "start":
                echo '<fieldset>
                    <legend>' . $key .'</legend>';
                break;

            case "end":
                echo '</fieldset>';
                break;

            case "select":
                htmlSelect($key, $value, $userInput);
                break;

            case "checkbox":
            case "radio":
                htmlRadioCheck($key, $value, $userInput);
                break;

            case "textarea":
                htmlTextArea($key, $value, $userInput);
                break;
            
            default:
                htmlOthers($key, $value, $userInput);
                break;
        }
    }

    echo '<input type="submit" value="' . $buttonName . '">
    </form>';
}
?>
