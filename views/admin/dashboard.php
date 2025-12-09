<?php
// Arquivo: views/admin/dashboard.php
// Conteúdo do Dashboard

// O array $data foi extraído para variáveis no layout/admin.php
// $total_conteudos, $total_playlists, $total_tvs, $tvs_online

?>
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $total_conteudos ?></h3>
                <p>Conteúdos Cadastrados</p>
            </div>
            <div class="icon">
                <i class="fas fa-image"></i>
            </div>
            <a href="<?= ADMIN_PATH ?>/conteudos" class="small-box-footer">
                Mais detalhes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $total_playlists ?></h3>
                <p>Playlists Criadas</p>
            </div>
            <div class="icon">
                <i class="fas fa-list-ul"></i>
            </div>
            <a href="<?= ADMIN_PATH ?>/playlists" class="small-box-footer">
                Mais detalhes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $total_tvs ?></h3>
                <p>TVs (Displays) Cadastradas</p>
            </div>
            <div class="icon">
                <i class="fas fa-tv"></i>
            </div>
            <a href="<?= ADMIN_PATH ?>/tvs" class="small-box-footer">
                Mais detalhes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $tvs_online ?></h3>
                <p>TVs Online (Últimos 5 min)</p>
            </div>
            <div class="icon">
                <i class="fas fa-signal"></i>
            </div>
            <a href="<?= ADMIN_PATH ?>/tvs" class="small-box-footer">
                Mais detalhes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bem-vindo ao Painel Administrativo</h3>
            </div>
            <div class="card-body">
                <p>Este é o painel de controle do seu sistema **<?= APP_NAME ?>**.</p>
                <p>Use o menu lateral para gerenciar conteúdos, playlists e TVs.</p>
            </div>
        </div>
    </div>
</div>
