import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.image-checkbox');
    const deleteSelectedButton = document.getElementById('deleteSelected');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
    let selectedImages = [];

    // Manipulador para selecionar/desmarcar todos os checkboxes
    selectAll.addEventListener('change', function () {
        selectedImages = [];
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
            if (selectAll.checked) {
                selectedImages.push(checkbox.value);
            }
        });
        toggleDeleteButton();
    });

    // Manipulador para cada checkbox individual
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                selectedImages.push(checkbox.value);
            } else {
                selectedImages = selectedImages.filter(id => id !== checkbox.value);
            }
            toggleDeleteButton();
        });
    });

    // Função para habilitar/desabilitar o botão de deletar
    function toggleDeleteButton() {
        const anyChecked = selectedImages.length > 0;
        deleteSelectedButton.disabled = !anyChecked;
    }

    // Manipulador para o botão de deletar selecionados
    deleteSelectedButton.addEventListener('click', function (event) {
        event.preventDefault();  // Previne o envio padrão do formulário

        // Desabilitar o botão e mudar o texto para "Excluindo..."
        deleteSelectedButton.disabled = true;
        deleteSelectedButton.innerHTML = 'Excluindo... <i class="fas fa-spinner fa-spin"></i>';

        const formData = new FormData(bulkDeleteForm);
        const xhr = new XMLHttpRequest();

        xhr.open('POST', bulkDeleteForm.action, true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Imagens excluídas!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro ao excluir imagens',
                    text: 'Tente novamente.',
                });
                deleteSelectedButton.disabled = false;
                deleteSelectedButton.innerHTML = 'Excluir Selecionados';
            }
        };

        xhr.onerror = function() {
            Swal.fire({
                icon: 'error',
                title: 'Erro no servidor',
                text: 'Tente novamente.',
            });
            deleteSelectedButton.disabled = false;
            deleteSelectedButton.innerHTML = 'Excluir Selecionados';
        };

        xhr.send(formData);
    });
});
