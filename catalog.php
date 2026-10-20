<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DigiLib | Catalogo Libri</title>
    <link rel="icon" href="assets/images/ui/logo.svg"/>
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/catalog.css">
</head>
<body>
    <?php
        include("components/navbar.php")
    ?>

    <div class="banner">
        <div class="text">
            <p class="sub-title">Catalogo</p>
            <p class="title">Scopri i nostri libri</p>
            <p class="details">
                Qui trovi tutti i libri disponibili nel nostro catalogo. Puoi cercare, filtrare 
                <br> 
                e visualizzare i dettagli di ogni titolo.
            </p>
        </div>
        <img src="assets/images/ui/bannerCatalog.svg">
    </div>

    <div class="content">
        <div class="books-table">
            <form class="search-bar">
                <div class="input">
                    <button type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                    <input name="search" placeholder="Cerca per titolo, autore...">
                </div>
                <select name="searchtype">
                    <option value="name">Titolo</option>
                    <option value="author">Autore</option>
                    <option value="position">Posizione</option>
                </select>
            </form>

            <table>
                <thead>
                    <tr>
                        <th class="mb-nd">Copertina</th>
                        <th>Titolo</th>
                        <th class="mb-nd">Autore</th>
                        <th class="mb-nd">Genere</th>
                        <th>Disponibilità</th>
                        <th class="mb-nd">Posizione</th>
                        <th class="mb-nd">Rilascio</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        require_once("main.php");

                        $params = [];
                        $searchquery = "";

                        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                            if (isset($_GET["searchtype"]) && isset($_GET["search"])) {
                                switch ($_GET["searchtype"]) {
                                    case "name":
                                        $searchquery = "WHERE b.name LIKE :search";
                                        break;
                                    case "author":
                                        $searchquery = "WHERE b.author LIKE :search";
                                        break;
                                    case "position":
                                        $searchquery = "WHERE b.position LIKE :search";
                                        break;
                                }

                                if ($searchquery != "")
                                    $params[':search'] = '%' . $_GET['search'] . '%';
                            }
                        }

                        $sql = "
                            SELECT
                                b.id,
                                b.img,
                                b.name,
                                b.author,
                                b.state,
                                b.position,
                                b.publish_date AS date,
                                t.name AS tag_name,
                                t.color AS tag_color,
                                t.background AS tag_bg
                            FROM books b
                            JOIN tags t ON b.tag_id = t.id
                            {$searchquery}
                            ORDER BY id
                            LIMIT 100
                        ";

                        $stmt = $conn -> prepare($sql);
                        $stmt -> execute($params);

                        foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $row) {
                    ?>

                    <tr>
                        <td class="mb-nd">
                            <a href="<?= $row->img ?>" target="_blank">
                                <img loading="lazy" src="<?= $row->img ?>" alt="Copertina del libro">
                            </a>
                        </td>

                        <td><?= $row->name ?></td>

                        <td class="mb-nd"><?= $row->author ?></td>

                        <td class="mb-nd">
                            <div 
                                style="background: #<?= $row->tag_bg ?>; color: #<?= $row->tag_color ?>"
                                class="genre"
                            >
                                <?= $row->tag_name ?>
                            </div>
                        </td>

                        <td>
                            <?php 
                                switch ($row->state) {
                                    case 0: // Disponibile
                                        echo "<div class='state free'>Disponibile</div>";
                                        break;
                                    case 1: // In prestito
                                        echo "<div class='state occupied'>In prestito</div>";
                                        break;
                                    default:
                                        echo "<div class='state unknown'>Sconosciuta</div>";
                                        break;
                                }
                            ?>
                        </td>

                        <td class="mb-nd">
                            <?= $row->position ?>
                        </td>

                        <td class="mb-nd"><?= str_replace("-", "/", ($row->date)) ?></td>
                    </tr>

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