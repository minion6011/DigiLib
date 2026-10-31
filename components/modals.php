<script src="assets/js/modals.js" type="text/javascript" defer></script>
<link rel="stylesheet" href="assets/css/modals.css">

<div class="modals" id="modals-cnt">

    <div class="modal" id="delete-modal-book"> <!-- Delete Modal -->
        <form action="account.php" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_type" value="book">
    
            <div class="top">
                <div class="logo" style="background-color: #F3A5A5;">
                    <img src="components/avatar.php?color=fc3a3a" alt="Logo">
                </div>
                <div class="text">
                    <p class="title">Rimuovi un libro</p>
                    <p class="description">Cancella il libro dal database</p>
                </div>
                <p class="close" onclick="closeModal()">&#x2715;</p>
            </div>

            <div class="middle">
                <input type="hidden" id="delete-id-book" name="id" required>
                
                <div class="inputbox buttons">
                    <p>Sei sicuro di volere eliminare questo libro?</p>
                    <div class="input">
                        <button class="delete" type="submit">Elimina</button>
                        <button class="cancel" type="button" onclick="closeModal()">Annulla</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal" id="create-modal-book"> <!-- Create Modal -->
        <form action="account.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="_type" value="book">
    
            <div class="top">
                <div class="logo" style="background-color: #c7dfff;">
                    <img src="components/avatar.php?color=1172F4" alt="Logo">
                </div>
                <div class="text">
                    <p class="title">Inserisci un libro</p>
                    <p class="description">Inserisci i dati del libro per aggiungerlo nel database</p>
                </div>
                <p class="close" onclick="closeModal()">&#x2715;</p>
            </div>

            <div class="middle">
                <div class="inputbox image">
                    <p>Foto del libro</p>
                    <div class="input" id="create-img_container-book">
                        <input type="file" id="create-img_input-book" name="img" accept="image/jpeg, image/jpg, image/png, image/webp">
                        <div class="request">
                            <img src="assets/images/ui/addimage.svg" alt="Simbolo del più">
                            <p>Clicca per caricare</p>
                        </div>
                        <img class="upload" id="create-img-book" alt="Immagine caricata">
                    </div>
                </div>

                <div class="inputbox text">
                    <p>Titolo*</p>
                    <div class="input">
                        <img src="components/avatar.php?color=989898" alt="Logo">
                        <input type="text" minlength="1" placeholder="Inserire il titolo del libro" id="create-title-book" name="title" required>
                    </div>
                </div>

                <div class="inputbox text">
                    <p>Autore*</p>
                    <div class="input">
                        <img src="components/avatar.php?color=989898" alt="Logo">
                        <input type="text" minlength="1" placeholder="Inserire l'autore del libro" id="create-author-book" name="author" required>
                    </div>
                </div>

                <div class="inputbox date">
                    <p>Data di pubblicazione*</p>
                    <div class="input">
                        <i class="fa fa-calendar" id="create-date-book-ico" style="cursor: pointer"></i>
                        <input type="date" id="create-date-book" name="date" required>
                    </div>
                </div>

                <div class="inputbox select">
                    <p>Stato*</p>
                    <div class="input">
                        <i class="fa fa-check-circle-o"></i>
                        <select id="create-state-book" name="state" required>
                            <option value="" disabled selected>-- Seleziona stato --</option>
                            <option value="0">Disponibile</option>
                            <option value="1">In prestito</option>
                        </select>
                    </div>
                </div>


                <div class="inputbox text">
                    <p>Posizione</p>
                    <div class="input">
                        <img src="components/avatar.php?color=989898" alt="Logo">
                        <input type="text" minlength="1" placeholder="Inserire la posizione del libro" id="create-position-book" name="position">
                    </div>
                </div>

                <div class="inputbox select">
                    <p>Tag</p>
                    <div class="input">
                        <i class="fa fa-check-circle-o"></i>
                        <select id="create-tag-book">
                            <option value="" disabled selected>-- Seleziona tag --</option>
                            <?php
                                $sql = "SELECT id, name FROM tags";
                                $stmt = $conn -> prepare($sql);
                                $stmt -> execute();

                                foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $row) {
                            ?>
                                <option value="<?= $row->id ?>"><?= $row->name ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>

            </div>

            <div class="bottom">
                <div class="buttons">
                    <button class="confirm" type="submit">Aggiungi</button>
                    <button class="cancel" type="button" onclick="closeModal()">Annulla</button>
                </div>
            </div>
        </form>
    </div>
</div>