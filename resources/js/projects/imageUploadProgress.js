import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('imageUploadForm');
    const uploadButton = document.getElementById('uploadButton');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadPercentage = document.getElementById('uploadPercentage');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        // Desabilitar o botão e mudar o texto para "Aguarde..."
        uploadButton.disabled = true;
        uploadButton.innerHTML = `Aguarde... <i class="fa-solid fa-spinner fa-spin"></i>`;

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();

        xhr.open('POST', form.action, true);

        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                uploadProgress.value = percentComplete;
                uploadPercentage.textContent = `${Math.round(percentComplete)}%`;
            }
        });

        xhr.onload = function() {
            if (xhr.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Upload completo!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro no upload',
                    text: 'Tente novamente.',
                });
                // Habilitar o botão e resetar o texto
                uploadButton.disabled = false;
                uploadButton.textContent = 'Adicionar Imagens';
            }
        };

        xhr.onerror = function() {
            Swal.fire({
                icon: 'error',
                title: 'Erro no upload',
                text: 'Tente novamente.',
            });
            // Habilitar o botão e resetar o texto
            uploadButton.disabled = false;
            uploadButton.textContent = 'Adicionar Imagens';
        };

        xhr.send(formData);
    });
});
