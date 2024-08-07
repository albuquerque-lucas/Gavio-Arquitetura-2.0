document.addEventListener('DOMContentLoaded', function() {
    function updateCharacterCountEdit() {
        const descriptionEdit = document.getElementById('description');
        const charCountEdit = document.getElementById('charCountEdit');
        charCountEdit.textContent = `${descriptionEdit.value.length} caracteres`;
    }

    updateCharacterCountEdit();

    const descriptionEdit = document.getElementById('description');
    descriptionEdit.addEventListener('input', updateCharacterCountEdit);
});
