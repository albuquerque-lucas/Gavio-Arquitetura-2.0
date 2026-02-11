<div class="modal fade" id="bulkDeleteConfirmationModal" tabindex="-1" aria-labelledby="bulkDeleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content gavio-modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkDeleteConfirmationModalLabel">Confirmacao de Exclusao em Massa</h5>
                <button type="button" class="btn-close gavio-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir os projetos selecionados?
            </div>
            <div class="modal-footer">
                <button type="button" class="gavio-btn gavio-btn-ghost" data-bs-dismiss="modal">Cancelar</button>
                <form id="bulk-delete-form" method="POST" action="{{ route('admin.projetos.bulkDelete') }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="selected_projects" id="selected_projects">
                    <button type="submit" class="gavio-btn gavio-btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
