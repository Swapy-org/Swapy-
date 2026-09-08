<?php
/**
 * views/admin_denuncias.php
 * Sección de denuncias en el admin panel.
 */

// 1. Validamos la sesión y el Rol con CodeIgniter
$session = \Config\Services::session();
if (!$session->get('isLoggedIn') || strtolower($session->get('rol')) !== 'administrador') {
    $response = \Config\Services::response();
    $response->redirect(base_url('?login_error=acceso'))->send();
    exit();
}

// 2. Cargamos el Modelo de forma nativa en CodeIgniter
$soporteModel = new \App\Models\SoporteModel();

// Capturamos el filtro actual desde la URL de forma segura
$filtro = $_GET['filtro'] ?? 'pendiente';

// Obtenemos las denuncias procesadas como un array
$resDenuncias = $soporteModel->obtenerDenuncias($filtro);

$mensajes = [
    'respondida' => ['success', '✅ Denuncia procesada correctamente.'],
    'error' => ['error', '❌ No se pudo guardar la resolución. Intenta de nuevo.'],
];
if(isset($_GET['msg']) && array_key_exists($_GET['msg'], $mensajes)):
    [$tipo, $texto] = $mensajes[$_GET['msg']];
?>
<div class="alert alert-<?= $tipo; ?>"><?= $texto; ?></div>
<?php endif; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapy | Denuncias</title>
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
                <li><a href="<?= base_url('admin_denuncias') ?>" class="nav-item active">🚩 Denuncias</a></li>
                <li><a href="<?= base_url('admin_premium') ?>" class="nav-item">⭐ Usuarios Premium</a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= base_url('auth?accion=logout') ?>" class="sidebar-btn sidebar-btn-logout">🚪 Cerrar sesión</a>
        </div>
    </aside>

    <main class="main-content-admin">

        <div class="section-header">
            <div>
                <span class="eyebrow">Moderación</span>
                <h1>Denuncias <span>de Usuarios</span></h1>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card blue"><div class="stat-label">Pendientes</div><div class="stat-number"><?= $soporteModel->contarDenuncias('pendiente'); ?></div></div>
            <div class="stat-card orange"><div class="stat-label">En Revisión</div><div class="stat-number"><?= $soporteModel->contarDenuncias('en_revision'); ?></div></div>
            <div class="stat-card green"><div class="stat-label">Resueltas</div><div class="stat-number"><?= $soporteModel->contarDenuncias('resuelta'); ?></div></div>
            <div class="stat-card red"><div class="stat-label">Rechazadas</div><div class="stat-number"><?= $soporteModel->contarDenuncias('rechazada'); ?></div></div>
        </div>

        <div class="table-container">
            <div class="table-toolbar">
                <div style="display:flex;gap:10px;">
                    <a href="<?= base_url('admin_denuncias?filtro=pendiente') ?>" class="filter-btn <?= $filtro === 'pendiente' ? 'active' : ''; ?>">Pendientes</a>
                    <a href="<?= base_url('admin_denuncias?filtro=en_revision') ?>" class="filter-btn <?= $filtro === 'en_revision' ? 'active' : ''; ?>">En Revisión</a>
                    <a href="<?= base_url('admin_denuncias?filtro=resuelta') ?>" class="filter-btn <?= $filtro === 'resuelta' ? 'active' : ''; ?>">Resueltas</a>
                </div>
            </div>

            <?php if(!empty($resDenuncias)): ?>
            <div class="denuncias-list">
                <?php foreach($resDenuncias as $denuncia): ?>
                <div class="denuncia-item">
                    <div class="denuncia-header">
                        <div class="denuncia-info">
                            <h4>🚩 <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $denuncia['tipo_denuncia']))); ?></h4>
                            <p class="denuncia-usuario">Por: <?= htmlspecialchars($denuncia['correo_denunciante']); ?></p>
                            <?php if(!empty($denuncia['correo_denunciado'])): ?>
                            <p class="denuncia-usuario">Contra: <?= htmlspecialchars($denuncia['correo_denunciado']); ?></p>
                            <?php endif; ?>
                            <p class="denuncia-fecha"><?= date('d/m/Y H:i', strtotime($denuncia['fecha_creacion'])); ?></p>
                        </div>
                        <span class="badge <?= $denuncia['estado'] === 'pendiente' ? 'badge-pending' : ($denuncia['estado'] === 'en_revision' ? 'badge-warning' : ($denuncia['estado'] === 'resuelta' ? 'badge-success' : 'badge-danger')); ?>">
                            <?= ucfirst($denuncia['estado']); ?>
                        </span>
                    </div>
                    
                    <div class="denuncia-body">
                        <p><strong>Descripción:</strong></p>
                        <p class="text-body"><?= htmlspecialchars($denuncia['descripcion']); ?></p>
                        
                        <?php if(!empty($denuncia['respuesta'])): ?>
                        <p style="margin-top:16px;"><strong>Resolución:</strong></p>
                        <p class="text-body"><?= htmlspecialchars($denuncia['respuesta']); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if($denuncia['estado'] !== 'resuelta' && $denuncia['estado'] !== 'rechazada'): ?>
                    <div class="denuncia-actions">
                        <button class="btn-action" onclick="abrirModalResolucion(<?= $denuncia['id_denuncia']; ?>, 'denuncia')" title="Procesar Denuncia">
                            ✅ Procesar
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">✨</div>
                <p>No hay denuncias en esta categoría.</p>
            </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<div id="modal-resolucion-denuncia" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Procesar Denuncia</h2>
            <button class="close-btn" onclick="cerrarModal('modal-resolucion-denuncia')">✖</button>
        </div>
        <div class="modal-body">
            <form action="<?= base_url('admin/responder_denuncia') ?>" method="POST">
                <input type="hidden" name="id_denuncia" id="res-denuncia-id">
                <div class="form-group">
                    <label>Estado de Resolución</label>
                    <select name="estado" required>
                        <option value="resuelta">✅ Resuelta</option>
                        <option value="rechazada">❌ Rechazada</option>
                        <option value="en_revision">🔍 Mantener en Revisión</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Notas de Resolución</label>
                    <textarea name="respuesta" placeholder="Describe cómo se resolvió la denuncia..." rows="8" required></textarea>
                </div>
                <button type="submit" class="save-btn">💾 Guardar Resolución</button>
            </form>
        </div>
    </div>
