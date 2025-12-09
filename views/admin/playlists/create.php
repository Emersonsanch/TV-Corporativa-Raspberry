<?php
// Arquivo: views/admin/playlists/create.php
// Formulário de Criação de Playlist
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Nova Playlist</h3>
            </div>
            <form action="<?= ADMIN_PATH ?>/playlists/create" method="POST">
                <div class="card-body">
                    <!-- Nome -->
                    <div class="form-group">
                        <label for="nome" class="required">Nome da Playlist</label>
                        <input type="text" class="form-control" id="nome" name="nome" required 
                               placeholder="Ex: Playlist Recepção, Playlist RH...">
                    </div>

                    <!-- Descrição -->
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" 
                                  placeholder="Descrição opcional da playlist..."></textarea>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="ativo" name="ativo" value="1" checked>
                            <label class="custom-control-label" for="ativo">Playlist Ativa</label>
                        </div>
                        <small class="form-text text-muted">
                            Apenas playlists ativas podem ser atribuídas às TVs.
                        </small>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Criar Playlist
                    </button>
                    <a href="<?= ADMIN_PATH ?>/playlists" class="btn btn-secondary">
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
                <p><i class="fas fa-info-circle"></i> Após criar a playlist, você poderá adicionar conteúdos e definir a ordem de exibição.</p>
                <p><i class="fas fa-sort"></i> Os conteúdos podem ser reordenados através de arrastar e soltar.</p>
                <p><i class="fas fa-clock"></i> Você pode definir uma duração específica para cada item na playlist.</p>
            </div>
        </div>
    </div>
</div>
