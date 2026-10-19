<script src="assets/js/navbar.js" type="text/javascript" defer></script>
<div class="navbar">
    <a href="index.php">
        <div class="logo">
            <img class="logo" src="assets/images/ui/logo.svg" alt="Logo">
            <p class="title">DigiLib</p>
        </div>
    </a>

    <div class="links" id="nav-link">
        <a href="catalog.php">Catalogo</a>
        <a href="index.php">Home</a>
        <a href="suggestion.php">Proposte</a>
    </div>

    <div class="btns">
        <?php
            require_once "main.php";

            if (loggedIn($conn, false)) {

                $sql = "SELECT email, color, isAdmin FROM users WHERE id = :id";
                $stmt = $conn -> prepare($sql);
                $stmt -> execute([
                    "id" => $_SESSION["user_id"],
                ]);

                $result = $stmt -> fetch(PDO::FETCH_OBJ);
                if ($result -> isAdmin)
                    echo "
                        <a href='account.php'>
                            <div class='account'>
                                <img src='components/avatar.php?color=$result->color' alt='User Ico, Admin pannel'>
                            </div>
                        </a>
                    ";
                else
                    echo "
                        <div class='account'>
                            <img src='components/avatar.php?color=$result->color' alt='User Ico'>
                        </div>
                    ";
            } else {
                echo "
                    <a href='login.php'>
                        <div class='account'>
                            <img src='assets/images/ui/add.svg'>
                        </div>
                    </a>
                ";
            }
        ?>
    </div>
</div>