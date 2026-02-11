<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content gavio-modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Confirmacao de Exclusao</h5>
                <button type="button" class="btn-close gavio-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir esta categoria?
            </div>
            <div class="modal-footer">
                <button type="button" class="gavio-btn gavio-btn-ghost" data-bs-dismiss="modal">Cancelar</button>
                <form id="delete-category-form" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="gavio-btn gavio-btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteCategoryModal = document.getElementById('deleteCategoryModal');

        deleteCategoryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const categoryId = button.getAttribute('data-category-id');
            const form = deleteCategoryModal.querySelector('#delete-category-form');
            form.action = `/admin/categories/${categoryId}`;
        });
    });
</script>
