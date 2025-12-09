<?php
// Arquivo: views/admin/playlists/manage.php
// Gerenciamento de Itens da Playlist

// $playlist, $playlist_itens, $conteudos_disponiveis estão disponíveis
?>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Itens da Playlist: <?= htmlspecialchars($playlist['nome']) ?></h3>
            </div>
            <div class="card-body">
                <?php if (empty($playlist_itens)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Esta playlist ainda não possui itens. 
                        Adicione conteúdos usando o painel ao lado.
                    </div>
                <?php else: ?>
                    <p class="text-muted">
                        <i class="fas fa-arrows-alt"></i> Arraste os itens para reordenar
                    </p>
                    <div id="sortableList">
                        <?php foreach ($playlist_itens as $item): ?>
                            <div class="card mb-2 sortable-item" data-id="<?= $item['id'] ?>" data-ordem="<?= $item['ordem'] ?>">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <i class="fas fa-grip-vertical text-muted" style="cursor: move;"></i>
                                        </div>
                                        <div class="col-auto">
                                            <strong>#<?= $item['ordem'] ?></strong>
                                        </div>
                                        <div class="col">
                                            <div>
                                                <span class="badge badge-<?= 
                                                    $item['tipo'] === 'imagem' ? 'primary' : 
                                                    ($item['tipo'] === 'video' ? 'success' : 
                                                    ($item['tipo'] === 'texto' ? 'info' : 'warning')) 
                                                ?>">
                                                    <?= ucfirst($item['tipo']) ?>
                                                </span>
                                                <strong><?= htmlspecialchars($item['nome']) ?></strong>
                                            </div>
                                            <small class="text-muted">
                                                Duração: <?= $item['duracao'] ?? $item['conteudo_duracao'] ?>s
                                            </small>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-sm btn-info" 
                                                    onclick="editDuracao(<?= $item['id'] ?>, <?= $item['duracao'] ?? $item['conteudo_duracao'] ?>)">
                                                <i class="fas fa-clock"></i>
                                            </button>
                                            <form action="<?= ADMIN_PATH ?>/playlists/remove-item/<?= $playlist['id'] ?>" 
                                                  method="POST" style="display:inline;">
                                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Remover este item da playlist?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <a href="<?= ADMIN_PATH ?>/playlists" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Adicionar Conteúdo</h3>
            </div>
            <form action="<?= ADMIN_PATH ?>/playlists/add-item/<?= $playlist['id'] ?>" method="POST">
                <div class="card-body">
                    <div class="form-group">
                        <label for="conteudo_id" class="required">Selecione o Conteúdo</label>
                        <select class="form-control" id="conteudo_id" name="conteudo_id" required>
                            <option value="">-- Selecione --</option>
                            <?php foreach ($conteudos_disponiveis as $conteudo): ?>
                                <option value="<?= $conteudo['id'] ?>" data-duracao="<?= $conteudo['duracao'] ?>">
                                    [<?= ucfirst($conteudo['tipo']) ?>] <?= htmlspecialchars($conteudo['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="duracao">Duração (segundos)</label>
                        <input type="number" class="form-control" id="duracao" name="duracao" 
                               min="1" max="300" placeholder="Usar duração padrão do conteúdo">
                        <small class="form-text text-muted">
                            Deixe em branco para usar a duração padrão do conteúdo.
                        </small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-plus"></i> Adicionar à Playlist
                    </button>
                </div>
            </form>
        </div>

        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Informações</h3>
            </div>
            <div class="card-body">
                <p><strong>Total de itens:</strong> <?= count($playlist_itens) ?></p>
                <p><strong>Duração total:</strong> 
                    <?php 
                    $total_duracao = 0;
                    foreach ($playlist_itens as $item) {
                        $total_duracao += $item['duracao'] ?? $item['conteudo_duracao'];
                    }
                    echo gmdate("H:i:s", $total_duracao);
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar duração -->
<div class="modal fade" id="modalDuracao" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= ADMIN_PATH ?>/playlists/update-duracao/<?= $playlist['id'] ?>" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Alterar Duração</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal_item_id" name="item_id">
                    <div class="form-group">
                        <label for="modal_duracao">Duração (segundos)</label>
                        <input type="number" class="form-control" id="modal_duracao" name="duracao" 
                               min="1" max="300" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-preencher duração ao selecionar conteúdo
    const conteudoSelect = document.getElementById('conteudo_id');
    const duracaoInput = document.getElementById('duracao');
    
    conteudoSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const duracao = selectedOption.getAttribute('data-duracao');
        if (duracao) {
            duracaoInput.placeholder = `Padrão: ${duracao}s`;
        }
    });
    
    // Sortable para reordenar itens
    const sortableList = document.getElementById('sortableList');
    if (sortableList) {
        Sortable.create(sortableList, {
            animation: 150,
            handle: '.fa-grip-vertical',
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                // Coletar nova ordem
                const items = [];
                document.querySelectorAll('.sortable-item').forEach((item, index) => {
                    items.push({
                        id: item.getAttribute('data-id'),
                        ordem: index + 1
                    });
                });
                
                // Enviar para o servidor
                fetch('<?= ADMIN_PATH ?>/playlists/reorder/<?= $playlist['id'] ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ items: items })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualizar números de ordem na tela
                        document.querySelectorAll('.sortable-item').forEach((item, index) => {
                            item.querySelector('strong').textContent = '#' + (index + 1);
                            item.setAttribute('data-ordem', index + 1);
                        });
                    }
                });
            }
        });
    }
});

// Função para abrir modal de edição de duração
function editDuracao(itemId, duracaoAtual) {
    document.getElementById('modal_item_id').value = itemId;
    document.getElementById('modal_duracao').value = duracaoAtual;
    $('#modalDuracao').modal('show');
}
</script>
