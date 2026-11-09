<?php
    require_once "main.php";
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DigiLib | Login</title>
    <link rel="icon" href="assets/images/ui/logo.svg"/>
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/login.css">

    <script src="assets/js/login.js" type="text/javascript" defer></script>
</head>
<body>
    <?php
        include("components/navbar.php")
    ?>

    <div class="content">
        <div class="login">
            <div class="title" style="--text-color: #ffffff;">
                <img src="components/avatar.php?color=1172f4" class="logo" alt="Logo del sito">
                <p>Accedi a DigiLib</p>
            </div>
            <?php
                if (isset($_GET["error"])) {
                    switch ($_GET["error"]) {
                        case "permission":
                            $msg = "Devi essere un admin per continuare";
                            break;
                        case "noaccount":
                            $msg = "Devi usare un account per continuare";
                            break;
                        case "exist":
                            $msg = "Esiste già un account con quella email";
                            break;
                        case "notfound":
                            $msg = "Nessun utente trovato";
                            break;
                        case "password":
                            $msg = "Password Errata";
                            break;
                        case "invalid":
                            $msg = "Email invalida";
                            break;
                        default:
                            $msg = "Errore sconosciuto";
                            break;
                    }
                    echo "<p class='error'>{$msg}</p>";
                }
            ?>
            <form action="login.php" method="POST">
                <input type="hidden" value="login" id="action-type" name="action">
                <?php
                    if (isset($_GET["redirect"])) {
                        $redirect = htmlspecialchars($_GET["redirect"]);
                        echo "<input type='hidden' value='{$redirect}' name='redirect'>";
                    }
                ?>
                <input type="text" placeholder="Email" name="email" required>
                <input type="password" placeholder="Password" name="password" required>
                <button type="submit" id="action-submit" name="account">ACCEDI</button>
            </form>
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (loggedIn($conn, false))
                        return;

                $action = htmlspecialchars($_POST["action"], ENT_QUOTES);
                $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
                $password = $_POST["password"];

                switch ($action) {
                    case "login":
                        if ($email == false) {
                            header("Location: login.php?error=invalid");
                            exit;
                        }

                        $sql = "SELECT id, password, isAdmin FROM users WHERE email = :email";
                        $stmt = $conn -> prepare($sql);
                        $stmt -> execute([
                            "email" => $email,
                        ]);             
                        $row = $stmt -> fetch(PDO::FETCH_OBJ);        

                        if (!$row) {
                            header("Location: login.php?error=notfound");
                            exit;
                        }

                        $passwordFetch = $row -> password;

                        if (!password_verify($password, $passwordFetch)) {
                            header("Location: login.php?error=password");
                            exit;
                        }

                        $id = $row -> id;
                        $perm = ($row -> isAdmin) == 1;
                        break;
                    case "register":
                        if (!str_contains($email, "@")) {
                            header("Location: login.php?error=invalid");
                            exit;
                        }
                        
                        $domain = explode($email, '@')[1];
                        if ($domain == '' || !checkdnsrr($domain)) {
                            header("Location: login.php?error=invalid");
                            exit;
                        }
                        

                        try {
                            $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
                            $stmt = $conn -> prepare($sql);
                            $stmt -> execute([
                                "email" => $email,
                                "password" => password_hash($password, PASSWORD_DEFAULT) // https://bcrypt-generator.com/
                            ]);
                            //$id = $stmt -> fetch(PDO::FETCH_OBJ) -> id;
                            $id = $conn -> lastInsertId();
                            $perm = false;
                        } catch (PDOException $e) {
                            //echo $sql . "<br>" . $e->getMessage();
                            header("Location: login.php?error=exist");
                            exit;
                        }
                        break;
                    default: 
                        exit;  
                    }

                    login($id, $perm);
                    $location = "index.php";
                    if (isset($_POST["redirect"]))
                        $location = $_POST["redirect"];
                    header("Location: " . $location);
                }
            ?>
            <a 
                class="forgot" 
                href="mailto:<?= $ini["Support"]["email"] ?>?subject=Recupero%20credenziali%20account%20libreria"
            >
                Password Dimenticata?
            </a>
            <p class="action" id="action-change">Non hai un account? <a onclick="changeAction()">Registrati</a></p>
        </div>
    </div>
    <?php
        include("components/footer.php")
    ?>
</body>
</html>