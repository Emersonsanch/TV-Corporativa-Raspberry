<?php
// Arquivo: views/admin/tvs/edit.php
// Formulário de Edição de TV

// $tv e $playlists estão disponíveis
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Editar TV #<?= $tv['id'] ?></h3>
            </div>
            <form action="<?= ADMIN_PATH ?>/tvs/edit/<?= $tv['id'] ?>" method="POST">
                <div class="card-body">
                    <!-- Nome -->
                    <div class="form-group">
                        <label for="nome" class="required">Nome da TV</label>
                        <input type="text" class="form-control" id="nome" name="nome" required 
                               value="<?= htmlspecialchars($tv['nome']) ?>">
                    </div>

                    <!-- Playlist -->
                    <div class="form-group">
                        <label for="playlist_id">Playlist Associada</label>
                        <select class="form-control" id="playlist_id" name="playlist_id">
                            <option value="">-- Nenhuma --</option>
                            <?php foreach ($playlists as $playlist): ?>
                                <?php if ($playlist['ativo']): ?>
                                    <option value="<?= $playlist['id'] ?>" 
                                            <?= $tv['playlist_id'] == $playlist['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($playlist['nome']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Token -->
                    <div class="form-group">
                        <label>Token de Acesso</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="<?= htmlspecialchars($tv['token']) ?>" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-secondary" 
                                        onclick="alert('Para alterar o token, delete e crie uma nova TV.')">
                                    <i class="fas fa-lock"></i> Bloqueado
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            O token não pode ser alterado após a criação por motivos de segurança.
                        </small>
                    </div>

                    <!-- URL de Acesso -->
                    <div class="form-group">
                        <label>URL do Player</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="playerUrl" 
                                   value="<?= APP_URL ?>/tv/<?= $tv['token'] ?>" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-info" onclick="copiarUrl()">
                                    <i class="fas fa-copy"></i> Copiar
                                </button>
                                <a href="<?= APP_URL ?>/tv/<?= $tv['token'] ?>" target="_blank" class="btn btn-success">
                                    <i class="fas fa-external-link-alt"></i> Abrir
                                </a>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            Abra esta URL no navegador do display em modo tela cheia.
                        </small>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                    <a href="<?= ADMIN_PATH ?>/tvs" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
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
                <p><strong>ID:</strong> <?= $tv['id'] ?></p>
                <p><strong>Token:</strong> <code><?= htmlspecialchars($tv['token']) ?></code></p>
                <p><strong>Criado em:</strong> <?= date('d/m/Y H:i', strtotime($tv['created_at'])) ?></p>
                <p><strong>Último Ping:</strong> 
                    <?php if ($tv['ultimo_ping']): ?>
                        <?= date('d/m/Y H:i:s', strtotime($tv['ultimo_ping'])) ?>
                        <?php
                        $last_ping = new DateTime($tv['ultimo_ping']);
                        $now = new DateTime();
                        $diff = $now->getTimestamp() - $last_ping->getTimestamp();
                        $is_online = $diff < 300;
                        ?>
                        <br>
                        <span class="badge badge-<?= $is_online ? 'success' : 'danger' ?>">
                            <?= $is_online ? 'Online' : 'Offline' ?>
                        </span>
                    <?php else: ?>
                        Nunca conectou
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Instruções</h3>
            </div>
            <div class="card-body">
                <ol>
                    <li>Copie a URL do player</li>
                    <li>Abra no navegador do display</li>
                    <li>Pressione F11 para tela cheia</li>
                    <li>O conteúdo será atualizado automaticamente</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<script>
function copiarUrl() {
    const urlInput = document.getElementById('playerUrl');
    urlInput.select();
    document.execCommand('copy');
    alert('URL copiada para a área de transferência!');
}
</script>
