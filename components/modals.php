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
</div>