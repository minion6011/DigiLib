<?php 
    require_once("main.php");
?>

<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        requireLogin($conn, false);

        if (!isset($_POST["title"]) || !isset($_POST["notes"]))
            return;
        $title = $_POST["title"];
        $notes = $_POST["notes"];
        $id = $_SESSION["user_id"];
        try {
            $sql = "INSERT INTO suggestions (title, notes, creator) VALUES (:title, :notes, :creator)";
            $stmt = $conn -> prepare($sql);
            $stmt -> execute([
                "title" => $title,
                "notes" => $notes,
                "creator" => $id
            ]);

            //$id = $stmt -> fetch(PDO::FETCH_OBJ) -> id;
            //$id = $conn -> lastInsertId();

            header("Location: " . $_SERVER['REQUEST_URI']);
        } catch (PDOException $e) {
            //echo $sql . "<br>" . $e->getMessage();
            return;
        }
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DigiLib | Suggerisci un libro</title>
    <link rel="icon" href="assets/images/ui/logo.svg"/>
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/suggestion.css">
</head>
<body>
    <?php
        include("components/navbar.php")
    ?>

    <div class="banner">
        <div class="text">
            <p class="sub-title">Proponi un libro</p>
            <p class="title">
                Hai un libro che vorresti 
                <br>
                nella nostra biblioteca?
            </p>
            <p class="details">
                Se hai un'idea o un titolo che ti piacerebbe leggere, 
                <br>
                puoi suggerirlo qui. Il tuo suggerimento potrebbe diventare
                <br>
                la prossima aggiunta al catalogo
            </p>
        </div>
        <img src="assets/images/ui/bannerSuggestion.svg">
    </div>

    <div class="content">
        <form class="card" method="POST">
            <div class="title">
                <div class="ico">
                    <i class="fa fa-lightbulb-o"></i>
                </div>
                <div class="text">
                    <p class="title">Fai una proposta</p>
                    <p class="description">Compila i campi qui sotto con il titolo del libro e, se vuoi, aggiungi delle note.</p>
                </div>
            </div>
            <div class="inputs">
                <div class="input">
                    <p class="title">Nome del libro</p>
                    <div class="box">
                        <div class="ico">
                            <img src="components/avatar.php?color=5b6e86">
                        </div>
                        <input maxlength="255" name="title" required type="text" placeholder="Inserisci il titolo del libro...">
                    </div>
                </div>
                <div class="input">
                    <p class="title">Note</p>
                    <div class="box">
                        <div class="ico">
                            <i class="fa fa-pencil"></i>
                        </div>
                        <textarea
                            maxlength="2047" 
                            name="notes"
                            placeholder="Puoi aggiungere autore, genere, motivo della proposta, link o altre informazioni utili..."
                            rows="5" 
                            cols="33"    
                        ></textarea>
                    </div>
                </div>
            </div>
            <button type="submit">
                <i class="fa fa-paper-plane"></i>
                Invia proposta
            </button>
        </form>

        <div class="books-table">
            <table>
                <thead>
                    <tr>
                        <th>Titolo</th>
                        <th>Note</th>
                        <th>Data</th>
                        <th>Stato</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $sql = "
                            SELECT
                                id,
                                title,
                                notes,
                                date,
                                state
                            FROM suggestions
                            ORDER BY id
                            LIMIT 10
                        ";

                        $stmt = $conn -> prepare($sql);
                        $stmt -> execute();

                        foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $row) {
                    ?>

                    <tr>
                        <td><?= $row->title ?></td>

                        <td><?= $row->notes ?></td>

                        <td><?= $row->date ?></td>
                    
                        <td>
                            <?php
                                switch ($row->state) {
                                    case 0: // In attesa di accettazione
                                        echo "<div class='state occupied'>In attessa</div>";
                                        break;
                                    case 1: // Accettato
                                        echo "<div class='state free'>Accettato</div>";
                                        break;
                                    case 2: // Rifiutato
                                        echo "<div class='state deny'>Rifiutato</div>";
                                        break;
                                    default:
                                        echo "<div class='state unknown'>Sconosciuto</div>";
                                        break;
                                }
                            ?>
                        </td>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php
        include("components/footer.php")
    ?>
</body>
</html>