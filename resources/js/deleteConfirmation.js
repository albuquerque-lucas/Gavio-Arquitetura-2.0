// resources/js/deleteConfirmation.js

import Modal from 'bootstrap/js/dist/modal';

document.addEventListener('DOMContentLoaded', function () {
    let deleteButtons = document.querySelectorAll('.delete-button');
    let confirmDeleteButton = document.getElementById('confirmDeleteButton');
    console.log('deleteConfirmation.js loaded');
    let deleteConfirmationModal = new Modal(document.getElementById('deleteConfirmationModal'));
    let projectIdToDelete;

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            console.log(`Botão de exclusão clicado: ${this.dataset.projectId}`);
            projectIdToDelete = this.dataset.projectId;
            deleteConfirmationModal.show();
        });
    });

    confirmDeleteButton.addEventListener('click', function () {
        console.log(`Projeto a ser excluído: ${projectIdToDelete}`);
        deleteConfirmationModal.hide();
        // Aqui você pode fazer uma requisição AJAX para excluir o projeto no backend
    });
});
