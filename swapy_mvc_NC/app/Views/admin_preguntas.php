<?php
/**
 * views/admin_preguntas.php
 * Sección de preguntas frecuentes de usuarios en el admin panel.
 */

// 1. Validamos la sesión y el Rol con CodeIgniter
$session = \Config\Services::session();
if (!$session->get('isLoggedIn') || strtolower($session->get('rol')) !== 'administrador') {
    $response = \Config\Services::response();
    $response->redirect(base_url('?login_error=acceso'))->send();
    exit();
}

// 2. Cargamos el Modelo de forma nativa en CodeIgniter
$soporteModel =  new \App\Models\SoporteModel();

// Capturamos el filtro actual desde la URL de forma segura
$filtro = $_GET['filtro'] ?? 'pendiente';

// Obtenemos los datos (CodeIgniter maneja las conexiones internamente)
$resPreguntas = $soporteModel->obtenerPreguntas($filtro);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapy | Preguntas Frecuentes</title>
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
                <li><a href="<?= base_url('admin_preguntas') ?>" class="nav-item active">❓ Preguntas Frecuentes</a></li>
                <li><a href="<?= base_url('admin_denuncias') ?>" class="nav-item">🚩 Denuncias</a></li>
                <li><a href="<?= base_url('admin_premium') ?>" class="nav-item">⭐ Usuarios Premium</a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= base_url('auth?accion=logout') ?>" class="sidebar-btn sidebar-btn-logout">🚪 Cerrar sesión</a>
        </div>
    </aside>

    <main class="main-content-admin">

        <?php
        $mensajes = [
            'respondida' => ['success', '✅ Pregunta respondida correctamente.'],
            'error' => ['error', '❌ No se pudo guardar la respuesta. Intenta de nuevo.'],
        ];
        if(isset($_GET['msg']) && array_key_exists($_GET['msg'], $mensajes)):
            [$tipo, $texto] = $mensajes[$_GET['msg']];
        ?>
        <div class="alert alert-<?= $tipo; ?>"><?= $texto; ?></div>
        <?php endif; ?>

        <div class="section-header">
            <div>
                <span class="eyebrow">Soporte</span>
                <h1>Preguntas <span>Frecuentes</span></h1>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card blue"><div class="stat-label">Pendientes</div><div class="stat-number"><?= $soporteModel->contarPreguntas('pendiente'); ?></div></div>
            <div class="stat-card green"><div class="stat-label">Respondidas</div><div class="stat-number"><?= $soporteModel->contarPreguntas('respondida'); ?></div></div>
            <div class="stat-card purple"><div class="stat-label">Cerradas</div><div class="stat-number"><?= $soporteModel->contarPreguntas('cerrada'); ?></div></div>
        </div>

        <div class="table-container">
            <div class="table-toolbar">
                <div style="display:flex;gap:10px;">
                    <a href="<?= base_url('admin_preguntas?filtro=pendiente') ?>" class="filter-btn <?= $filtro === 'pendiente' ? 'active' : ''; ?>">Pendientes</a>
                    <a href="<?= base_url('admin_preguntas?filtro=respondida') ?>" class="filter-btn <?= $filtro === 'respondida' ? 'active' : ''; ?>">Respondidas</a>
                    <a href="<?= base_url('admin_preguntas?filtro=cerrada') ?>" class="filter-btn <?= $filtro === 'cerrada' ? 'active' : ''; ?>">Cerradas</a>
                </div>
            </div>

            <?php if(!empty($resPreguntas)): ?>
            <div class="preguntas-list">
                <?php foreach($resPreguntas as $pregunta): ?>
                <div class="pregunta-item">
                    <div class="pregunta-header">
                        <div class="pregunta-info">
                            <h4><?= htmlspecialchars(substr($pregunta['pregunta'], 0, 80) . '...'); ?></h4>
                            <p class="pregunta-usuario">De: <?= htmlspecialchars($pregunta['correo']); ?></p>
                            <p class="pregunta-fecha"><?= date('d/m/Y H:i', strtotime($pregunta['fecha_creacion'])); ?></p>
                        </div>
                        <span class="badge <?= $pregunta['estado'] === 'pendiente' ? 'badge-pending' : ($pregunta['estado'] === 'respondida' ? 'badge-success' : 'badge-closed'); ?>">
                            <?= ucfirst($pregunta['estado']); ?>
                        </span>
                    </div>
                    
                    <div class="pregunta-body">
                        <p><strong>Pregunta:</strong></p>
                        <p class="text-body"><?= htmlspecialchars($pregunta['pregunta']); ?></p>
                        
                        <?php if(!empty($pregunta['respuesta'])): ?>
                        <p style="margin-top:16px;"><strong>Respuesta:</strong></p>
                        <p class="text-body"><?= htmlspecialchars($pregunta['respuesta']); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if($pregunta['estado'] === 'pendiente'): ?>
                    <div class="pregunta-actions">
                        <button class="btn-respond" onclick="abrirModalRespuesta(<?= $pregunta['id_pregunta']; ?>, 'pregunta')">
                            💬 Responder
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">❓</div>
                <p>No hay preguntas en esta categoría.</p>
            </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<div id="modal-respuesta-pregunta" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Responder Pregunta</h2>
            <button class="close-btn" onclick="cerrarModal('modal-respuesta-pregunta')">✖</button>
        </div>
        <div class="modal-body">
            <form action="<?= base_url('admin/responder_pregunta') ?>" method="POST">
                <input type="hidden" name="id_pregunta" id="resp-pregunta-id">
                <div class="form-group">
                    <label>Tu Respuesta</label>
                    <textarea name="respuesta" placeholder="Escribe tu respuesta aquí..." rows="8" required></textarea>
                </div>
                <button type="submit" class="save-btn">💾 Enviar Respuesta</button>
            </form>
        </div>
    </div>
</div>

<style>
.preguntas-list { display: flex; flex-direction: column; gap: 16px; }
.pregunta-item { border: 1px solid #e9ecef; border-radius: 12px; padding: 20px; background: #f8f9fa; }
.pregunta-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px; }
.pregunta-info h4 { color: #2c3e50; font-size: 16px; margin-bottom: 8px; }
.pregunta-usuario { font-size: 13px; color: #6c757d; }
.pregunta-fecha { font-size: 12px; color: #adb5bd; margin-top: 4px; }
.pregunta-body { margin: 16px 0; }
.text-body { color: #5a6c7d; font-size: 14px; line-height: 1.6; margin-top: 8px; background: white; padding: 12px; border-radius: 8px; }
.pregunta-actions { display: flex; gap: 10px; margin-top: 16px; }
.btn-respond { background: #0d6efd; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: background 0.2s; }
.btn-respond:hover { background: #0b5ed7; }
.filter-btn { background: #e9ecef; color: #495057; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.2s; text-decoration: none; }
.filter-btn:hover { background: #dee2e6; }
.filter-btn.active { background: #0d6efd; color: white; }
.badge-pending { background: #ffc107; color: #000; }
.badge-success { background: #198754; color: white; }
.badge-closed { background: #6c757d; color: white; }
</style>

<script>
function abrirModalRespuesta(id, tipo) {
    document.getElementById('resp-pregunta-id').value = id;
    document.getElementById('modal-respuesta-' + tipo).style.display = 'flex';
}

function cerrarModal(id) {
    document.getElementById(id).style.display = 'none';
}
</script>
</body>
</html>