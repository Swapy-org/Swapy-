<?php

$session = \Config\Services::session();

if (
    !$session->get('isLoggedIn') ||
    strtolower((string)$session->get('rol')) !== 'administrador'
) {
    return redirect()->to(base_url('?login_error=acceso'));
}

$model = new \App\Models\UsuarioModel();

$usuarios = $model->obtenerTodos();
$tiposDoc = $model->obtenerTiposDoc();

$mensajes = [
    'creado' => ['success', '✅ Usuario creado correctamente.'],
    'empleado_creado' => ['success', '✅ Registro de empleado creado exitosamente.'],
    'editado' => ['success', '✅ Usuario actualizado correctamente.'],
    'eliminado' => ['success', '✅ Usuario eliminado correctamente.'],
    'activado' => ['success', '✅ Usuario activado correctamente.'],
    'desactivado' => ['success', '✅ Usuario desactivado correctamente.'],
    'correo_duplicado' => ['error', '❌ Ese correo ya está registrado.'],
    'documento_duplicado' => ['error', '❌ Ese número de documento ya está registrado.'],
    'error_campos' => ['error', '❌ Debes completar todos los campos obligatorios.'],
    'error_correo' => ['error', '❌ El formato del correo no es válido.'],
    'error_contrasena' => ['error', '❌ La contraseña debe tener mínimo 6 caracteres.'],
    'error_contrasena_actual' => ['error', '❌ La contraseña anterior no es correcta.'],
    'error_contrasena_no_coincide' => ['error', '❌ Las contraseñas no coinciden.'],
    'error_empleado' => ['error', '❌ No fue posible crear el registro del empleado.'],
    'error' => ['error', '❌ Ocurrió un error inesperado.'],
];

$msg = $_GET['msg'] ?? null;

$totalUsuarios = $model->contarTotal();
$usuariosActivos = $model->contarActivos();
$usuariosInactivos = $model->contarInactivos();
$administradores = $model->contarAdmins();
$empleados = $model->contarEmpleados();

$rolesCount = [];
$verificadosCount = 0;
$noVerificadosCount = 0;

foreach ($usuarios as $u) {

    $rol = (string)($u['rol'] ?? 'Sin rol');
    $rolesCount[$rol] = ($rolesCount[$rol] ?? 0) + 1;

    $esVerificado =
        strtoupper((string)($u['verificado'] ?? '')) === 'SI'
        || $u['verificado'] == '1';

    if ($esVerificado) {
        $verificadosCount++;
    } else {
        $noVerificadosCount++;
    }
}

