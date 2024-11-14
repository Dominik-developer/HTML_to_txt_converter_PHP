<?php

include 'logs/logs.php';

define("PROD", "false");

// IP check in log
$ip_address = $_SERVER['REMOTE_ADDR'];

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
}

$found = false;

    foreach ($assoc_array as $element) {
        if ($ip_address == $element) {
            if(PROD == true){
                echo "Variable $ip_address was found in array.<br>";
            }

            $found = true;
            $_SESSION['info'] = '<p> Sorry, but you are not allowed to user this app. For more info use email in footer. </p>';
            break;
        }
    }

    // if pass, data enter final check
if (!$found) {
    if(PROD == true){
        echo "variable $ip_address was not found in array.<br>";
    }
    
    if (isset($_FILES['plik']) && $_FILES['plik']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['plik']['tmp_name'];
        $fileName = $_FILES['plik']['name'];
        $fileType = mime_content_type($fileTmpPath); // MIME file
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION); // getting file extension
        
        // rejecting js and php
        if ($fileExtension === 'php' || $fileExtension === 'js') {
            echo "PHP and JS scripts are forbiden! <br> It looks like attack.";
            // block IP adress
            exit;
        }
    
        // Allowed types 
        if ($fileType === 'text/html' || $fileType === 'text/plain') {
            echo "HTML file was uploades succesfully!";
        } else {
            echo "Error! Wrong type of file!";
        }
    
        // Checking (again) if extension is html or txt
        $allowedExtensions = ['html', 'txt'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "Wrong extension. Only html or txt are allowed.";
        }
    
        // Size check (np. not bigger than 5MB)
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        if ($_FILES['plik']['size'] > $maxFileSize) {
            echo "File is too big!";
        }
    
    } else {
        // Submit error
        echo "Submit Error!";
    }
}

