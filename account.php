<?php
    require_once "main.php";
    requireLogin($conn, true);
?>

<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $method = $_POST["_method"] ?? '';
        $type = $_POST["_type"] ?? '';
        switch ($type) {
            case 'book':
                // Methods (book)
                switch ($method) {
                    case 'DELETE':
                        try {
                            if (!isset($_POST["id"]))
                                throw new Exception("Missing ID");

                            $id = $_POST["id"];

                            // Remove from the DB
                            $sql = "DELETE FROM books WHERE id = :id";
                            $stmt = $conn -> prepare($sql);
                            $stmt -> execute([
                                "id" => $id,
                            ]);

                            http_response_code(200);
                        } catch (Exception $e) {
                            http_response_code(400);
                        }
                        break;
                }
                break;
        }
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DigiLib | Admin Dashboard</title>
    <link rel="icon" href="assets/images/ui/logo.svg"/>
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/account.css">
</head>
<body>
    <?php
        include("components/navbar.php");
    ?>

    <?php
        include("components/modals.php")
    ?>

    <div class="content">

        <div class="books-table">
            <form class="search-bar">
                <div class="input">
                    <button type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                    <input name="search_book" placeholder="Cerca per titolo, autore...">
                </div>
                <select name="searchtype_book">
                    <option value="name">Titolo</option>
                    <option value="author">Autore</option>
                    <option value="position">Posizione</option>
                </select>
                <button type="button" class="add">
                    <img src="assets/images/ui/add.svg">
                </button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th class="mb-nd">Copertina</th>
                        <th>Titolo</th>
                        <th class="mb-nd">Autore</th>
                        <th class="mb-nd">Genere</th>
                        <th>Stato</th>
                        <th class="mb-nd">Posizione</th>
                        <th class="mb-nd">Rilascio</th>
                        <th>Azioni</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $params = [];
                        $searchquery = "";

                        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                            if (isset($_GET["searchtype_book"]) && isset($_GET["search_book"])) {
                                switch ($_GET["searchtype_book"]) {
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
                                    $params[':search'] = '%' . $_GET['search_book'] . '%';
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
                            LEFT JOIN tags t ON b.tag_id = t.id
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
                            <?php  
                                if (isset($row->tag_bg) && isset($row->tag_color) && $row->tag_name) {
                            ?>
                                <div 
                                    style="background: #<?= $row->tag_bg ?>; color: #<?= $row->tag_color ?>"
                                    class="genre"
                                >
                                    <?= $row->tag_name ?>
                                </div>
                            <?php
                                } else {
                            ?>
                                <div 
                                    style="background: #e7e7e7; color: #858585"
                                    class="genre"
                                >
                                    Sconosciuto
                                </div>
                            <?php
                                }
                            ?>
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
                                        echo "<div class='state unknown'>Sconosciuto</div>";
                                        break;
                                }
                            ?>
                        </td>

                        <td class="mb-nd">
                            <?= $row->position ?>
                        </td>

                        <td class="mb-nd"><?= str_replace("-", "/", ($row->date)) ?></td>

                        <td>
                            <div class="action-btns"> 
                                <button onclick="openDeleteBookModal(<?= $row->id ?>)">
                                    <img src="assets/images/ui/delete.svg">
                                </button>
                                <button>
                                    <img src="assets/images/ui/edit.svg">
                                </button>
                            </div>
                        </td>
                    </tr>

                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <?php
        include("components/footer.php");
    ?>
</body>
</html>