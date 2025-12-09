<?php
// Arquivo: views/tv/player.php
// Player de TV Corporativa com Auto-Play e Tela Cheia

// $tv e $playlist_itens estão disponíveis
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TV <?= htmlspecialchars($tv['nome']) ?> - <?= APP_NAME ?></title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            background-color: #000;
            overflow: hidden;
        }

        #player-container {
            width: 100%;
            height: 100%;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            display: none;
            justify-content: center;
            align-items: center;
            background-color: #000;
            color: #fff;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .slide.active {
            display: flex;
            opacity: 1;
        }

        /* Imagem */
        .slide-imagem img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Vídeo */
        .slide-video video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Texto */
        .slide-texto {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 5%;
            text-align: center;
            width: 100%;
        }

        .slide-texto .texto-content {
            font-size: 5vw;
            font-weight: bold;
            line-height: 1.5;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            word-wrap: break-word;
        }

        /* Link / iFrame */
        .slide-link iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Loading */
        #loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 9999;
        }

        #loading.hidden {
            display: none;
        }

        .spinner {
            border: 8px solid rgba(255, 255, 255, 0.1);
            border-left-color: #fff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        #loading p {
            margin-top: 20px;
            font-size: 1.5rem;
            color: #fff;
        }

        /* Mensagem de erro/vazio */
        .empty-message {
            font-size: 3vw;
            text-align: center;
            padding: 5%;
        }
    </style>
</head>
<body>
    <div id="loading">
        <div class="spinner"></div>
        <p>Carregando TV Corporativa...</p>
    </div>

    <div id="player-container">
        <?php if (empty($playlist_itens)): ?>
            <div class="slide active slide-texto">
                <div class="empty-message">
                    <strong>TV Corporativa</strong><br><br>
                    Nenhum conteúdo disponível.<br>
                    Configure uma playlist para esta TV.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($playlist_itens as $index => $item): 
                $duracao = $item['duracao'] ?? $item['conteudo_duracao'];
            ?>
                <div class="slide slide-<?= $item['tipo'] ?> <?= $index === 0 ? 'active' : '' ?>" 
                     data-duracao="<?= $duracao ?>" 
                     data-tipo="<?= $item['tipo'] ?>"
                     data-index="<?= $index ?>">
                    
                    <?php if ($item['tipo'] === 'imagem'): ?>
                        <img src="<?= get_upload_url($item['arquivo_url']) ?>" 
                             alt="<?= htmlspecialchars($item['nome']) ?>">
                    
                    <?php elseif ($item['tipo'] === 'video'): ?>
                        <video src="<?= get_upload_url($item['arquivo_url']) ?>" 
                               autoplay muted loop playsinline></video>
                    
                    <?php elseif ($item['tipo'] === 'texto'): ?>
                        <div class="texto-content">
                            <?= nl2br(htmlspecialchars($item['texto'])) ?>
                        </div>
                    
                    <?php elseif ($item['tipo'] === 'link'): ?>
                        <iframe src="<?= htmlspecialchars($item['arquivo_url']) ?>" 
                                allow="autoplay; encrypted-media" 
                                allowfullscreen></iframe>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;
        let currentSlide = 0;
        let slideTimer = null;
        let refreshTimer = null;
        let pingTimer = null;
        const TV_ID = <?= $tv['id'] ?>;
        const REFRESH_INTERVAL = 60000; // 60 segundos
        const PING_INTERVAL = 30000; // 30 segundos

        // Ocultar loading após carregar
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('loading').classList.add('hidden');
                if (totalSlides > 0) {
                    startSlideshow();
                    startRefreshTimer();
                    startPingTimer();
                }
            }, 1000);
        });

        function startSlideshow() {
            showSlide(currentSlide);
        }

        function showSlide(index) {
            // Esconder todos os slides
            slides.forEach((slide, i) => {
                slide.classList.remove('active');
                
                // Pausar vídeos
                const video = slide.querySelector('video');
                if (video) {
                    video.pause();
                    video.currentTime = 0;
                }
            });

            // Mostrar slide atual
            const currentSlideEl = slides[index];
            currentSlideEl.classList.add('active');

            // Reproduzir vídeo se for o tipo
            const video = currentSlideEl.querySelector('video');
            if (video) {
                video.play().catch(e => console.log('Erro ao reproduzir vídeo:', e));
            }

            // Obter duração do slide
            const duracao = parseInt(currentSlideEl.dataset.duracao) * 1000;

            // Agendar próximo slide
            clearTimeout(slideTimer);
            slideTimer = setTimeout(() => {
                nextSlide();
            }, duracao);
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        function startRefreshTimer() {
            // Atualizar conteúdo a cada 60 segundos
            refreshTimer = setInterval(() => {
                console.log('Atualizando conteúdo...');
                location.reload();
            }, REFRESH_INTERVAL);
        }

        function startPingTimer() {
            // Enviar ping para o servidor a cada 30 segundos
            sendPing(); // Enviar imediatamente
            pingTimer = setInterval(() => {
                sendPing();
            }, PING_INTERVAL);
        }

        function sendPing() {
            fetch('<?= APP_URL ?>/api/tv/ping', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ tv_id: TV_ID })
            }).then(response => {
                if (response.ok) {
                    console.log('Ping enviado com sucesso');
                }
            }).catch(e => console.log('Erro ao enviar ping:', e));
        }

        // Suporte a tela cheia
        document.addEventListener('keydown', function(e) {
            if (e.key === 'f' || e.key === 'F' || e.key === 'F11') {
                e.preventDefault();
                toggleFullscreen();
            }
        });

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log('Erro ao entrar em tela cheia:', err);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        // Auto-entrar em tela cheia após 2 segundos
        setTimeout(() => {
            if (!document.fullscreenElement) {
                toggleFullscreen();
            }
        }, 2000);

        // Prevenir sleep/screensaver com Wake Lock API
        let wakeLock = null;
        if ('wakeLock' in navigator) {
            navigator.wakeLock.request('screen').then(lock => {
                wakeLock = lock;
                console.log('Wake Lock ativado');
                
                lock.addEventListener('release', () => {
                    console.log('Wake Lock liberado');
                });
            }).catch(err => {
                console.log('Erro ao ativar Wake Lock:', err);
            });

            // Re-adquirir Wake Lock quando a página voltar a ficar visível
            document.addEventListener('visibilitychange', async () => {
                if (wakeLock !== null && document.visibilityState === 'visible') {
                    wakeLock = await navigator.wakeLock.request('screen');
                }
            });
        }

        // Log de inicialização
        console.log('TV Corporativa iniciada');
        console.log('Total de slides:', totalSlides);
        console.log('TV ID:', TV_ID);
    </script>
</body>
</html>
