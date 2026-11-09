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
                                throw new Exception("Missing Args");

                            $id = $_POST["id"];
                            // Get Image Path
                            $sql = "SELECT img FROM books WHERE id = :id";
                            $stmt = $conn -> prepare($sql);
                            $stmt -> execute([
                                "id" => $id,
                            ]);
                            $imgpath = (string) $stmt->fetch(PDO::FETCH_OBJ)->img;

                            // Remove from the DB
                            $sql = "DELETE FROM books WHERE id = :id";
                            $stmt = $conn -> prepare($sql);
                            $stmt -> execute([
                                "id" => $id,
                            ]);

                            // Delete old image
                            if (str_starts_with($imgpath, (string) $ini["Paths"]["bookimgs"])) {
                                if (file_exists($imgpath)) {
                                    unlink($imgpath);
                                }
                            }

                            http_response_code(200);
                        } catch (Exception $e) {
                            http_response_code(400);
                        }
                        break;
                    case 'POST': // img (file)
                        if (!isset($_POST["title"], $_POST["author"], $_POST["date"], $_POST["state"]))
                            throw new Exception("Missing Args");
                        try {
                            $img = null;

                            $params = [
                                "title" => htmlspecialchars($_POST["title"], ENT_QUOTES), // Sanification: htmlspecialchars($_POST["title"], ENT_QUOTES)
                                "author" => htmlspecialchars($_POST["author"], ENT_QUOTES),
                                "date" => $_POST["date"],
                                "state" => filter_var($_POST["state"], FILTER_SANITIZE_NUMBER_INT),
                                "position" => isset($_POST["position"]) ? 
                                    htmlspecialchars($_POST["position"], ENT_QUOTES) : null,
                                "tag_id" => isset($_POST["tag_id"]) ? 
                                    filter_var($_POST["tag_id"], FILTER_SANITIZE_NUMBER_INT) : null
                            ];

                            // Image Validation
                            $imgStatus = isset($_FILES["img"]) ?
                                validateImg($_FILES["img"], $ini["DB"]["maximagesize"]) : 1;

                            switch ($imgStatus) {
                                case 0:
                                    // Uploads image
                                    $imgpath = $ini["Paths"]["bookimgs"];
                                    $ext = pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);
                                    $img_name = bin2hex(random_bytes(16)) . '.' . $ext; // Random name
                                    $img = (string) $imgpath . $img_name;
                                    $params["img"] = $img;

                                    if (!move_uploaded_file($_FILES["img"]['tmp_name'], $img)) {
                                        http_response_code(422); // Unprocessable Content
                                        break 2;
                                    }
                                    break;
                                case 2:
                                    http_response_code(431); // Request Header Fields Too Large
                                    break 2;
                            }


                            $img_check = isset($img) ? ":img" : "DEFAULT";
                            // SQL Query
                            $sql = "
                                INSERT INTO books 
                                    (img, name, author, publish_date, state, position, tag_id) 
                                VALUES 
                                    ($img_check, :title, :author, :date, :state, :position, :tag_id)
                            ";
                            $stmt = $conn -> prepare($sql);
                            $stmt -> execute($params);
                            http_response_code(200);
                        } catch (Exception $e) {
                            //echo $e;
                            http_response_code(400);
                        }
                        break;
                    case 'PATCH':
                        if (!isset($_POST["id"], $_POST["title"], $_POST["author"], $_POST["date"], $_POST["state"]))
                            throw new Exception("Missing Args");
                        try {
                            $img = null;

                            $params = [
                                "id" => filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT), // Sanification: filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT)
                                "title" => htmlspecialchars($_POST["title"], ENT_QUOTES), // Sanification: htmlspecialchars($_POST["title"], ENT_QUOTES)
                                "author" => htmlspecialchars($_POST["author"], ENT_QUOTES),
                                "date" => $_POST["date"],
                                "state" => filter_var($_POST["state"], FILTER_SANITIZE_NUMBER_INT),
                                "position" => isset($_POST["position"]) ? 
                                    htmlspecialchars($_POST["position"], ENT_QUOTES) : null,
                                "tag_id" => isset($_POST["tag_id"]) ? 
                                    filter_var($_POST["tag_id"], FILTER_SANITIZE_NUMBER_INT) : null
                            ];

                            // Image Validation
                            $imgStatus = isset($_FILES["img"]) ?
                                validateImg($_FILES["img"], $ini["DB"]["maximagesize"]) : 1;

                            switch ($imgStatus) {
                                case 0:
                                    // Uploads image
                                    $imgpath = $ini["Paths"]["bookimgs"];
                                    $ext = pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);
                                    $img_name = bin2hex(random_bytes(16)) . '.' . $ext; // Random name
                                    $img = (string) $imgpath . $img_name;
                                    $params["img"] = $img;

                                    if (!move_uploaded_file($_FILES["img"]['tmp_name'], $img)) {
                                        http_response_code(422); // Unprocessable Content
                                        break 2;
                                    }
                                    // Remove Old Image
                                    $sql = "SELECT img FROM books WHERE id = :id";
                                    $stmt = $conn -> prepare($sql);
                                    $stmt -> execute([
                                        "id" => $_POST["id"],
                                    ]);
                                    $imgoldpath = (string) $stmt->fetch(PDO::FETCH_OBJ)->img;
                                    if (str_starts_with($imgoldpath, (string) $ini["Paths"]["bookimgs"])) {
                                        if (file_exists($imgoldpath)) {
                                            unlink($imgoldpath);
                                        }
                                    }
                                    break;
                                case 2:
                                    http_response_code(431); // Request Header Fields Too Large
                                    break 2;
                            }


                            $img_check = isset($img) ? ":img" : "DEFAULT";
                            // SQL Query
                            $sql = "
                                UPDATE books SET 
                                    img = $img_check,
                                    name = :title,
                                    author = :author,
                                    publish_date = :date,
                                    state = :state,
                                    position = :position,
                                    tag_id = :tag_id
                                WHERE
                                    id = :id
                            ";
                            $stmt = $conn -> prepare($sql);
                            $stmt -> execute($params);
                            http_response_code(200);
                        } catch (Exception $e) {
                            //echo $e;
                            http_response_code(400);
                        }
                        break;     
                }
                break;
            case 'tag':
                switch ($method) {
                    case 'POST':
                        if (!isset($_POST["name"], $_POST["color"], $_POST["background"]))
                            throw new Exception("Missing Args");
                        try {
                            // Max Elements controlls
                            $sql = "SELECT COUNT(*) FROM tags;";
                            $stmt = $conn -> prepare($sql);
                            $stmt -> execute();
                            $tagN = $stmt->fetchColumn();
                            if ($tagN > 100) {
                                http_response_code(507);  // Insufficient Storage
                                break 2;
                            }
                            // Color filter
                            $color = filterColor($_POST["color"]);
                            $background = filterColor($_POST["background"]);
                            // SQL Query
                            $sql = "INSERT INTO tags (name, color, background) VALUES (:name, :color, :background)";
                            $stmt = $conn -> prepare($sql);
                            $stmt -> execute([
                                "name" => htmlspecialchars($_POST["name"], ENT_QUOTES), // Sanification: htmlspecialchars($_POST["name"], ENT_QUOTES)
                                "color" => $color,
                                "background" => $background,
                            ]);
                            http_response_code(200);
                        } catch (Exception $e) {
                            //echo $e;
                            http_response_code(400);
                        }
                        break;
                    case 'DELETE':
                        try {
                            if (!isset($_POST["id"]))
                                throw new Exception("Missing Args");

                            $id = $_POST["id"];

                            // Remove from the DB
                            $sql = "DELETE FROM tags WHERE id = :id";
                            $stmt = $conn -> prepare($sql);
                            $stmt -> execute([
                                "id" => $id,
                            ]);
                            
                            http_response_code(200);
                        } catch (Exception $e) {
                            http_response_code(400);
                        }
                        break;
                    case 'PATCH':
                        if (!isset($_POST["id"], $_POST["name"], $_POST["color"], $_POST["background"]))
                            throw new Exception("Missing Args");
                        try {
                            $color = filterColor($_POST["color"]);
                            $background = filterColor($_POST["background"]);
                            // SQL Query
                            $sql = "
                                UPDATE tags SET
                                    id = :id,
                                    name = :name,
                                    color = :color,
                                    background = :background
                                WHERE
                                    id = :id
                            ";
                            $stmt = $conn -> prepare($sql);
                            $stmt -> execute([
                                "id" => filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT),
                                "name" => htmlspecialchars($_POST["name"], ENT_QUOTES), // Sanification: htmlspecialchars($_POST["name"], ENT_QUOTES)
                                "color" => $color,
                                "background" => $background,
                            ]);
                            http_response_code(200);
                        } catch (Exception $e) {
                            //echo $e;
                            http_response_code(400);
                        }
                        break;
                }
            case 'suggestion':
                switch ($method) {
                    case 'DELETE':
                        try {
                            if (!isset($_POST["id"]))
                                throw new Exception("Missing Args");

                            $id = $_POST["id"];

                            // Remove from the DB
                            $sql = "DELETE FROM suggestions WHERE id = :id";
                            $stmt = $conn -> prepare($sql);
                            $stmt -> execute([
                                "id" => $id,
                            ]);
                            
                            http_response_code(200);
                        } catch (Exception $e) {
                            http_response_code(400);
                        }
                        break;
                    case 'PATCH':
                        if (!isset($_POST["id"], $_POST["state"]))
                            throw new Exception("Missing Args");
                        try {
                            // SQL Query
                            $sql = "
                                UPDATE suggestions SET
                                    state = :state
                                WHERE
                                    id = :id
                            ";
                            $stmt = $conn -> prepare($sql);
                            $stmt -> execute([
                                "id" => filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT),
                                "state" => filter_var($_POST["state"], FILTER_SANITIZE_NUMBER_INT),
                            ]);
                            http_response_code(200);
                        } catch (Exception $e) {
                            //echo $e;
                            http_response_code(400);
                        }
                        break;
                }
        }
        header("Location: account.php"); // Reset request
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
        <div class="custom-table">
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
                <button type="button" class="add" onclick="openCreateBookModal()">
                    <img src="assets/images/ui/add.svg">
                </button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th class="mb-nd center">Copertina</th>
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
                                t.id AS tag_id,
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
                        <td class="mb-nd center">
                            <a href="<?= $row->img ?>" target="_blank">
                                <img loading="lazy" src="<?= $row->img ?>" alt="Copertina del libro">
                            </a>
                        </td>

                        <td><?= $row->name ?></td>

                        <td class="mb-nd"><?= $row->author ?></td>

                        <td class="mb-nd">
                            <?php  
                                if (isset($row->tag_bg, $row->tag_color, $row->tag_name)) {
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
                                <button onclick="openEditBookModal(
                                    <?= $row->id ?>,
                                    '<?= $row->img ?>',
                                    '<?= $row->name ?>',
                                    '<?= $row->author ?>',
                                    '<?= $row->date ?>',
                                    <?= $row->state ?>,
                                    '<?= $row->position ?>',
                                    <?= $row->tag_id ?>
                                )">
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

        <br><br>

        <div class="custom-table tags">
            <div class="msg-box">
                <p class="message">
                    Clicca per aggiungere un tag...
                </p>

                <button type="button" class="add" onclick="openCreateTagModal()">
                    <img src="assets/images/ui/add.svg">
                </button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Tag</th>
                        <th>Azioni</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $sql = "
                            SELECT
                                id,
                                name,
                                color,
                                background
                            FROM tags
                            ORDER BY id
                            LIMIT 100
                        ";

                        $stmt = $conn -> prepare($sql);
                        $stmt -> execute($params);

                        foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $row) {
                    ?>

                    <tr>
                        <td>
                            <div 
                                style="background: #<?= $row->background ?>; color: #<?= $row->color ?>"
                                class="genre"
                            >
                                <?= $row->name ?>
                            </div>
                        </td>

                        <td>
                            <div class="action-btns"> 
                                <button onclick="openDeleteTagModal(<?= $row->id ?>)">
                                    <img src="assets/images/ui/delete.svg">
                                </button>
                                <button onclick="openEditTagModal(
                                    <?= $row->id ?>,
                                    '<?= $row->name ?>',
                                    '#<?= $row->color ?>',
                                    '#<?= $row->background ?>'
                                )">
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

        <br><br>

        <div class="custom-table suggestion">
            <form class="search-bar">
                <div class="input">
                    <button type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                    <input name="search_sug" placeholder="Cerca per titolo, note...">
                </div>
                <select name="searchtype_sug">
                    <option value="name">Titolo</option>
                    <option value="notes">Note</option>
                    <option value="date">Data</option>
                </select>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Titolo</th>
                        <th class="mb-nd">Note</th>
                        <th class="mb-nd">Data</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $params = [];
                        $searchquery = "";

                        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                            if (isset($_GET["searchtype_sug"]) && isset($_GET["search_sug"])) {
                                switch ($_GET["searchtype_sug"]) {
                                    case "name":
                                        $searchquery = "WHERE title LIKE :search";
                                        break;
                                    case "notes":
                                        $searchquery = "WHERE notes LIKE :search";
                                        break;
                                    case "date":
                                        $searchquery = "WHERE date LIKE :search";
                                        break;
                                }

                                if ($searchquery != "")
                                    $params[':search'] = '%' . $_GET['search_sug'] . '%';
                            }
                        }

                        $sql = "
                            SELECT
                                id,
                                title,
                                notes,
                                date,
                                state
                            FROM suggestions
                            {$searchquery}
                            ORDER BY id DESC
                            LIMIT 10
                        ";

                        $stmt = $conn -> prepare($sql);
                        $stmt -> execute($params);

                        foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $row) {
                            // Skips unreviewed suggestions
                            if (in_array($row->state, [1, 2]) && (bool) !$ini["Dashboard"]["showUnreviewedSuggestion"])
                                continue;
                    ?>

                    <tr>
                        <td><b><?= $row->title ?></b></td>

                        <td class="mb-nd"><?= $row->notes ?></td>

                        <td class="mb-nd"><?= $row->date ?></td>
                    
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

                        <td>
                            <div class="action-btns"> 
                                <button onclick="openDeleteSuggModal(<?= $row->id ?>)">
                                    <img src="assets/images/ui/delete.svg">
                                </button>
                                <button onclick="openEditSuggModal(<?= $row->id ?>, <?= $row->state ?>)">
                                    <img src="assets/images/ui/edit.svg">
                                </button>
                            </div>
                        </td>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <br><br>

    </div>

    <?php
        include("components/footer.php");
    ?>
</body>
</html>