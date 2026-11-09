const modalsContainer = document.getElementById("modals-cnt");

const deleteSuggModal = {
    element: document.getElementById("delete-modal-sugg"),

    id: document.getElementById("delete-id-sugg"),
}
const editSuggModal = {
    element: document.getElementById("edit-modal-sugg"),

    form: document.getElementById("edit-form-sugg"),

    id: document.getElementById("edit-id-sugg"),
    state: document.getElementById("edit-state-sugg"),
}

const deleteTagModal = {
    element: document.getElementById("delete-modal-tag"),

    id: document.getElementById("delete-id-tag"),
}
const createTagModal = {
    form: document.getElementById("create-form-tag"),
    element: document.getElementById("create-modal-tag"),
}
const editTagModal = {
    form: document.getElementById("edit-form-tag"),
    element: document.getElementById("edit-modal-tag"),

    id: document.getElementById("edit-id-tag"),

    name: document.getElementById("edit-name-tag"),
    color: document.getElementById("edit-color-tag"),
    background: document.getElementById("edit-background-tag"),
}

const deleteBookModal = {
    element: document.getElementById("delete-modal-book"),

    id: document.getElementById("delete-id-book"),
}
const createBookModal = {
    form: document.getElementById("create-form-book"),
    element: document.getElementById("create-modal-book"),

    img_container: document.getElementById("create-img_container-book"),
    img_input: document.getElementById("create-img_input-book"),
    img: document.getElementById("create-img-book"),

    date_ico: document.getElementById("create-date-book-ico"),
    date: document.getElementById("create-date-book"),
}
const editBookModal = {
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

// - Suggestions

/**
 * Opens the edit suggestion modal
 * @param {int} id Suggestion Id
 * @param {int} state Suggestion State
 */
function openEditSuggModal(id, state) {
    editSuggModal.form.reset();
    openModalDefault(editSuggModal.element);
    editSuggModal.id.value = id;
    if (editSuggModal.state.contains(state))
        editSuggModal.state.value = state;
}

/**
 * Opens the delete suggestion modal
 * @param {int} id Suggestion Id
 */
function openDeleteSuggModal(id) {
    openModalDefault(deleteSuggModal.element);
    deleteSuggModal.id.value = id;
}

// - Tags

/**
 * Opens the edit tag modal
 * @param {int} id Tag id
 * @param {string} name Tag name
 * @param {string} color Tag text color (Hex)
 * @param {string} background Tag background color (Hex)
 */
function openEditTagModal(id, name, color, background) {
    //editTagModal.form.reset(); // Non necessario, tutti gli elementi vengono sovrascritti
    openModalDefault(editTagModal.element);
    editTagModal.id.value = id;
    editTagModal.name.value = name;
    editTagModal.color.value = color;
    editTagModal.background.value = background;
}

/**
 * Opens the create tag modal
 */
function openCreateTagModal() {
    createTagModal.form.reset();
    openModalDefault(createTagModal.element);
}

/**
 * Opens the delete tag modal
 * @param {int} id Tag Id
 */
function openDeleteTagModal(id) {
    openModalDefault(deleteTagModal.element);
    deleteTagModal.id.value = id;
}

// - Books

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
    editBookModal.form.reset();
    openModalDefault(editBookModal.element);
    // Set values
    editBookModal.id.value = id;
    editBookModal.title.value = title;
    editBookModal.author.value = author;
    editBookModal.date.value = date;
    if (editBookModal.state.contains(state))
        editBookModal.state.value = state;
    if (position != "//") // "//" è il valore di default
        editBookModal.position.value = position;
    if (editBookModal.tag.contains(tag))
        editBookModal.tag.value = tag;
    // Clears Image Input && Sets Old Image Url
    editBookModal.img.src = img;
    editBookModal.img_input.value = "";
}

/**
 * Opens the create book modal
 */
function openCreateBookModal() {
    createBookModal.form.reset();
    openModalDefault(createBookModal.element);
    // Clears Image Input
    createBookModal.img.src = "";
    createBookModal.img_input.value = "";
}

/**
 * Opens the delete book modal
 * @param {int} id Book Id
 */
function openDeleteBookModal(id) {
    openModalDefault(deleteBookModal.element);
    deleteBookModal.id.value = id;
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
createBookModal.img_container.addEventListener("click", () => {
    createBookModal.img_input.click();
});
createBookModal.img_input.addEventListener("change", () => {
    const file = createBookModal.img_input.files[0];
    if (file == undefined)
        return

    const reader = new FileReader();
    reader.onload = (e) => {
        createBookModal.img.src = e.target.result;
    }
    reader.readAsDataURL(file);
});

createBookModal.date_ico.addEventListener("click", () => {
    createBookModal.date.showPicker();
})

// Edit Modal
editBookModal.img_container.addEventListener("click", () => {
    editBookModal.img_input.click();
});
editBookModal.img_input.addEventListener("change", () => {
    const file = editBookModal.img_input.files[0];
    if (file == undefined)
        return

    const reader = new FileReader();
    reader.onload = (e) => {
        editBookModal.img.src = e.target.result;
    }
    reader.readAsDataURL(file);
});

editBookModal.date_ico.addEventListener("click", () => {
    editBookModal.date.showPicker();
})