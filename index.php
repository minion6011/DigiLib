<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DigiLib | Home Page</title>
    <link rel="icon" href="assets/images/ui/logo.svg"/>
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <?php
        include("components/navbar.php")
    ?>

    <!-- <p class="centercheck">•</p> -->

    <div class="banner">
        <div class="text">
            <p class="title">DigiLib</p>
            <p class="sub-title">
                La biblioteca digitale 
                <br> 
                della tua scuola
            </p>
            <div class="btns">
                <a href="catalog.php">
                    <button class="catalog">
                        <i class="fa fa-book"></i>
                        Vai al catalogo
                    </button>
                </a>

                <a href="suggestion.php">
                    <button class="request">
                        <i class="fa fa-paper-plane"></i>
                        Richiedi un libro
                    </button>
                </a>
            </div>
        </div>
        <img src="assets/images/ui/bannerHome.svg">
    </div>

    <div class="content">
        <div class="cards">
            <div class="card">
                <div class="ico">
                    <i class="fa fa-bookmark"></i>
                </div>
                <div class="text">
                    <p class="title">Catalogo Completo</p>
                    <p class="description">
                        Consulta tutti i libri disponibili
                        <br> 
                        nella nostra biblioteca.
                    </p>
                </div>
            </div>
        
            <div class="card">
                <div class="ico">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="text">
                    <p class="title">Richiedi un libro</p>
                    <p class="description">
                        Non trovi che cerchi? 
                        <br>
                        Richiedilo in pochi click.
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="ico">
                    <i class="fa fa-shield"></i>
                </div>
                <div class="text">
                    <p class="title">Gestione semplice</p>
                    <p class="description">
                        Un sistema intuitivo, pensato
                        <br>
                        per gli studenti e docenti.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php
        include("components/footer.php")
    ?>
</body>
</html>