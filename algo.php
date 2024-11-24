<?php

include 'logs/logs.php';

define("PROD", false);

// Pobranie IP użytkownika
$ip_address = $_SERVER['REMOTE_ADDR'];

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
}

// Sprawdzenie IP w tablicy
$found = false;
foreach ($assoc_array as $element) {
    if ($ip_address == $element) {
        if (PROD == true) {
            echo "Variable $ip_address was found in array.<br>";
        }
        $found = true;
        $_SESSION['info'] = "<p> Sorry, but you are not allowed to use this app. For more info, contact support. </p>";
        header('Location: show.php');
        break;
    }
}

// Jeśli IP nie jest zablokowane
if (!$found) {

    if (PROD == true) {
        echo "Variable $ip_address was not found in array.<br>";
    }

    if (isset($_FILES['plik']) && $_FILES['plik']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['plik']['tmp_name'];
        $fileName = $_FILES['plik']['name'];
        $fileType = mime_content_type($fileTmpPath); // Typ MIME pliku
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION); // Rozszerzenie pliku
        
        // Odrzucanie plików PHP i JS
        if ($fileExtension === 'php' || $fileExtension === 'js') {
            $_SESSION['info'] = "PHP and JS scripts are forbidden! Possible attack detected.";
            header('Location: show.php');
            exit;
        }

        // Dozwolone typy MIME i rozszerzenia
        $allowedMimeTypes = ['text/html', 'text/plain'];
        $allowedExtensions = ['html', 'txt'];

        if (!in_array($fileType, $allowedMimeTypes) || !in_array($fileExtension, $allowedExtensions)) {
            $_SESSION['info'] = "Invalid file type or extension. Only HTML and TXT files are allowed." ;
            header('Location: show.php');
            exit;
        }

        // Sprawdzenie rozmiaru pliku (max. 5MB)
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        if ($_FILES['plik']['size'] > $maxFileSize) {
            $_SESSION['info'] = "The file is too large! Maximum size is 5MB." ;
            header('Location: show.php');
            exit;
        }
        
        // Przetwarzanie pliku
        $htmlContent = file_get_contents($fileTmpPath);

        // Definicja tagów blokowych
        $blockTags = ['address', 'article', 'aside', 'blockquote', 'body', 'div', 'footer', 'header', 'main', 'nav', 'section', 'html', 'dialog', 'form', 'button', 'input', 'label'];

        // Usuwanie tagów blokowych
        $pattern = '/<\/?(' . implode('|', $blockTags) . ')[^>]*>/i';
        $cleanedHtml = preg_replace($pattern, '', $htmlContent);

        // Usuwanie kodu JavaScript
        $cleanedHtml = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $cleanedHtml);
        $cleanedHtml = preg_replace('/<no-script\b[^>]*>(.*?)<\/no-script>/is', '', $cleanedHtml);

        // Usuwanie kodu PHP (tagi PHP ?php ... ? oraz krótkie tagi ? ... ? ) 
        $cleanedHtml = preg_replace('/<\?php.*?\?>/s', '', $cleanedHtml); // Usuwanie tagów PHP  ...  
        $cleanedHtml = preg_replace('/<\?.*?\?>/s', '', $cleanedHtml); // Usuwanie krótkich tagów PHP  ...  

        // Usuwanie tagów <style> i stylów CSS
        $cleanedHtml = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $cleanedHtml);

        // head tags
        $cleanedHtml = preg_replace('/<head\b[^>]*>.*?<\/head>/is', '', $cleanedHtml);
        
        // Usuwanie tagu DOCTYPE
        $cleanedHtml = preg_replace('/<!DOCTYPE[^>]*>/i', '', $cleanedHtml);

        // Usuwanie pustych linii lub linii z samymi białymi znakami
        $cleanedHtml = preg_replace('/^\s*[\r\n]/m', '', $cleanedHtml);

        // Usuwamy wszystkie tabulatory i nadmiarowe białe znaki
        $cleanedHtml = preg_replace('/^[ \t]+/m', '', $cleanedHtml);  // Usuwa tabulatory i spacje na początku każdej linii
        $cleanedHtml = preg_replace('/[ \t]+$/m', '', $cleanedHtml);  // Usuwa tabulatory i spacje na końcu każdej linii

        // Usuwa nadmiarowe białe znaki (spacje, tabulatory) w środku kodu
        $cleanedHtml = preg_replace('/\s{2,}/', ' ', $cleanedHtml);


        // Tworzenie pliku do pobrania
        $outputDir = __DIR__ . '/downloads'; // Ścieżka zapisu
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true); // Tworzenie katalogu, jeśli nie istnieje
        }
        
        $outputFile = $outputDir . '/processed_file.txt';
        file_put_contents($outputFile, $cleanedHtml);

        // Przekierowanie do show.php z informacją o pliku
        session_start();
        $_SESSION['cleanedHtml'] = $cleanedHtml;
        header("Location: show.php");
        exit;
        
        if (PROD == true) {
            echo '<h1> Output: </h1>';
            echo "<pre>" . htmlspecialchars($cleanedHtml) . "</pre>";
            echo '<a href="downloads/processed_file.txt" download="processed_file.txt">Download the processed file</a>';
        }
        
        } else {
            $_SESSION['info'] = "Error uploading the file!";
            header('Location: show.php');
    }
} else {
    $_SESSION['info'] = "Access denied!";
    header('Location: show.php');
}


