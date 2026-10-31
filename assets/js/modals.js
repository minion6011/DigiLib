const modalsContainer = document.getElementById("modals-cnt");

const deleteModal = {
    element: document.getElementById("delete-modal-book"),

    id: document.getElementById("delete-id-book"),
}

const createModal = {
    element: document.getElementById("create-modal-book"),

    img_container: document.getElementById("create-img_container-book"),
    img_input: document.getElementById("create-img_input-book"),
    img: document.getElementById("create-img-book"),

    title: document.getElementById("create-title-book"),

    date_ico: document.getElementById("create-date-book-ico"),
    date: document.getElementById("create-date-book"),

    state: document.getElementById("create-state-book"),

    position: document.getElementById("create-position-book"),

    tag: document.getElementById("create-tag-book"),
}

let currentModal = null;

/**
 * Opens the create book modal
 * @param {int} id Book Id
 */
function openCreateBookModal() {
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