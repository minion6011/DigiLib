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
        <form action="account.php" method="POST" enctype="multipart/form-data" id="create-form-book">
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
                        <input type="text" minlength="1" placeholder="Inserire il titolo del libro" name="title" required>
                    </div>
                </div>

                <div class="inputbox text">
                    <p>Autore*</p>
                    <div class="input">
                        <img src="components/avatar.php?color=989898" alt="Logo">
                        <input type="text" minlength="1" placeholder="Inserire l'autore del libro" name="author" required>
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
                        <select name="state" required>
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
                        <input type="text" minlength="1" placeholder="Inserire la posizione del libro" name="position">
                    </div>
                </div>

                <div class="inputbox select">
                    <p>Tag</p>
                    <div class="input">
                        <i class="fa fa-check-circle-o"></i>
                        <select name="tag_id">
                            <option value="" disabled selected>-- Seleziona tag --</option>
                            <option value="">Nessuno</option>
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

    <div class="modal" id="edit-modal-book"> <!-- Edit Modal -->
        <form action="account.php" method="POST" enctype="multipart/form-data" id="edit-form-book">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="_type" value="book">
    
            <div class="top">
                <div class="logo" style="background-color: #fff1c7;">
                    <img src="components/avatar.php?color=F4B711" alt="Logo">
                </div>
                <div class="text">
                    <p class="title">Modifica un libro</p>
                    <p class="description">Aggiorna i dati del libro per aggiornali nel database</p>
                </div>
                <p class="close" onclick="closeModal()">&#x2715;</p>
            </div>

            <div class="middle">
                <input type="hidden" id="edit-id-book" name="id" required>

                <div class="inputbox image">
                    <p>Foto del libro</p>
                    <div class="input" id="edit-img_container-book">
                        <input type="file" id="edit-img_input-book" name="img" accept="image/jpeg, image/jpg, image/png, image/webp">
                        <div class="request">
                            <img src="assets/images/ui/addimage.svg" alt="Simbolo del più">
                            <p>Clicca per caricare</p>
                        </div>
                        <img class="upload" id="edit-img-book" alt="Immagine caricata">
                    </div>
                </div>

                <div class="inputbox text">
                    <p>Titolo*</p>
                    <div class="input">
                        <img src="components/avatar.php?color=989898" alt="Logo">
                        <input type="text" minlength="1" placeholder="Inserire il titolo del libro" id="edit-title-book" name="title" required>
                    </div>
                </div>

                <div class="inputbox text">
                    <p>Autore*</p>
                    <div class="input">
                        <img src="components/avatar.php?color=989898" alt="Logo">
                        <input type="text" minlength="1" placeholder="Inserire l'autore del libro" id="edit-author-book" name="author" required>
                    </div>
                </div>

                <div class="inputbox date">
                    <p>Data di pubblicazione*</p>
                    <div class="input">
                        <i class="fa fa-calendar" id="edit-date-book-ico" style="cursor: pointer"></i>
                        <input type="date" id="edit-date-book" name="date" required>
                    </div>
                </div>

                <div class="inputbox select">
                    <p>Stato*</p>
                    <div class="input">
                        <i class="fa fa-check-circle-o"></i>
                        <select id="edit-state-book" name="state" required>
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
                        <input type="text" minlength="1" placeholder="Inserire la posizione del libro" id="edit-position-book" name="position">
                    </div>
                </div>

                <div class="inputbox select">
                    <p>Tag</p>
                    <div class="input">
                        <i class="fa fa-check-circle-o"></i>
                        <select id="edit-tag-book" name="tag_id">
                            <option value="" disabled selected>-- Seleziona tag --</option>
                            <option value="">Nessuno</option>
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
                    <button class="confirm" type="submit">Modifica</button>
                    <button class="cancel" type="button" onclick="closeModal()">Annulla</button>
                </div>
            </div>
        </form>
    </div>


    <!-- Tags -->

    <div class="modal" id="delete-modal-tag"> <!-- Delete Modal -->
        <form action="account.php" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_type" value="tag">
    
            <div class="top">
                <div class="logo" style="background-color: #F3A5A5;">
                    <img src="components/avatar.php?color=fc3a3a" alt="Logo">
                </div>
                <div class="text">
                    <p class="title">Rimuovi un Tag</p>
                    <p class="description">Cancella tag e rimuovilo dai libri associati</p>
                </div>
                <p class="close" onclick="closeModal()">&#x2715;</p>
            </div>

            <div class="middle">
                <input type="hidden" id="delete-id-tag" name="id" required>
                
                <div class="inputbox buttons">
                    <p>Sei sicuro di volere eliminare questo tag?</p>
                    <div class="input">
                        <button class="delete" type="submit">Elimina</button>
                        <button class="cancel" type="button" onclick="closeModal()">Annulla</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal" id="create-modal-tag"> <!-- Create Modal -->
        <form action="account.php" method="POST" id="create-form-tag">
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="_type" value="tag">
    
            <div class="top">
                <div class="logo" style="background-color: #c7dfff;">
                    <img src="components/avatar.php?color=1172F4" alt="Logo">
                </div>
                <div class="text">
                    <p class="title">Crea un tag</p>
                    <p class="description">Inserisci le informazioni per creare un tag</p>
                </div>
                <p class="close" onclick="closeModal()">&#x2715;</p>
            </div>

            <div class="middle">
                <div class="inputbox text">
                    <p>Nome Tag*</p>
                    <div class="input">
                        <img src="components/avatar.php?color=989898" alt="Logo">
                        <input type="text" minlength="1" placeholder="Inserire il nome del tag" name="name" required>
                    </div>
                </div>
                <div class="inputbox multiple">
                    <p>Colori Tag*</p>
                    <div class="inputs">
                        <div class="inputbox color">
                            <p>Colore Testo</p>
                            <div class="input">
                                <input type="color" value="#1673fe" name="color" required>
                            </div>
                        </div>

                        <div class="inputbox color">
                            <p>Colore Sfondo</p>
                            <div class="input">
                                <input type="color" value="#eaf4fd" name="background" required>
                            </div>
                        </div>
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

    <div class="modal" id="edit-modal-tag"> <!-- Edit Modal -->
        <form action="account.php" method="POST" id="edit-form-tag">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="_type" value="tag">
    
            <div class="top">
                <div class="logo" style="background-color: #fff1c7;">
                    <img src="components/avatar.php?color=F4B711" alt="Logo">
                </div>
                <div class="text">
                    <p class="title">Modifica un tag</p>
                    <p class="description">Modifica le informazioni di un tag</p>
                </div>
                <p class="close" onclick="closeModal()">&#x2715;</p>
            </div>

            <div class="middle">
                <input type="hidden" id="edit-id-tag" name="id" required>
                
                <div class="inputbox text">
                    <p>Nome Tag*</p>
                    <div class="input">
                        <img src="components/avatar.php?color=989898" alt="Logo">
                        <input type="text" minlength="1" placeholder="Inserire il nome del tag" id="edit-name-tag" name="name" required>
                    </div>
                </div>
                <div class="inputbox multiple">
                    <p>Colori Tag*</p>
                    <div class="inputs">
                        <div class="inputbox color">
                            <p>Colore Testo</p>
                            <div class="input">
                                <input type="color" value="#1673fe" name="color" id="edit-color-tag" required>
                            </div>
                        </div>

                        <div class="inputbox color">
                            <p>Colore Sfondo</p>
                            <div class="input">
                                <input type="color" value="#eaf4fd" name="background" id="edit-background-tag" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bottom">
                <div class="buttons">
                    <button class="confirm" type="submit">Modifica</button>
                    <button class="cancel" type="button" onclick="closeModal()">Annulla</button>
                </div>
            </div>
        </form>
    </div>


    <!-- Suggestion --> 
    <div class="modal" id="delete-modal-sugg"> <!-- Delete Modal -->
        <form action="account.php" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_type" value="suggestion">
    
            <div class="top">
                <div class="logo" style="background-color: #F3A5A5;">
                    <img src="components/avatar.php?color=fc3a3a" alt="Logo">
                </div>
                <div class="text">
                    <p class="title">Rimuovi un suggerimento</p>
                    <p class="description">Cancella un suggerimento</p>
                </div>
                <p class="close" onclick="closeModal()">&#x2715;</p>
            </div>

            <div class="middle">
                <input type="hidden" id="delete-id-sugg" name="id" required>
                
                <div class="inputbox buttons">
                    <p>Sei sicuro di volere eliminare questo suggerimento?</p>
                    <div class="input">
                        <button class="delete" type="submit">Elimina</button>
                        <button class="cancel" type="button" onclick="closeModal()">Annulla</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal" id="edit-modal-sugg"> <!-- Edit Modal -->
        <form action="account.php" method="POST" id="edit-form-sugg">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="_type" value="suggestion">
    
            <div class="top">
                <div class="logo" style="background-color: #fff1c7;">
                    <img src="components/avatar.php?color=F4B711" alt="Logo">
                </div>
                <div class="text">
                    <p class="title">Revisiona un suggerimento</p>
                    <p class="description">Modifica lo stato di un suggerimento</p>
                </div>
                <p class="close" onclick="closeModal()">&#x2715;</p>
            </div>

            <div class="middle">
                <input type="hidden" id="edit-id-sugg" name="id" required>

                <div class="inputbox select">
                    <p>Stato*</p>
                    <div class="input">
                        <i class="fa fa-check-circle-o"></i>
                        <select name="state" required id="edit-state-sugg">
                            <option value="" disabled selected>-- Seleziona stato --</option>
                            <option value="0">In attesa</option>
                            <option value="1">Approvato</option>
                            <option value="2">Rifiutato</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bottom">
                <div class="buttons">
                    <button class="confirm" type="submit">Modifica</button>
                    <button class="cancel" type="button" onclick="closeModal()">Annulla</button>
                </div>
            </div>
        </form>
    </div>
</div>