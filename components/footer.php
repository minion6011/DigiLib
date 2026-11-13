<div class="footer">
    <div class="divider">
        <div class="logo">
            <img class="logo" src="assets/images/ui/logo.svg" alt="Logo">
            <p class="title">DigiLib</p>
        </div>
        <div class="socials">
            <a href="https://github.com/minion6011" target="_blank" rel="noopener noreferrer">
                <i class="fa fa-github fa-3"></i>
            </a>
            <?php
                require_once "main.php";
                
                if (loggedIn($conn, false))
                    echo "
                    <a href='logout.php' class='signout'>
                        <i class='fa fa-sign-out'></i>
                    </a>
                    ";
            ?>
            </div>
    </div>
    <hr>
    <p class="copyright">
        &copy; <?= date("Y") ?> DigiLib. Tutti i diritti riservati.
    </p>
</div>