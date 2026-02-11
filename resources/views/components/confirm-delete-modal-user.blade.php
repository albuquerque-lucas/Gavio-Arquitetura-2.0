<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content gavio-modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirmacao de Exclusao</h5>
                <button type="button" class="btn-close gavio-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir este usuario?
            </div>
            <div class="modal-footer">
                <button type="button" class="gavio-btn gavio-btn-ghost" data-bs-dismiss="modal">Cancelar</button>
                <form id="delete-user-form" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="gavio-btn gavio-btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
