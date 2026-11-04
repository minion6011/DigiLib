const modalsContainer = document.getElementById("modals-cnt");

const deleteModal = {
    element: document.getElementById("delete-modal-book"),

    id: document.getElementById("delete-id-book"),
}

const createModal = {
    form: document.getElementById("create-form-book"),
    element: document.getElementById("create-modal-book"),

    img_container: document.getElementById("create-img_container-book"),
    img_input: document.getElementById("create-img_input-book"),
    img: document.getElementById("create-img-book"),

    date_ico: document.getElementById("create-date-book-ico"),
    date: document.getElementById("create-date-book"),
}

const editModal = {
    form: document.getElementById("edit-form-book"),
    element: document.getElementById("edit-modal-book"),

    id: document.getElementById("edit-id-book"),

    img_container: document.getElementById("edit-img_container-book"),
    img_input: document.getElementById("edit-img_input-book"),
    img: document.getElementById("edit-img-book"),

    title: document.getElementById("edit-title-book"),

    author: document.getElementById("edit-author-book"),

    date_ico: document.getElementById("edit-date-book-ico"),
    date: document.getElementById("edit-date-book"),

    state: document.getElementById("edit-state-book"),

    position: document.getElementById("edit-position-book"),

    tag: document.getElementById("edit-tag-book"),
}

// Source - https://stackoverflow.com/a/38225299
// Posted by Kelderic, modified by community. See post 'Timeline' for change history
// Retrieved 2026-11-04, License - CC BY-SA 3.0
HTMLSelectElement.prototype.contains = function( value ) {
    for (var i = 0, l = this.options.length; i < l; i++) {
        if (this.options[i].value == value) {
            return true;
        }
    }
    return false;
}


let currentModal = null;


/**
 * Opens the create book modal
 * @param {int} id Book Id
 * @param {string} img Book Image Url
 * @param {string} title Book Title
 * @param {string} author Book Author
 * @param {string} date Book Date
 * @param {int} state
 * @param {string} position
 * @param {int} tag
 */
function openEditBookModal(id, img, title, author, date, state, position, tag) {
    editModal.form.reset();
    openModalDefault(editModal.element);
    // Set values
    editModal.id.value = id;
    editModal.title.value = title;
    editModal.author.value = author;
    editModal.date.value = date;
    if (editModal.state.contains(state))
        editModal.state.value = state;
    if (position != "//") // "//" è il valore di default
        editModal.position.value = position;
    if (editModal.tag.contains(tag))
        editModal.tag.value = tag;
    // Clears Image Input && Sets Old Image Url
    editModal.img.src = img;
    editModal.img_input.value = "";
}


/**
 * Opens the create book modal
 */
function openCreateBookModal() {
    createModal.form.reset();
    openModalDefault(createModal.element);
    // Clears Image Input
    createModal.img.src = "";
    createModal.img_input.value = "";
}


/**
 * Opens the delete book modal
 * @param {int} id Book Id
 */
function openDeleteBookModal(id) {
    openModalDefault(deleteModal.element);
    deleteModal.id.value = id;
}


/**
 * Default Function that NEEDS to be executed after opening a modal
 * @param {HTMLElement} modalElement 
 */
function openModalDefault(modalElement) {
    modalsContainer.classList.add("open");
    modalElement.classList.add("open");

    currentModal = modalElement;
}

/** 
 * Close any open modal
*/
function closeModal() {
    modalsContainer.classList.remove("open")

    if (currentModal) {
        currentModal.classList.remove("open")
        currentModal = null;
    }
}


// Events
// Create Modal
createModal.img_container.addEventListener("click", () => {
    createModal.img_input.click();
});
createModal.img_input.addEventListener("change", () => {
    const file = createModal.img_input.files[0];
    if (file == undefined)
        return

    const reader = new FileReader();
    reader.onload = (e) => {
        createModal.img.src = e.target.result;
    }
    reader.readAsDataURL(file);
});

createModal.date_ico.addEventListener("click", () => {
    createModal.date.showPicker();
})

// Edit Modal
editModal.img_container.addEventListener("click", () => {
    editModal.img_input.click();
});
editModal.img_input.addEventListener("change", () => {
    const file = editModal.img_input.files[0];
    if (file == undefined)
        return

    const reader = new FileReader();
    reader.onload = (e) => {
        editModal.img.src = e.target.result;
    }
    reader.readAsDataURL(file);
});

editModal.date_ico.addEventListener("click", () => {
    editModal.date.showPicker();
})