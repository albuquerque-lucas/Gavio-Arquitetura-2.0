<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Confirmação de Exclusão</h5>
                <button type="button" class="btn-close btn-primary" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir esta categoria?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="delete-category-form" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let deleteCategoryModal = document.getElementById('deleteCategoryModal');
        deleteCategoryModal.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget;
            let categoryId = button.getAttribute('data-category-id');
            let form = deleteCategoryModal.querySelector('#delete-category-form');
            form.action = `/admin/categories/${categoryId}`;
        });
    });
</script>
