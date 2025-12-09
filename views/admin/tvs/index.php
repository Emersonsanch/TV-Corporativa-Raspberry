<?php
// Arquivo: views/admin/tvs/index.php
// Listagem de TVs

// $tvs está disponível

?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de TVs (Displays)</h3>
        <div class="card-tools">
            <a href="<?= ADMIN_PATH ?>/tvs/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nova TV
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Playlist Associada</th>
                    <th>Token</th>
                    <th>Último Ping</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tvs)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Nenhuma TV cadastrada.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tvs as $tv): 
                        $last_ping = new DateTime($tv['ultimo_ping']);
                        $now = new DateTime();
                        $diff = $now->getTimestamp() - $last_ping->getTimestamp();
                        $is_online = $diff < 300; // Online se ping nos últimos 5 minutos
                    ?>
                        <tr>
                            <td><?= $tv['id'] ?></td>
                            <td><?= htmlspecialchars($tv['nome']) ?></td>
                            <td><?= htmlspecialchars($tv['playlist_nome'] ?? 'Nenhuma') ?></td>
                            <td><?= htmlspecialchars($tv['token']) ?></td>
                            <td><?= $last_ping->format('d/m/Y H:i:s') ?></td>
                            <td>
                                <span class="badge badge-<?= $is_online ? 'success' : 'danger' ?>">
                                    <?= $is_online ? 'Online' : 'Offline' ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= APP_URL ?>/tv/<?= $tv['token'] ?>" target="_blank" class="btn btn-sm btn-success" title="Visualizar Player">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= ADMIN_PATH ?>/tvs/edit/<?= $tv['id'] ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?= ADMIN_PATH ?>/tvs/delete/<?= $tv['id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta TV?');">
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
