<?php
// Arquivo: views/admin/conteudos/index.php
// Listagem de Conteúdos

// $conteudos está disponível

?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de Conteúdos</h3>
        <div class="card-tools">
            <a href="<?= ADMIN_PATH ?>/conteudos/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Novo Conteúdo
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Conteúdo</th>
                    <th>Duração (s)</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($conteudos)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Nenhum conteúdo cadastrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($conteudos as $conteudo): ?>
                        <tr>
                            <td><?= $conteudo['id'] ?></td>
                            <td><?= ucfirst($conteudo['tipo']) ?></td>
                            <td>
                                <?php if ($conteudo['tipo'] === 'texto'): ?>
                                    <?= htmlspecialchars(substr($conteudo['texto'], 0, 50)) ?>...
                                <?php elseif ($conteudo['tipo'] === 'link'): ?>
                                    <a href="<?= htmlspecialchars($conteudo['arquivo_url']) ?>" target="_blank">Link Externo</a>
                                <?php else: ?>
                                    <a href="<?= get_upload_url($conteudo['arquivo_url']) ?>" target="_blank">
                                        <?= htmlspecialchars($conteudo['arquivo_url']) ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td><?= $conteudo['duracao'] ?></td>
                            <td>
                                <span class="badge badge-<?= $conteudo['ativo'] ? 'success' : 'danger' ?>">
                                    <?= $conteudo['ativo'] ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= ADMIN_PATH ?>/conteudos/edit/<?= $conteudo['id'] ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?= ADMIN_PATH ?>/conteudos/delete/<?= $conteudo['id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este conteúdo?');">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