$rolesLabels = array_keys($rolesCount);
$rolesValues = array_values($rolesCount);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapy | Panel Administrador</title>

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=DM+Sans:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/style/admin.css') ?>">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

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
                <li><a href="<?= base_url('admin') ?>" class="nav-item active">👥 Personas Registradas</a></li>
                <li><a href="<?= base_url('admin_preguntas') ?>" class="nav-item">❓ Preguntas Frecuentes</a></li>
                <li><a href="<?= base_url('admin_denuncias') ?>" class="nav-item">🚩 Denuncias</a></li>
                <li><a href="<?= base_url('admin_solicitudes') ?>" class="nav-item">🚚 Solicitudes de Entrega</a></li>
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
                <input type="text" id="global-search" class="search-input-topbar" placeholder="Buscar correo, rol, estado..." oninput="filtrar(this.value)" style="padding-left:38px; border-radius:999px;">
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

            <?php if ($msg !== null && isset($mensajes[$msg])): ?>
                <?php [$tipoMensaje, $textoMensaje] = $mensajes[$msg]; ?>
                <div class="alert alert-<?= esc($tipoMensaje) ?>"><?= esc($textoMensaje) ?></div>
            <?php endif; ?>

            <div class="section-header">
                <div>
                    <span class="eyebrow">Usuarios</span>
                    <h1>Personas <span>Registradas</span></h1>
                </div>
            </div>

            <div class="stats-grid">

                <div class="stat-card blue">
                    <span class="stat-icon">👥</span>
                    <div class="stat-label">Total</div>
                    <div class="stat-number"><?= $totalUsuarios ?></div>
                </div>

                <div class="stat-card green">
                    <span class="stat-icon">🟢</span>
                    <div class="stat-label">Activos</div>
                    <div class="stat-number"><?= $usuariosActivos ?></div>
                </div>

                <div class="stat-card red">
                    <span class="stat-icon">🔴</span>
                    <div class="stat-label">Inactivos</div>
                    <div class="stat-number"><?= $usuariosInactivos ?></div>
                </div>

                <div class="stat-card purple">
                    <span class="stat-icon">🛡️</span>
                    <div class="stat-label">Administradores</div>
                    <div class="stat-number"><?= $administradores ?></div>
                </div>

                <div class="stat-card amber">
                    <span class="stat-icon">🧑‍💼</span>
                    <div class="stat-label">Empleados</div>
                    <div class="stat-number"><?= $empleados ?></div>
                </div>

            </div>

            <div class="charts-grid">

                <div class="chart-card">
                    <h3>Distribución por Rol</h3>
                    <div class="chart-wrap"><canvas id="chart-roles"></canvas></div>
                </div>

                <div class="chart-card">
                    <h3>Estado de Cuentas</h3>
                    <div class="chart-wrap"><canvas id="chart-estado"></canvas></div>
                </div>

                <div class="chart-card">
                    <h3>Verificación de Correo</h3>
                    <div class="chart-wrap"><canvas id="chart-verificado"></canvas></div>
                </div>

            </div>

            <div class="table-container">

                <div class="table-toolbar">

                    <input type="text" class="search-input" id="table-search" placeholder="🔍 Buscar correo, rol, estado..." oninput="filtrar(this.value)">

                    <div class="filter-chips">
                        <button type="button" class="filter-chip active" onclick="setQuickFilter('', this)">Todos</button>
                        <button type="button" class="filter-chip" onclick="setQuickFilter('Administrador', this)">Administradores</button>
                        <button type="button" class="filter-chip" onclick="setQuickFilter('Empleado', this)">Empleados</button>
                        <button type="button" class="filter-chip" onclick="setQuickFilter('Activo', this)">Activos</button>
                        <button type="button" class="filter-chip" onclick="setQuickFilter('Inactivo', this)">Inactivos</button>
                    </div>

                    <button type="button" class="add-user-btn" onclick="abrirModal('modal-crear')">➕ Agregar Usuario</button>

                </div>


                <?php if (!empty($usuarios)): ?>

                    <table class="users-table" id="tabla">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo Doc.</th>
                                <th>Número Documento</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Verificado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($usuarios as $f): ?>

                            <?php
                            $esActivo = strtolower((string)($f['estado'] ?? '')) === 'activo';
                            $verificado = strtoupper((string)($f['verificado'] ?? '')) === 'SI' || $f['verificado'] == '1';
                            $rolLower = strtolower((string)($f['rol'] ?? ''));
                            $rolClases = ['administrador' => 'badge-admin', 'empleado' => 'badge-empleado', 'cliente' => 'badge-cliente'];
                            $rolClase = $rolClases[$rolLower] ?? 'badge-cliente';
                            $idU = (int)$f['id_usuario'];
                            $correoJs = esc($f['correo'], 'js');
                            $hrefActivar = base_url('admin/cambiar_estado?id=' . $idU . '&estado=Activo');
                            $hrefDesactivar = base_url('admin/cambiar_estado?id=' . $idU . '&estado=Inactivo');
                            ?>

                            <tr>
                                <td><?= $idU ?></td>
                                <td><?= esc($f['tipo_doc'] ?? 'N/A') ?></td>
                                <td><?= esc($f['numero_documento'] ?? 'N/A') ?></td>
                                <td><?= esc($f['correo']) ?></td>
                                <td><span class="badge <?= esc($rolClase) ?>"><?= esc($f['rol']) ?></span></td>
                                <td><span class="badge <?= $esActivo ? 'badge-activo' : 'badge-inactivo' ?>"><?= esc($f['estado']) ?></span></td>
                                <td><span class="badge <?= $verificado ? 'badge-si' : 'badge-no' ?>"><?= $verificado ? '✓ Sí' : '✗ No' ?></span></td>
                                <td class="actions">
                                    <button type="button" class="btn-action btn-edit" title="Editar" onclick="abrirEditar('<?= $idU ?>', '<?= esc($f['pkfk_id_doc']) ?>', '<?= esc($f['numero_documento'] ?? '') ?>', '<?= $correoJs ?>', '<?= esc($f['rol']) ?>')">✏️</button>
                                    <?php if ($esActivo): ?>
                                    <a href="<?= $hrefDesactivar ?>" title="Desactivar"><button type="button" class="btn-action btn-deactivate">🔴</button></a>
                                    <?php else: ?>
                                    <a href="<?= $hrefActivar ?>" title="Activar"><button type="button" class="btn-action btn-activate">🟢</button></a>
                                    <?php endif; ?>
                                    <button type="button" class="btn-action btn-delete" title="Eliminar" onclick="confirmarEliminar(<?= $idU ?>, '<?= $correoJs ?>')">🗑️</button>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                <?php else: ?>

                    <div class="empty-state">
                        <div class="empty-icon">👤</div>
                        <p>No hay usuarios registrados aún.</p>
                    </div>

                <?php endif; ?>

            </div>

        </main>

    </div>

