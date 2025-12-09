<?php
// Arquivo: views/admin/playlists/index.php
// Listagem de Playlists

// $playlists está disponível

?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de Playlists</h3>
        <div class="card-tools">
            <a href="<?= ADMIN_PATH ?>/playlists/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nova Playlist
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th style="width: 100px;">Itens</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 200px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($playlists)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Nenhuma playlist cadastrada.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($playlists as $playlist): ?>
                        <tr>
                            <td><?= $playlist['id'] ?></td>
                            <td><strong><?= htmlspecialchars($playlist['nome']) ?></strong></td>
                            <td><?= htmlspecialchars($playlist['descricao'] ?? '-') ?></td>
                            <td class="text-center">
                                <span class="badge badge-info"><?= $playlist['total_itens'] ?? 0 ?> itens</span>
                            </td>
                            <td>
                                <span class="badge badge-<?= $playlist['ativo'] ? 'success' : 'danger' ?>">
                                    <?= $playlist['ativo'] ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group-sm">
                                    <a href="<?= ADMIN_PATH ?>/playlists/manage/<?= $playlist['id'] ?>" 
                                       class="btn btn-sm btn-primary" title="Gerenciar Itens">
                                        <i class="fas fa-list"></i> Itens
                                    </a>
                                    <a href="<?= ADMIN_PATH ?>/playlists/edit/<?= $playlist['id'] ?>" 
                                       class="btn btn-sm btn-info" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?= ADMIN_PATH ?>/playlists/delete/<?= $playlist['id'] ?>" 
                                          method="POST" style="display:inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta playlist?');">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
