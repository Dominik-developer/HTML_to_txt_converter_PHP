<!DOCTYPE html>
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
                <section>Result</section>
                <section>
                    <?php 
                        if(isset($_SESSION['info'])) 
                        {
                            echo $_SESSION['info'];
                        }

                        if(isset($_SESSION['respond']))
                        {
                            echo '<div>'.$_SESSION['respond'].'</div>';
                        }else {
                            echo "ERROR!";
                        }
                    ?>
                </section>
            </div>
        </main>
        <footer>
            Frontend project for web search engine. Repository: <a href="https://github.com/Dominik-developer/search_page_frontend/">LINK</a>
        </footer>
    </div>
</body>
</html>