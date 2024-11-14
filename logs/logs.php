<?php
// path to logs file
$file_path = './logs/logs.txt';

// Check if exist
if (file_exists($file_path)) {
    // reading file and creating lines
    $lines = file($file_path, FILE_IGNORE_NEW_LINES);

    // creating table of IP ad.
    $assoc_array = [];
    foreach ($lines as $index => $line) {
        $assoc_array["IP_Adress_" . ($index + 1)] = $line;
    }

    // Showing table
    //print_r($assoc_array);
} else {
    echo "Secruity error.";
}