</div>

<style>
.denuncias-list { display: flex; flex-direction: column; gap: 16px; }
.denuncia-item { border: 1px solid #e9ecef; border-radius: 12px; padding: 20px; background: #f8f9fa; }
.denuncia-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px; }
.denuncia-info h4 { color: #2c3e50; font-size: 16px; margin-bottom: 8px; }
.denuncia-usuario { font-size: 13px; color: #6c757d; }
.denuncia-fecha { font-size: 12px; color: #adb5bd; margin-top: 4px; }
.denuncia-body { margin: 16px 0; }
.text-body { color: #5a6c7d; font-size: 14px; line-height: 1.6; margin-top: 8px; background: white; padding: 12px; border-radius: 8px; }
.denuncia-actions { display: flex; gap: 10px; margin-top: 16px; }
.btn-action { background: #198754; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: background 0.2s; }
.btn-action:hover { background: #157347; }
.badge-warning { background: #fd7e14; color: white; }
.badge-danger { background: #dc3545; color: white; }
</style>

<script>
function abrirModalResolucion(id, tipo) {
    document.getElementById('res-denuncia-id').value = id;
    document.getElementById('modal-resolucion-' + tipo).style.display = 'flex';
}

function cerrarModal(id) {
    document.getElementById(id).style.display = 'none';
}
</script>
</body>
</html>