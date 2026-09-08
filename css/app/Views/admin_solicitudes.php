<?php

$session = \Config\Services::session();

if (
    !$session->get('isLoggedIn') ||
    strtolower((string)$session->get('rol')) !== 'administrador'
) {
    return redirect()->to(base_url('?login_error=acceso'));
}

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapy | Solicitudes de Entrega</title>

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=DM+Sans:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/style/admin.css') ?>">

</head>

<body>

<div class="admin-wrapper">

    <aside class="sidebar">

        <div class="brand-mark">

            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="34" height="34" rx="9" fill="#2E3B34"/>
                <path d="M9 14.5C9 11.4624 11.4624 9 14.5 9H21.5" stroke="#E0A458" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M18.5 6L21.8 9L18.5 12" stroke="#E0A458" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25 19.5C25 22.5376 22.5376 25 19.5 25H12.5" stroke="#E0A458" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M15.5 28L12.2 25L15.5 22" stroke="#E0A458" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>

            <div>
                <div class="brand-wordmark">Swapy<span>.</span></div>
                <p class="role-tag">Panel de Control</p>
            </div>

        </div>

        <nav class="sidebar-nav">

            <ul>
                <li><a href="<?= base_url('admin') ?>" class="nav-item">👥 Personas Registradas</a></li>
                <li><a href="<?= base_url('admin_preguntas') ?>" class="nav-item">❓ Preguntas Frecuentes</a></li>
                <li><a href="<?= base_url('admin_denuncias') ?>" class="nav-item">🚩 Denuncias</a></li>
                <li><a href="<?= base_url('admin_solicitudes') ?>" class="nav-item active">🚚 Solicitudes de Entrega</a></li>
                <li><a href="<?= base_url('admin_premium') ?>" class="nav-item">⭐ Usuarios Premium</a></li>
                <li><a href="<?= base_url('reportes') ?>" class="nav-item">📊 Reportes</a></li>
            </ul>

        </nav>

        <div class="sidebar-footer">
            <a href="<?= base_url('auth/logout') ?>" class="sidebar-btn sidebar-btn-logout">🚪 Cerrar sesión</a>
        </div>

    </aside>


    <div>

        <header class="topbar">

            <div class="topbar-search">
                <span class="icon">🔍</span>
                <input type="text" id="table-search" class="search-input-topbar" placeholder="Buscar producto, correo, estado..." oninput="filtrar(this.value)" style="padding-left:38px; border-radius:999px;">
            </div>

            <div class="topbar-right">

                <span class="topbar-date"><?= esc(date('d M Y')) ?></span>

                <div class="topbar-profile">
                    <div class="topbar-avatar"><?= esc(strtoupper(substr((string)$session->get('rol'), 0, 1))) ?></div>
                    <span class="topbar-profile-role"><?= esc($session->get('rol')) ?></span>
                </div>

            </div>

        </header>


        <main class="main-content-admin">

            <div class="section-header">
                <div>
                    <span class="eyebrow">Logística</span>
                    <h1>Solicitudes de <span>Entrega</span></h1>
                </div>
            </div>

            <div class="table-container">

                <?php if (!empty($solicitudesEntrega)): ?>

                    <table class="users-table" id="tabla">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Solicitante</th>
                                <th>Propietario</th>
                                <th>Dirección</th>
                                <th>Descripción lugar</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($solicitudesEntrega as $s): ?>
                                <tr>
                                    <td>#<?= (int)$s['id_intercambio'] ?></td>
                                    <td><?= esc($s['nombre_producto'] ?? 'N/A') ?></td>
                                    <td><?= esc($s['correo_solicitante'] ?? 'N/A') ?></td>
                                    <td><?= esc($s['correo_propietario'] ?? 'N/A') ?></td>
                                    <td><?= esc($s['direccion']) ?></td>
                                    <td><?= esc($s['descripcion_lugar']) ?></td>
                                    <td>
                                        <span class="badge <?= $s['estado'] === 'aceptado' ? 'badge-activo' : ($s['estado'] === 'rechazado' ? 'badge-inactivo' : 'badge-no') ?>">
                                            <?= esc(ucfirst($s['estado'])) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php else: ?>

                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <p>No hay solicitudes de entrega registradas.</p>
                    </div>

                <?php endif; ?>

            </div>

        </main>

    </div>

</div>

<script>
function filtrar(term) {
    term = (term || '').toLowerCase();
    var filas = document.querySelectorAll('#tabla tbody tr');
    filas.forEach(function(fila) {
        var texto = fila.textContent.toLowerCase();
        fila.style.display = texto.indexOf(term) !== -1 ? '' : 'none';
    });
}
</script>

</body>

</html>