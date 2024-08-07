document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.image-checkbox');
    const deleteSelectedButton = document.getElementById('deleteSelected');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
    let selectedImages = [];

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

    function toggleDeleteButton() {
        const anyChecked = selectedImages.length > 0;
        deleteSelectedButton.disabled = !anyChecked;
    }

    deleteSelectedButton.addEventListener('click', function () {
        bulkDeleteForm.submit();
    });
});
