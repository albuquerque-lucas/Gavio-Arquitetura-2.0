// resources/js/deleteConfirmation.js

import Modal from 'bootstrap/js/dist/modal';

document.addEventListener('DOMContentLoaded', function () {
    let deleteButtons = document.querySelectorAll('.delete-button');
    let deleteConfirmationModal = new Modal(document.getElementById('deleteConfirmationModal'));
    let form = document.getElementById('delete-form');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            let projectId = this.dataset.projectId;
            form.action = '/admin/projetos/' + projectId;
        });
    });
});