</div>


<div id="modal-crear" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Agregar Usuario</h2>
            <button type="button" class="close-btn" onclick="cerrarModal('modal-crear')">✖</button>
        </div>
        <div class="modal-body">
            <form action="<?= base_url('admin/crear') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label>Tipo de Documento</label>
                    <select name="documento" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($tiposDoc as $tipo): ?>
                            <option value="<?= esc($tipo['id_doc']) ?>"><?= esc($tipo['tipo_doc']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Número de Documento</label>
                    <input type="text" name="numero_documento" placeholder="Ej: 100200300" required>
                </div>
                <div class="form-group">
                    <label>Rol</label>
                    <select name="rol" id="crear-rol" onchange="mostrarCamposEmpleado()" required>
                        <option value="">Seleccione</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Empleado">Empleado</option>
                        <option value="Cliente">Cliente</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="correo" placeholder="correo@ejemplo.com" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="contrasena" id="crear-contrasena" placeholder="Mínimo 6 caracteres" minlength="6" required>
                </div>
                <div class="form-group">
                    <label>Confirmar Contraseña</label>
                    <input type="password" name="confirmar_contrasena" placeholder="Repita la contraseña" minlength="6" required>
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
                <button type="submit" class="save-btn">💾 Guardar Usuario</button>
            </form>
        </div>
    </div>
</div>


<div id="modal-editar" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Editar Usuario</h2>
            <button type="button" class="close-btn" onclick="cerrarModal('modal-editar')">✖</button>
        </div>
        <div class="modal-body">
            <form action="<?= base_url('admin/editar') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="e-id">
                <div class="form-group">
                    <label>Tipo de Documento</label>
                    <select name="documento" id="e-doc" required>
                        <?php foreach ($tiposDoc as $tipo): ?>
                            <option value="<?= esc($tipo['id_doc']) ?>"><?= esc($tipo['tipo_doc']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Número de Documento</label>
                    <input type="text" name="numero_documento" id="e-numero-documento" required>
                </div>
                <div class="form-group">
                    <label>Rol</label>
                    <select name="rol" id="e-rol" required>
                        <option value="Administrador">Administrador</option>
                        <option value="Empleado">Empleado</option>
                        <option value="Cliente">Cliente</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="correo" id="e-correo" required>
                </div>
                <div class="form-group">
                    <label>Contraseña Anterior</label>
                    <input type="password" name="contrasena_anterior" placeholder="Contraseña actual del usuario" autocomplete="current-password">
                </div>
                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" name="contrasena" minlength="6" placeholder="Dejar vacío para conservar la actual" autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label>Confirmar Nueva Contraseña</label>
                    <input type="password" name="confirmar_contrasena" minlength="6" placeholder="Repita la nueva contraseña" autocomplete="new-password">
                </div>
                <button type="submit" class="save-btn">💾 Guardar Cambios</button>
            </form>
        </div>
    </div>
</div>


<div id="modal-confirmar" class="modal-overlay">
    <div class="modal-content" style="max-width:380px;">
        <div class="confirm-body">
            <div class="confirm-icon">🗑️</div>
            <h3>¿Eliminar usuario?</h3>
            <p id="confirm-txt">Esta acción no se puede deshacer.</p>
            <div class="confirm-btns">
                <button type="button" class="btn-cancel" onclick="cerrarModal('modal-confirmar')">Cancelar</button>
                <a id="confirm-href" href="#" style="flex:1;">
                    <button type="button" class="btn-confirm-del" style="width:100%;">Sí, eliminar</button>
                </a>
            </div>
        </div>
    </div>
</div>


<script>

function confirmarEliminar(id, correo) {
    document.getElementById('confirm-txt').innerText = '¿Estás seguro de que deseas eliminar a ' + correo + '?';
    document.getElementById('confirm-href').href = '<?= base_url("admin/eliminar?id=") ?>' + id;
    abrirModal('modal-confirmar');
}

function mostrarCamposEmpleado() {
    const rol = document.getElementById('crear-rol').value;
    console.log('Rol seleccionado:', rol);
}

function abrirEditar(id, documento, numeroDocumento, correo, rol) {
    document.getElementById('e-id').value = id;
    document.getElementById('e-doc').value = documento;
    document.getElementById('e-numero-documento').value = numeroDocumento;
    document.getElementById('e-correo').value = correo;
    document.getElementById('e-rol').value = rol;
    abrirModal('modal-editar');
}

function setQuickFilter(term, btnEl) {
    const tableSearch = document.getElementById('table-search');
    const globalSearch = document.getElementById('global-search');
    tableSearch.value = term;
    if (globalSearch) globalSearch.value = term;
    if (typeof filtrar === 'function') filtrar(term);
    document.querySelectorAll('.filter-chip').forEach(chip => chip.classList.remove('active'));
    if (btnEl) btnEl.classList.add('active');
}

document.addEventListener('DOMContentLoaded', function () {

    const paleta = { marigold: '#E0A458', slate: '#4C7089', leaf: '#3F6C51', brick: '#B14A3B', gray: '#CBD1C4' };
    const rolesLabels = <?= json_encode($rolesLabels) ?>;
    const rolesValues = <?= json_encode($rolesValues) ?>;
    const rolColorMap = { 'Administrador': paleta.marigold, 'Empleado': paleta.slate, 'Cliente': paleta.leaf };
    const rolesColors = rolesLabels.map(r => rolColorMap[r] || paleta.gray);

    new Chart(document.getElementById('chart-roles'), {
        type: 'doughnut',
        data: { labels: rolesLabels, datasets: [{ data: rolesValues, backgroundColor: rolesColors, borderWidth: 0 }] },
        options: { maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { boxWidth: 8, font: { size: 11 } } } } }
    });

    new Chart(document.getElementById('chart-estado'), {
        type: 'doughnut',
        data: { labels: ['Activos', 'Inactivos'], datasets: [{ data: [<?= (int)$usuariosActivos ?>, <?= (int)$usuariosInactivos ?>], backgroundColor: [paleta.leaf, paleta.brick], borderWidth: 0 }] },
        options: { maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { boxWidth: 8, font: { size: 11 } } } } }
    });

    new Chart(document.getElementById('chart-verificado'), {
        type: 'bar',
        data: { labels: ['Verificados', 'No verificados'], datasets: [{ data: [<?= (int)$verificadosCount ?>, <?= (int)$noVerificadosCount ?>], backgroundColor: [paleta.leaf, paleta.brick], borderRadius: 6, barThickness: 34 }] },
        options: {
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { stepSize: 1 }, grid: { color: '#E1E5DD' } },
                y: { grid: { display: false } }
            }
        }
    });

});

</script>


<script src="<?= base_url('assets/script/admin_script.js') ?>" defer></script>

</body>

</html>