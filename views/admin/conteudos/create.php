<?php
// Arquivo: views/admin/conteudos/create.php
// Formulário de Criação de Conteúdo com Drag & Drop
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Novo Conteúdo</h3>
            </div>
            <form action="<?= ADMIN_PATH ?>/conteudos/create" method="POST" enctype="multipart/form-data" id="formConteudo">
                <div class="card-body">
                    <!-- Nome -->
                    <div class="form-group">
                        <label for="nome" class="required">Nome do Conteúdo</label>
                        <input type="text" class="form-control" id="nome" name="nome" required 
                               placeholder="Ex: Banner Promocional, Vídeo Institucional...">
                    </div>

                    <!-- Tipo -->
                    <div class="form-group">
                        <label for="tipo" class="required">Tipo de Conteúdo</label>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option value="">Selecione o tipo</option>
                            <option value="imagem">Imagem (JPG, PNG, GIF)</option>
                            <option value="video">Vídeo (MP4, WebM)</option>
                            <option value="texto">Texto / Mensagem</option>
                            <option value="link">Link Externo / iFrame</option>
                        </select>
                    </div>

                    <!-- Upload de Arquivo (Imagem/Vídeo) -->
                    <div class="form-group" id="uploadArea" style="display:none;">
                        <label class="required">Arquivo</label>
                        <div class="upload-area" id="dropZone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p><strong>Arraste e solte o arquivo aqui</strong></p>
                            <p>ou</p>
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                <i class="fas fa-folder-open"></i> Selecionar Arquivo
                            </button>
                            <input type="file" id="fileInput" name="arquivo" style="display:none;" accept="">
                        </div>
                        <div class="preview-container" id="previewContainer"></div>
                    </div>

                    <!-- Campo de Texto -->
                    <div class="form-group" id="textoArea" style="display:none;">
                        <label for="texto" class="required">Texto / Mensagem</label>
                        <textarea class="form-control" id="texto" name="texto" rows="5" 
                                  placeholder="Digite a mensagem que será exibida na TV..."></textarea>
                        <small class="form-text text-muted">
                            Este texto será exibido em tela cheia na TV corporativa.
                        </small>
                    </div>

                    <!-- Campo de Link -->
                    <div class="form-group" id="linkArea" style="display:none;">
                        <label for="link" class="required">URL do Link</label>
                        <input type="url" class="form-control" id="link" name="link" 
                               placeholder="https://exemplo.com/dashboard">
                        <small class="form-text text-muted">
                            O conteúdo será exibido em um iframe. Certifique-se de que o site permite ser incorporado.
                        </small>
                    </div>

                    <!-- Duração -->
                    <div class="form-group">
                        <label for="duracao" class="required">Duração de Exibição (segundos)</label>
                        <input type="number" class="form-control" id="duracao" name="duracao" 
                               value="10" min="1" max="300" required>
                        <small class="form-text text-muted">
                            Tempo que o conteúdo ficará visível na tela (1 a 300 segundos).
                        </small>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="ativo" name="ativo" value="1" checked>
                            <label class="custom-control-label" for="ativo">Conteúdo Ativo</label>
                        </div>
                        <small class="form-text text-muted">
                            Apenas conteúdos ativos serão exibidos nas playlists.
                        </small>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Salvar Conteúdo
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
                <h3 class="card-title">Ajuda</h3>
            </div>
            <div class="card-body">
                <h5><i class="fas fa-image"></i> Imagem</h5>
                <p>Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 10MB.</p>
                
                <h5><i class="fas fa-video"></i> Vídeo</h5>
                <p>Formatos aceitos: MP4, WebM. Tamanho máximo: 100MB. O vídeo será reproduzido em loop.</p>
                
                <h5><i class="fas fa-font"></i> Texto</h5>
                <p>Mensagens curtas e avisos internos que serão exibidos em tela cheia.</p>
                
                <h5><i class="fas fa-link"></i> Link Externo</h5>
                <p>Dashboards, sites externos ou qualquer URL que possa ser exibida em iframe.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('tipo');
    const uploadArea = document.getElementById('uploadArea');
    const textoArea = document.getElementById('textoArea');
    const linkArea = document.getElementById('linkArea');
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const previewContainer = document.getElementById('previewContainer');
    
    // Alternar campos com base no tipo selecionado
    tipoSelect.addEventListener('change', function() {
        const tipo = this.value;
        
        // Esconder todos os campos específicos
        uploadArea.style.display = 'none';
        textoArea.style.display = 'none';
        linkArea.style.display = 'none';
        
        // Resetar required
        fileInput.removeAttribute('required');
        document.getElementById('texto').removeAttribute('required');
        document.getElementById('link').removeAttribute('required');
        
        // Mostrar campo apropriado
        if (tipo === 'imagem') {
            uploadArea.style.display = 'block';
            fileInput.setAttribute('accept', 'image/jpeg,image/png,image/gif');
            fileInput.setAttribute('required', 'required');
        } else if (tipo === 'video') {
            uploadArea.style.display = 'block';
            fileInput.setAttribute('accept', 'video/mp4,video/webm');
            fileInput.setAttribute('required', 'required');
        } else if (tipo === 'texto') {
            textoArea.style.display = 'block';
            document.getElementById('texto').setAttribute('required', 'required');
        } else if (tipo === 'link') {
            linkArea.style.display = 'block';
            document.getElementById('link').setAttribute('required', 'required');
        }
        
        // Limpar preview
        previewContainer.innerHTML = '';
    });
    
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
        const tipo = tipoSelect.value;
        previewContainer.innerHTML = '';
        
        if (tipo === 'imagem' && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.innerHTML = `
                    <div class="preview-item">
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-btn" onclick="removeFile()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        } else if (tipo === 'video' && file.type.startsWith('video/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.innerHTML = `
                    <div class="preview-item">
                        <video src="${e.target.result}" controls></video>
                        <button type="button" class="remove-btn" onclick="removeFile()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Função global para remover arquivo
    window.removeFile = function() {
        fileInput.value = '';
        previewContainer.innerHTML = '';
    };
});
</script>
