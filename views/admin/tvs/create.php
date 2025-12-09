<?php
// Arquivo: views/admin/tvs/create.php
// Formulário de Criação de TV

// $playlists está disponível
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Nova TV (Display)</h3>
            </div>
            <form action="<?= ADMIN_PATH ?>/tvs/create" method="POST">
                <div class="card-body">
                    <!-- Nome -->
                    <div class="form-group">
                        <label for="nome" class="required">Nome da TV</label>
                        <input type="text" class="form-control" id="nome" name="nome" required 
                               placeholder="Ex: TV Recepção, TV RH, TV Refeitório...">
                        <small class="form-text text-muted">
                            Identificação da TV para facilitar o gerenciamento.
                        </small>
                    </div>

                    <!-- Playlist -->
                    <div class="form-group">
                        <label for="playlist_id">Playlist Associada</label>
                        <select class="form-control" id="playlist_id" name="playlist_id">
                            <option value="">-- Nenhuma (pode ser definida depois) --</option>
                            <?php foreach ($playlists as $playlist): ?>
                                <?php if ($playlist['ativo']): ?>
                                    <option value="<?= $playlist['id'] ?>">
                                        <?= htmlspecialchars($playlist['nome']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">
                            Selecione a playlist que será exibida nesta TV.
                        </small>
                    </div>

                    <!-- Token (gerado automaticamente) -->
                    <div class="form-group">
                        <label>Token de Acesso</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="token" name="token" 
                                   value="<?= bin2hex(random_bytes(16)) ?>" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-secondary" onclick="gerarNovoToken()">
                                    <i class="fas fa-sync"></i> Gerar Novo
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            Token único para acesso seguro ao player desta TV.
                        </small>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Criar TV
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
                <h5><i class="fas fa-tv"></i> TV Corporativa</h5>
                <p>Cada TV representa um display físico que exibirá o conteúdo da playlist associada.</p>
                
                <h5><i class="fas fa-key"></i> Token</h5>
                <p>O token é usado para acessar o player da TV de forma segura, sem necessidade de login.</p>
                
                <h5><i class="fas fa-link"></i> URL de Acesso</h5>
                <p>Após criar a TV, você receberá uma URL específica para abrir em tela cheia no display.</p>
            </div>
        </div>
    </div>
</div>

<script>
function gerarNovoToken() {
    // Gerar token aleatório
    const array = new Uint8Array(16);
    crypto.getRandomValues(array);
    const token = Array.from(array, byte => byte.toString(16).padStart(2, '0')).join('');
    document.getElementById('token').value = token;
}
</script>
