<?php
/**
 * views/admin_premium.php
 * Sección de usuarios premium en el admin panel (Adaptado a CodeIgniter 4).
 */

// 1. Validamos la sesión y el Rol con CodeIgniter
$session = \Config\Services::session();
if (!$session->get('isLoggedIn') || strtolower($session->get('rol')) !== 'administrador') {
    $response = \Config\Services::response();
    $response->redirect(base_url('?login_error=acceso'))->send();
    exit();
}

// 2. Cargamos los Modelos de CodeIgniter para alimentar la vista
$soporteModel = new \App\Models\SoporteModel();
$usuarioModel = new \App\Models\UsuarioModel();

// 3. Inicializamos las variables que requiere tu diseño
$totalPremium  = $soporteModel->contarPremium();
// Obtenemos los usuarios con rol Cliente
$todosLosUsuarios = $usuarioModel->obtenerTodos(); 
$clientes = [];

if (!empty($todosLosUsuarios)) {
    foreach ($todosLosUsuarios as $u) {
        $rolL = strtolower($u['rol']);
        if ($rolL === 'cliente') {
            // Evaluamos si tiene una suscripción premium activa
            $u['tiene_premium'] = $soporteModel->esUsuarioPremium($u['id_usuario']);
            $clientes[] = $u;
        }
    }
}
$totalClientes = count($clientes);
$premiums      = $soporteModel->obtenerPremium();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapy | Usuarios Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/style/admin.css') ?>">
</head>
<body>
<div class="admin-wrapper">

    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="footer-logo">Swapy<span>.</span></div>
            <p class="role-tag">Panel de Control</p>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="<?= base_url('admin') ?>" class="nav-item">👥 Personas Registradas</a></li>
                <li><a href="<?= base_url('admin_preguntas') ?>" class="nav-item">❓ Preguntas Frecuentes</a></li>
                <li><a href="<?= base_url('admin_denuncias') ?>" class="nav-item">🚩 Denuncias</a></li>
                <li><a href="<?= base_url('admin_premium') ?>" class="nav-item active">⭐ Usuarios Premium</a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= base_url('auth?accion=logout') ?>" class="sidebar-btn sidebar-btn-logout">🚪 Cerrar sesión</a>
        </div>
    </aside>

    <main class="main-content-admin">

        <?php
        $mensajes = [
            'creado'    => ['success', '✅ Usuario premium creado correctamente.'],
            'cancelado' => ['success', '✅ Suscripción premium cancelada.'],
            'error'     => ['error',   '❌ Ocurrió un error inesperado.'],
        ];
        if(isset($_GET['msg']) && array_key_exists($_GET['msg'], $mensajes)):
            [$tipo, $texto] = $mensajes[$_GET['msg']];
        ?>
        <div class="alert alert-<?= $tipo; ?>"><?= $texto; ?></div>
        <?php endif; ?>

        <div class="section-header">
            <div>
                <span class="eyebrow">Suscripción</span>
                <h1>Usuarios <span>Premium</span></h1>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card green"><div class="stat-label">Premium Activos</div><div class="stat-number"><?= $totalPremium; ?></div></div>
            <div class="stat-card blue"><div class="stat-label">Clientes Registrados</div><div class="stat-number"><?= $totalClientes; ?></div></div>
        </div>

        <div class="section-divider">
            <h2>📋 Clientes Registrados</h2>
        </div>

        <div class="table-container">
            <div class="table-toolbar">
                <button class="add-user-btn" onclick="abrirModalPremium('modal-crear-premium')">⭐ Crear Premium</button>
            </div>

            <?php if(!empty($clientes)): ?>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Estado Premium</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($clientes as $cliente): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['correo']); ?></td>
                    <td><span class="badge" style="background:#e7f5ff;color:#1971c2;"><?= ucfirst($cliente['rol']); ?></span></td>
                    <td>
                        <?php if($cliente['tiene_premium']): ?>
                            <span class="badge" style="background:#d3f9d8;color:#2b8a3e;">✅ Premium</span>
                        <?php else: ?>
                            <span class="badge" style="background:#f8d7da;color:#842029;">❌ No Premium</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <button class="btn-action" onclick="abrirModalConUsuario(<?= $cliente['id_usuario']; ?>)" title="Hacer Premium">⭐</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">👤</div>
                <p>No hay clientes registrados aún.</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="section-divider">
            <h2>⭐ Usuarios Premium Activos</h2>
        </div>

        <div class="table-container">
            <?php if(!empty($premiums)): ?>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Correo</th>
                        <th>Plan</th>
                        <th>Inicio</th>
                        <th>Vencimiento</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($premiums as $premium): ?>
                <?php
                    $estado_color = $premium['estado'] === 'activo' ? 'badge-success' : 'badge-danger';
                    $fecha_fin = $premium['fecha_fin'] ? date('d/m/Y', strtotime($premium['fecha_fin'])) : 'N/A';
                ?>
                <tr>
                    <td><?= htmlspecialchars($premium['correo']); ?></td>
                    <td><span class="badge" style="background:#ffc107;color:#000;"><?= ucfirst($premium['plan']); ?></span></td>
                    <td><?= date('d/m/Y', strtotime($premium['fecha_inicio'])); ?></td>
                    <td><?= $fecha_fin; ?></td>
                    <td><span class="badge <?= $estado_color; ?>"><?= ucfirst($premium['estado']); ?></span></td>
                    <td class="actions">
                        <?php if($premium['estado'] === 'activo'): ?>
                        <a href="<?= base_url('admin/cancelar_premium?id_usuario='.$premium['id_usuario']) ?>" 
                           class="btn-action" title="Cancelar" onclick="return confirm('¿Cancelar premium de este usuario?');">
                            ❌
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">⭐</div>
                <p>No hay usuarios premium aún.</p>
            </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<div id="modal-crear-premium" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Crear Usuario Premium</h2>
            <button class="close-btn" onclick="cerrarModalPremium('modal-crear-premium')">✖</button>
        </div>
        <div class="modal-body">
            <form action="<?= base_url('admin/crear_premium') ?>" method="POST">
                <div class="form-group">
                    <label>Usuario</label>
                    <select name="id_usuario" id="select-usuario" required>
                        <option value="">Seleccione un usuario</option>
                        <?php if(!empty($clientes)): ?>
                            <?php foreach($clientes as $u): ?>
                            <option value="<?= $u['id_usuario']; ?>"><?= htmlspecialchars($u['correo']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Plan</label>
                    <select name="plan" required>
                        <option value="basico">Básico</option>
                        <option value="profesional">Profesional</option>
                        <option value="empresarial">Empresarial</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Duración (Meses)</label>
                    <input type="number" name="meses" min="1" max="60" value="1" required>
                </div>
                <button type="submit" class="save-btn">⭐ Crear Premium</button>
            </form>
        </div>
    </div>
</div>

<style>
/* CSS del overlay para asegurar que los modales funcionen con classList active */
.modal-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.modal-overlay.active {
    display: flex;
}
.section-divider { 
    margin-top: 48px; 
    margin-bottom: 24px; 
    padding-top: 24px; 
    border-top: 2px solid #e2e8f0; 
}
.section-divider h2 {
    font-size: 20px;
    color: #2c3e50;
    font-weight: 600;
}
.badge-success { background: #198754; color: white; }
.badge-danger { background: #dc3545; color: white; }
</style>

<script>
function abrirModalPremium(id) {
    document.getElementById(id).classList.add('active');
}

function abrirModalConUsuario(idUsuario) {
    document.getElementById('select-usuario').value = idUsuario;
    abrirModalPremium('modal-crear-premium');
}

function cerrarModalPremium(id) {
    document.getElementById(id).classList.remove('active');
}
</script>
</body>
</html>