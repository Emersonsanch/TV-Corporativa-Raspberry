<?php
// Arquivo: views/admin/playlists/edit.php
// Formulário de Edição de Playlist

// $playlist está disponível
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Editar Playlist #<?= $playlist['id'] ?></h3>
            </div>
            <form action="<?= ADMIN_PATH ?>/playlists/edit/<?= $playlist['id'] ?>" method="POST">
                <div class="card-body">
                    <!-- Nome -->
                    <div class="form-group">
                        <label for="nome" class="required">Nome da Playlist</label>
                        <input type="text" class="form-control" id="nome" name="nome" required 
                               value="<?= htmlspecialchars($playlist['nome']) ?>">
                    </div>

                    <!-- Descrição -->
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($playlist['descricao'] ?? '') ?></textarea>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="ativo" name="ativo" value="1" 
                                   <?= $playlist['ativo'] ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="ativo">Playlist Ativa</label>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                    <a href="<?= ADMIN_PATH ?>/playlists" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <a href="<?= ADMIN_PATH ?>/playlists/manage/<?= $playlist['id'] ?>" class="btn btn-primary float-right">
                        <i class="fas fa-list"></i> Gerenciar Itens
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Informações</h3>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> <?= $playlist['id'] ?></p>
                <p><strong>Criado em:</strong> <?= date('d/m/Y H:i', strtotime($playlist['created_at'])) ?></p>
                <p><strong>Status:</strong> 
                    <span class="badge badge-<?= $playlist['ativo'] ? 'success' : 'danger' ?>">
                        <?= $playlist['ativo'] ? 'Ativo' : 'Inativo' ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
