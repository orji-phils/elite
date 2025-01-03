<?php // uploads.php
// require_once "configurationFile.php";

function fileUpload($fileType, $allowedExtentions, $fileSize, $success) {
    $fileDir = "../uploads/";
    $filePath = $fileDir . basename($_FILES[$fileType]["name"]);
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
    $fileSize = $fileSize * 1024 * 1024;

    // check if the file size is too large
    if ($_FILES[$fileType]["size"] > $fileSize) {
        $error[] = "Your file is too large! Please enter a file of about " . $fileSize . " Mb down";
        return FALSE;
    } elseif (!in_array($fileExtension, $allowedExtentions)) {
        $error[] = "Unsupported file format. Please select a valid file.";
        for ($i=0; $i < count($allowedExtentions) - 1; $i++) { 
            $error[] .= $allowedExtentions[$i] . ", ";
        }
        $error[] .= "or " . $allowedExtentions[count($allowedExtentions) - 1] . " file.";
        return FALSE;
    } else {
        if (move_uploaded_file($_FILES[$fileType]["tmp_name"], $filePath)) {
            $success = $success;
            return $filePath;
        } else {
            $error[] = "Something happened and we couldn't upload your file, please try again later";
            return FALSE;
        }
    }
}
?>