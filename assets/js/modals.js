const modalsContainer = document.getElementById("modals-cnt");

const deleteModal = {
    element: document.getElementById("delete-modal-book"),

    id: document.getElementById("delete-id-book")
}
let currentModal = null;

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