<!DOCTYPE html>

<?php
session_start();

// Sprawdzenie, czy wynik przetwarzania jest dostępny
if (isset($_SESSION['processedContent'])) {
    $processedContent = $_SESSION['processedContent'];
    unset($_SESSION['processedContent']); // Usunięcie danych po wyświetleniu
} else {
    $processedContent = "No content to display.";
}

$processedContent = $_SESSION['cleanedHtml']
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTML to txt</title>

    <link rel="stylesheet" type="text/css" href="https://dominik-developer.github.io/BaseFrame_CSS_library/index.css"> 
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <main>
            <h2>Txt file after convertion </h2>
            
            <div>
                    <?php /* if (!empty($processedContent) && $processedContent !== "No content to display."): ?>
                        <a href="downloads/processed_file.txt" download="processed_file.txt">Download the processed file</a>
                    <?php endif;*/ ?>
                <h3>Result:</h3>
                <section class="code-container">
                    <div class="line-numbers"></div>
                    <pre class="code-output"><?php echo htmlspecialchars($processedContent); ?></pre>
                </section>
                <section id="link">
                    <?php if (!empty($processedContent) && $processedContent !== "No content to display."): ?>
                        <button class="download-btn" onclick="downloadFile()">Download the processed file</button>
                    <?php endif; ?>
                </section>
                <?php 
                    if(isset($_SESSION['info'])) 
                    {
                        echo '<section>'.$_SESSION['info'].'<section>';
                    }
                ?>
            </div>
        </main>
        <footer>
            HTML to Txt safe converter: <a href="https://github.com/Dominik-developer/HTML_to_txt_converter_PHP/">LINK</a>
        </footer>
    </div>
</body>
    <script>
    
        function downloadFile() {
            const link = document.createElement('a');
            link.href = 'downloads/processed_file.txt';  
            link.download = 'processed_file.txt';        
            link.click();                               
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Pobieranie elementów
            const lineNumbers = document.querySelector('.line-numbers'); // Numeracja linii
            const codeOutput = document.querySelector('.code-output');  // Pole na kod

            // Sprawdź, czy elementy istnieją
            if (!lineNumbers || !codeOutput) {
                console.error('Nie znaleziono wymaganych elementów w DOM');
                return;
            }

            // Pobieranie kodu z elementu .code-output
            const outputText = codeOutput.textContent.trim();

            // Podziel kod na linie i dodaj numerację
            const lines = outputText.split('\n');
            lineNumbers.innerHTML = lines.map((_, i) => i + 1).join('<br>'); // Zmieniono na <br>
            codeOutput.textContent = outputText;
        });


    </script>
</html>