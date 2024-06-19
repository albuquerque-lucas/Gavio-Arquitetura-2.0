// resources/js/toggleCarousel.js

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-carousel').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            let projectId = this.dataset.projectId;
            let url = `/admin/projetos/${projectId}/toggleCarousel`;

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let icon = this.querySelector('i');
                    if (data.status) {
                        icon.classList.remove('fa-regular');
                        icon.classList.add('fa-solid');
                    } else {
                        icon.classList.remove('fa-solid');
                        icon.classList.add('fa-regular');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
