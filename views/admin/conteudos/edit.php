<?php
// Arquivo: views/admin/conteudos/edit.php
// Formulário de Edição de Conteúdo

// $conteudo está disponível
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Editar Conteúdo #<?= $conteudo['id'] ?></h3>
            </div>
            <form action="<?= ADMIN_PATH ?>/conteudos/edit/<?= $conteudo['id'] ?>" method="POST" enctype="multipart/form-data" id="formConteudo">
                <div class="card-body">
                    <!-- Nome -->
                    <div class="form-group">
                        <label for="nome" class="required">Nome do Conteúdo</label>
                        <input type="text" class="form-control" id="nome" name="nome" required 
                               value="<?= htmlspecialchars($conteudo['nome']) ?>">
                    </div>

                    <!-- Tipo (somente leitura) -->
                    <div class="form-group">
                        <label>Tipo de Conteúdo</label>
                        <input type="text" class="form-control" value="<?= ucfirst($conteudo['tipo']) ?>" readonly>
                        <input type="hidden" name="tipo" value="<?= $conteudo['tipo'] ?>">
                        <small class="form-text text-muted">
                            O tipo não pode ser alterado após a criação.
                        </small>
                    </div>

                    <?php if ($conteudo['tipo'] === 'imagem' || $conteudo['tipo'] === 'video'): ?>
                        <!-- Arquivo Atual -->
                        <div class="form-group">
                            <label>Arquivo Atual</label>
                            <div class="mb-2">
                                <?php if ($conteudo['tipo'] === 'imagem'): ?>
                                    <img src="<?= get_upload_url($conteudo['arquivo_url']) ?>" 
                                         alt="Preview" style="max-width: 300px; border: 1px solid #dee2e6; border-radius: 4px;">
                                <?php else: ?>
                                    <video src="<?= get_upload_url($conteudo['arquivo_url']) ?>" 
                                           controls style="max-width: 400px; border: 1px solid #dee2e6; border-radius: 4px;"></video>
                                <?php endif; ?>
                            </div>
                            <small class="form-text text-muted">
                                Arquivo: <?= htmlspecialchars($conteudo['arquivo_url']) ?>
                            </small>
                        </div>

                        <!-- Upload de Novo Arquivo (Opcional) -->
                        <div class="form-group">
                            <label>Substituir Arquivo (Opcional)</label>
                            <div class="upload-area" id="dropZone">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p><strong>Arraste e solte o novo arquivo aqui</strong></p>
                                <p>ou</p>
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                    <i class="fas fa-folder-open"></i> Selecionar Novo Arquivo
                                </button>
                                <input type="file" id="fileInput" name="arquivo" style="display:none;" 
                                       accept="<?= $conteudo['tipo'] === 'imagem' ? 'image/jpeg,image/png,image/gif' : 'video/mp4,video/webm' ?>">
                            </div>
                            <div class="preview-container" id="previewContainer"></div>
                        </div>
                    <?php endif; ?>

                    <?php if ($conteudo['tipo'] === 'texto'): ?>
                        <!-- Campo de Texto -->
                        <div class="form-group">
                            <label for="texto" class="required">Texto / Mensagem</label>
                            <textarea class="form-control" id="texto" name="texto" rows="5" required><?= htmlspecialchars($conteudo['texto']) ?></textarea>
                        </div>
                    <?php endif; ?>

                    <?php if ($conteudo['tipo'] === 'link'): ?>
                        <!-- Campo de Link -->
                        <div class="form-group">
                            <label for="link" class="required">URL do Link</label>
                            <input type="url" class="form-control" id="link" name="link" required
                                   value="<?= htmlspecialchars($conteudo['arquivo_url']) ?>">
                        </div>
                    <?php endif; ?>

                    <!-- Duração -->
                    <div class="form-group">
                        <label for="duracao" class="required">Duração de Exibição (segundos)</label>
                        <input type="number" class="form-control" id="duracao" name="duracao" 
                               value="<?= $conteudo['duracao'] ?>" min="1" max="300" required>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="ativo" name="ativo" value="1" 
                                   <?= $conteudo['ativo'] ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="ativo">Conteúdo Ativo</label>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                    <a href="<?= ADMIN_PATH ?>/conteudos" class="btn btn-secondary">
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
                <p><strong>ID:</strong> <?= $conteudo['id'] ?></p>
                <p><strong>Tipo:</strong> <?= ucfirst($conteudo['tipo']) ?></p>
                <p><strong>Criado em:</strong> <?= date('d/m/Y H:i', strtotime($conteudo['created_at'])) ?></p>
                <p><strong>Status:</strong> 
                    <span class="badge badge-<?= $conteudo['ativo'] ? 'success' : 'danger' ?>">
                        <?= $conteudo['ativo'] ? 'Ativo' : 'Inativo' ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<?php if ($conteudo['tipo'] === 'imagem' || $conteudo['tipo'] === 'video'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const previewContainer = document.getElementById('previewContainer');
    const tipo = '<?= $conteudo['tipo'] ?>';
    
    // Drag & Drop
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('drag-over');
    });
    
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('drag-over');
    });
    
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });
    
    // File Input Change
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleFileSelect(this.files[0]);
        }
    });
    
    // Preview do arquivo
    function handleFileSelect(file) {
        previewContainer.innerHTML = '';
        
        if (tipo === 'imagem' && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.innerHTML = `
                    <div class="alert alert-info mt-3">
                        <strong>Novo arquivo selecionado:</strong><br>
                        <img src="${e.target.result}" alt="Preview" style="max-width: 200px; margin-top: 10px; border-radius: 4px;">
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        } else if (tipo === 'video' && file.type.startsWith('video/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.innerHTML = `
                    <div class="alert alert-info mt-3">
                        <strong>Novo arquivo selecionado:</strong><br>
                        <video src="${e.target.result}" controls style="max-width: 300px; margin-top: 10px; border-radius: 4px;"></video>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
    }
});
</script>
<?php endif; ?>
