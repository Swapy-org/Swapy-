<?php

$session = \Config\Services::session();

$solicitudesEntrega = $solicitudes_entrega ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapy | Panel Empleado</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/style/empleado_panel.css') ?>">
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">Swapy<span>.</span></div>
        <div class="brand-sub">Panel Empleado</div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">Principal</div>
        <a href="javascript:void(0);" class="nav-item active" onclick="go('inicio')" id="nav-inicio"><span class="nav-icon">🏠</span> Inicio</a>

        <div class="nav-label">Gestión</div>
        <a href="javascript:void(0);" class="nav-item" onclick="go('intercambios')" id="nav-intercambios"><span class="nav-icon">🔄</span> Reporte Intercambios<span class="nav-badge">12</span></a>
        <a href="javascript:void(0);" class="nav-item" onclick="go('solicitudes')" id="nav-solicitudes"><span class="nav-icon">🚚</span> Solicitudes de Entrega<span class="nav-badge"><?= count($solicitudesEntrega) ?></span></a>
        <a href="javascript:void(0);" class="nav-item" onclick="go('denuncias')" id="nav-denuncias"><span class="nav-icon">🚩</span> Denuncias<span class="nav-badge">3</span></a>

        <div class="nav-label">Soporte</div>
        <a href="javascript:void(0);" class="nav-item" onclick="go('errores')" id="nav-errores"><span class="nav-icon">⚠️</span> Errores Frecuentes</a>

        <div class="nav-label">Mi cuenta</div>
        <a href="javascript:void(0);" class="nav-item" onclick="go('perfil')" id="nav-perfil"><span class="nav-icon">👤</span> Mi Perfil</a>
    </nav>
    <div class="sidebar-footer">
        <a href="<?= base_url('empleado/logout') ?>" class="btn-logout" style="text-decoration: none; display: flex; align-items: center; justify-content: center; width: 100%;">🚪 Cerrar sesión</a>
    </div>
</aside>

<header class="topbar">
    <div style="display:flex;align-items:center;gap:14px;">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
        <div class="topbar-title" id="topbar-title">Inicio <span>/ Dashboard</span></div>
    </div>
    <div class="topbar-right">
        <div class="topbar-notif" title="Notificaciones">🔔<span class="notif-dot"></span></div>
        <div class="user-chip">
            <div class="user-avatar">JD</div>
            <div>
                <div class="user-name">Juan David</div>
                <div class="user-role">Empleado</div>
            </div>
        </div>
    </div>
</header>

<main class="main">
    <section class="section active" id="sec-inicio">
        <div class="section-head">
            <div class="eyebrow">✦ Bienvenido de vuelta</div>
            <h1>Hola, <em>Juan David</em> 👋</h1>
        </div>
        <div class="stats-row">
            <div class="stat-card c-orange">
                <div class="stat-icon">🔄</div>
                <div class="stat-label">Intercambios hoy</div>
                <div class="stat-value">12</div>
                <div class="stat-trend">▲ 3 más que ayer</div>
            </div>
            <div class="stat-card c-green">
                <div class="stat-icon">✅</div>
                <div class="stat-label">Entregas completas</div>
                <div class="stat-value">47</div>
                <div class="stat-trend">Semana actual</div>
            </div>
            <div class="stat-card c-red">
                <div class="stat-icon">🚩</div>
                <div class="stat-label">Denuncias activas</div>
                <div class="stat-value">3</div>
                <div class="stat-trend">Requieren atención</div>
            </div>
            <div class="stat-card c-blue">
                <div class="stat-icon">🚚</div>
                <div class="stat-label">Pendientes entrega</div>
                <div class="stat-value"><?= count($solicitudesEntrega) ?></div>
                <div class="stat-trend">Para hoy</div>
            </div>
        </div>
        <div class="dash-grid">
            <div class="card">
                <div class="card-header"><span class="card-title">Actividad reciente</span></div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="act-dot green"></div>
                        <div>
                            <div class="act-text">Intercambio #0047 entregado exitosamente.</div>
                            <div class="act-time">Hace 15 minutos</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="act-dot orange"></div>
                        <div>
                            <div class="act-text">Nueva solicitud de entrega asignada: Calle 80 #12-34.</div>
                            <div class="act-time">Hace 42 minutos</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="act-dot red"></div>
                        <div>
                            <div class="act-text">Denuncia #0011 marcada como alta prioridad.</div>
                            <div class="act-time">Hace 1 hora</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="act-dot blue"></div>
                        <div>
                            <div class="act-text">Intercambio #0046 en camino al punto B.</div>
                            <div class="act-time">Hace 2 horas</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="act-dot green"></div>
                        <div>
                            <div class="act-text">Intercambio #0045 completado — ambas partes confirmaron.</div>
                            <div class="act-time">Hace 3 horas</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><span class="card-title">Rendimiento semanal</span></div>
                <div class="progress-wrap">
                    <div>
                        <div class="progress-item-label"><span>Entregas completadas</span><span>47 / 60</span></div>
                        <div class="progress-bar"><div class="progress-fill fill-green" style="width:78%"></div></div>
                    </div>
                    <div>
                        <div class="progress-item-label"><span>Intercambios gestionados</span><span>38 / 50</span></div>
                        <div class="progress-bar"><div class="progress-fill fill-orange" style="width:76%"></div></div>
                    </div>
                    <div>
                        <div class="progress-item-label"><span>Satisfacción usuarios</span><span>94%</span></div>
                        <div class="progress-bar"><div class="progress-fill fill-blue" style="width:94%"></div></div>
                    </div>
                    <div>
                        <div class="progress-item-label"><span>Denuncias resueltas</span><span>8 / 11</span></div>
                        <div class="progress-bar"><div class="progress-fill fill-green" style="width:72%"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="sec-intercambios">
        <div class="section-head">
            <div class="eyebrow">✦ Gestión</div>
            <h1>Reporte de <em>Intercambios</em></h1>
        </div>
        <div class="stats-row" style="grid-template-columns:repeat(3,1fr);">
            <div class="stat-card c-orange">
                <div class="stat-label">Total del mes</div>
                <div class="stat-value">148</div>
            </div>
            <div class="stat-card c-green">
                <div class="stat-label">Completados</div>
                <div class="stat-value">121</div>
            </div>
            <div class="stat-card c-yellow">
                <div class="stat-label">En proceso</div>
                <div class="stat-value">27</div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Historial de intercambios</span>
                <div class="search-box"><span>🔍</span><input type="text" placeholder="Buscar intercambio..." oninput="filtrarTabla(this,'tbl-inter')"></div>
            </div>
            <table id="tbl-inter">
                <thead>
                    <tr><th>#</th><th>Origen</th><th>Destino</th><th>Productos</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#0047</td><td>Calle 80 #12-34</td><td>Carrera 15 #22-10</td><td>Bicicleta / Patines</td><td>16 jun 2026</td>
                        <td><span class="badge badge-entregado">✓ Entregado</span></td>
                        <td class="actions"><button class="btn-sm" title="Ver">👁️</button></td>
                    </tr>
                    <tr>
                        <td>#0046</td><td>Av. El Poblado #1-20</td><td>Calle 10 #43-00</td><td>Consola / Tablet</td><td>16 jun 2026</td>
                        <td><span class="badge badge-proceso">↻ En proceso</span></td>
                        <td class="actions"><button class="btn-sm" title="Ver">👁️</button></td>
                    </tr>
                    <tr>
                        <td>#0045</td><td>Cra 70 #34-12</td><td>Calle 33 #70-45</td><td>Ropa / Libros</td><td>15 jun 2026</td>
                        <td><span class="badge badge-entregado">✓ Entregado</span></td>
                        <td class="actions"><button class="btn-sm" title="Ver">👁️</button></td>
                    </tr>
                    <tr>
                        <td>#0044</td><td>Calle 50 #10-00</td><td>Cra 43 #1-200</td><td>Libros / Juguetes</td><td>14 jun 2026</td>
                        <td><span class="badge badge-cancelado">✗ Cancelado</span></td>
                        <td class="actions"><button class="btn-sm" title="Ver">👁️</button></td>
                    </tr>
                    <tr>
                        <td>#0043</td><td>Cra 65 #48-00</td><td>Calle 80 #65-30</td><td>Monitor / Teclado</td><td>13 jun 2026</td>
                        <td><span class="badge badge-entregado">✓ Entregado</span></td>
                        <td class="actions"><button class="btn-sm" title="Ver">👁️</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="section" id="sec-solicitudes">
        <div class="section-head">
            <div class="eyebrow">✦ Logística</div>
            <h1>Solicitudes de <em>Entrega</em></h1>
        </div>
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <span class="card-title">Entregas pendientes</span>
            </div>
            <table id="tbl-sol">
                <thead>
                    <tr><th>#</th><th>Producto</th><th>Solicitante</th><th>Dirección</th><th>Descripción del lugar</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <?php if (!empty($solicitudesEntrega)): ?>
                        <?php foreach ($solicitudesEntrega as $s): ?>
                            <tr id="fila-intercambio-<?= (int)$s['id_intercambio'] ?>">
                                <td>#<?= (int)$s['id_intercambio'] ?></td>
                                <td><?= htmlspecialchars($s['nombre_producto'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($s['correo_solicitante'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($s['direccion']) ?></td>
                                <td><?= htmlspecialchars($s['descripcion_lugar']) ?></td>
                                <td><span class="badge badge-pendiente">⏳ Pendiente</span></td>
                                <td class="actions">
                                    <button class="btn-sm" title="Aceptar" onclick="responderIntercambio(<?= (int)$s['id_intercambio'] ?>, 'aceptado')">✅</button>
                                    <button class="btn-sm danger" title="Rechazar" onclick="responderIntercambio(<?= (int)$s['id_intercambio'] ?>, 'rechazado')">✗</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align:center;color:var(--muted);">No hay solicitudes pendientes.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="section" id="sec-denuncias">
        <div class="section-head">
            <div class="eyebrow">✦ Seguridad</div>
            <h1>Centro de <em>Denuncias</em></h1>
        </div>
        <div class="stats-row" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px;">
            <div class="stat-card c-red"><div class="stat-label">Alta prioridad</div><div class="stat-value">2</div></div>
            <div class="stat-card c-yellow"><div class="stat-label">Media prioridad</div><div class="stat-value">1</div></div>
            <div class="stat-card c-green"><div class="stat-label">Resueltas este mes</div><div class="stat-value">8</div></div>
        </div>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Denuncias activas</span>
                <div class="search-box"><span>🔍</span><input type="text" placeholder="Buscar denuncia..." oninput="filtrarTabla(this,'tbl-den')"></div>
            </div>
            <table id="tbl-den">
                <thead>
                    <tr><th>#</th><th>Denunciante</th><th>Motivo</th><th>Intercambio</th><th>Prioridad</th><th>Estado</th><th>Acciones</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>D-011</td><td>Pedro R.</td><td>Producto en mal estado</td><td>#0039</td>
                        <td><span class="badge badge-alta">🔴 Alta</span></td><td><span class="badge badge-pendiente">⏳ Pendiente</span></td>
                        <td class="actions">
                            <button class="btn-sm" onclick="openModal('modal-denuncia')">📋</button>
                            <button class="btn-sm" onclick="toastMsg('Marcada como resuelta ✓')">✅</button>
                        </td>
                    </tr>
                    <tr>
                        <td>D-010</td><td>Sandra Q.</td><td>No llegó el paquete</td><td>#0041</td>
                        <td><span class="badge badge-alta">🔴 Alta</span></td><td><span class="badge badge-proceso">↻ En revisión</span></td>
                        <td class="actions">
                            <button class="btn-sm" onclick="openModal('modal-denuncia')">📋</button>
                            <button class="btn-sm" onclick="toastMsg('Marcada como resuelta ✓')">✅</button>
                        </td>
                    </tr>
                    <tr>
                        <td>D-009</td><td>Felipe M.</td><td>Trato irrespetuoso</td><td>#0038</td>
                        <td><span class="badge badge-media">🟡 Media</span></td><td><span class="badge badge-pendiente">⏳ Pendiente</span></td>
                        <td class="actions">
                            <button class="btn-sm" onclick="openModal('modal-denuncia')">📋</button>
                            <button class="btn-sm" onclick="toastMsg('Marcada como resuelta ✓')">✅</button>
                        </td>
                    </tr>
                    <tr>
                        <td>D-008</td><td>Camila T.</td><td>Retraso excesivo</td><td>#0035</td>
                        <td><span class="badge badge-baja">🟢 Baja</span></td><td><span class="badge badge-resuelta">✓ Resuelta</span></td>
                        <td class="actions"><button class="btn-sm">👁️</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="section" id="sec-errores">
        <div class="section-head">
            <div class="eyebrow">✦ Soporte</div>
            <h1>Errores <em>Frecuentes</em></h1>
        </div>
        <div style="display:grid;gap:14px;">
            <div class="card">
                <div class="card-header" style="cursor:pointer;" onclick="toggleError(this)">
                    <div><span style="color:var(--red);font-size:18px;margin-right:10px;">⚠️</span><span class="card-title">El intercambio no aparece en mi panel</span></div>
                    <span class="err-arrow" style="color:var(--muted);font-size:18px;transition:transform .2s;">▼</span>
                </div>
                <div class="err-body" style="padding:20px 22px;display:none;color:var(--muted);font-size:14px;line-height:1.7;border-top:1px solid var(--border);">
                    Verifica que el administrador haya asignado el intercambio a tu usuario. Si el problema persiste, recarga la página y revisa que tu sesión esté activa. Contacta soporte si después de 10 minutos el intercambio sigue sin aparecer.
                </div>
            </div>
            <div class="card">
                <div class="card-header" style="cursor:pointer;" onclick="toggleError(this)">
                    <div><span style="color:var(--yellow);font-size:18px;margin-right:10px;">⚠️</span><span class="card-title">No puedo marcar una entrega como completada</span></div>
                    <span class="err-arrow" style="color:var(--muted);font-size:18px;transition:transform .2s;">▼</span>
                </div>
                <div class="err-body" style="padding:20px 22px;display:none;color:var(--muted);font-size:14px;line-height:1.7;border-top:1px solid var(--border);">
                    Para marcar una entrega como completada, ambas partes deben haber confirmado la recepción de sus productos. Si una de las partes no ha confirmado, debes contactarla directamente o escalar a soporte.
                </div>
            </div>
            <div class="card">
                <div class="card-header" style="cursor:pointer;" onclick="toggleError(this)">
                    <div><span style="color:var(--orange);font-size:18px;margin-right:10px;">⚠️</span><span class="card-title">La dirección de entrega no existe o está incorrecta</span></div>
                    <span class="err-arrow" style="color:var(--muted);font-size:18px;transition:transform .2s;">▼</span>
                </div>
                <div class="err-body" style="padding:20px 22px;display:none;color:var(--muted);font-size:14px;line-height:1.7;border-top:1px solid var(--border);">
                    Comunícate con el usuario a través del chat interno de la plataforma para validar la dirección antes de salir. Si la dirección está definitivamente equivocada, repórtalo desde la sección de denuncias indicando el número de solicitud.
                </div>
            </div>
            <div class="card">
                <div class="card-header" style="cursor:pointer;" onclick="toggleError(this)">
                    <div><span style="color:var(--red);font-size:18px;margin-right:10px;">⚠️</span><span class="card-title">Mi pago no fue acuditado correctamente</span></div>
                    <span class="err-arrow" style="color:var(--muted);font-size:18px;transition:transform .2s;">▼</span>
                </div>
                <div class="err-body" style="padding:20px 22px;display:none;color:var(--muted);font-size:14px;line-height:1.7;border-top:1px solid var(--border);">
                    Los pagos se procesan dentro de las 24 horas siguientes a la confirmación del intercambio. Asegúrate de que tu número de cuenta esté correcto en tu perfil. Si después de 48 horas no ves el pago, abre un ticket de soporte con el número del intercambio.
                </div>
            </div>
            <div class="card">
                <div class="card-header" style="cursor:pointer;" onclick="toggleError(this)">
                    <div><span style="color:var(--blue);font-size:18px;margin-right:10px;">⚠️</span><span class="card-title">Usuario no está en casa al momento de la entrega</span></div>
                    <span class="err-arrow" style="color:var(--muted);font-size:18px;transition:transform .2s;">▼</span>
                </div>
                <div class="err-body" style="padding:20px 22px;display:none;color:var(--muted);font-size:14px;line-height:1.7;border-top:1px solid var(--border);">
                    Intenta contactar al usuario por el chat de la plataforma. Si después de 20 minutos no hay respuesta, marca la entrega con el estado "Intento fallido" y el sistema reagendará automáticamente. Nunca dejes el paquete sin confirmación.
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="sec-perfil">
        <div class="section-head">
            <div class="eyebrow">✦ Mi cuenta</div>
            <h1>Mi <em>Perfil</em></h1>
        </div>
        <div class="profile-hero">
            <div class="profile-avatar-big">JD</div>
            <div class="profile-info">
                <h2>Juan David López</h2>
                <p>juandavid@correo.com &nbsp;·&nbsp; Medellín, Colombia</p>
                <div class="profile-tags">
                    <span class="ptag">🚚 Empleado</span>
                    <span class="ptag">⭐ Activo</span>
                    <span class="ptag">✓ Verificado</span>
                    <span class="ptag">47 entregas</span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><span class="card-title">Información personal</span></div>
            <div style="padding:24px;">
                <div class="profile-grid">
                    <div class="profile-field"><label class="field-label">Nombre completo</label><input class="field-input" type="text" value="Juan David López"></div>
                    <div class="profile-field"><label class="field-label">Correo electrónico</label><input class="field-input" type="email" value="juandavid@correo.com"></div>
                    <div class="profile-field"><label class="field-label">Teléfono</label><input class="field-input" type="tel" value="+57 300 000 0000"></div>
                    <div class="profile-field"><label class="field-label">Ciudad</label><input class="field-input" type="text" value="Medellín"></div>
                    <div class="profile-field"><label class="field-label">Número de cuenta (pagos)</label><input class="field-input" type="text" value="••••••••9012"></div>
                    <div class="profile-field"><label class="field-label">Medio de transporte</label><input class="field-input" type="text" value="Moto"></div>
                </div>
                <div style="margin-top:20px;">
                    <button class="btn-primary" onclick="toastMsg('Cambios guardados correctamente ✓')">💾 Guardar cambios</button>
                </div>
            </div>
        </div>
        <div class="card" style="margin-top:20px;">
            <div class="card-header"><span class="card-title">Cambiar contraseña</span></div>
            <div style="padding:24px;">
                <div class="profile-grid">
                    <div class="profile-field"><label class="field-label">Contraseña actual</label><input class="field-input" type="password" placeholder="••••••••"></div>
                    <div class="profile-field"><label class="field-label">Nueva contraseña</label><input class="field-input" type="password" placeholder="Mínimo 8 caracteres"></div>
                </div>
                <div style="margin-top:20px;">
                    <button class="btn-primary" onclick="toastMsg('Contraseña actualizada ✓')">🔒 Actualizar contraseña</button>
                </div>
            </div>
        </div>
    </section>
</main>

<div class="modal-bg" id="modal-nueva">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-nueva')">✕</button>
        <h3>Nueva Solicitud</h3>
        <p class="sub">Registra los datos de la entrega.</p>
        <div class="form-group"><label>Solicitante</label><input type="text" placeholder="Nombre del usuario"></div>
        <div class="form-group"><label>Dirección de recogida</label><input type="text" placeholder="Ej: Calle 80 #12-34"></div>
        <div class="form-group"><label>Dirección de entrega</label><input type="text" placeholder="Ej: Carrera 15 #22-10"></div>
        <div class="form-group"><label>Hora límite</label><input type="time"></div>
        <button class="btn-primary" style="width:100%;margin-top:6px;" onclick="closeModal('modal-nueva');toastMsg('Solicitud creada correctamente ✓')">💾 Crear solicitud</button>
    </div>
</div>

<div class="modal-bg" id="modal-denuncia">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-denuncia')">✕</button>
        <h3>Detalle de Denuncia</h3>
        <p class="sub">Denuncia #D-011 — Alta prioridad</p>
        <div class="form-group"><label>Denunciante</label><input type="text" value="Pedro Rodríguez" readonly style="opacity:.7;"></div>
        <div class="form-group"><label>Motivo</label><textarea readonly style="opacity:.7;">El producto recibido llegó en condiciones diferentes a las acordadas. La pantalla presentaba una grieta no mencionada en el intercambio #0039.</textarea></div>
        <div class="form-group"><label>Respuesta del empleado</label><textarea placeholder="Escribe tu observación o solución..."></textarea></div>
        <div style="display:flex;gap:10px;">
            <button class="btn-primary" style="flex:1;" onclick="closeModal('modal-denuncia');toastMsg('Respuesta enviada ✓')">📨 Enviar respuesta</button>
            <button style="flex:1;padding:12px;background:rgba(34,197,94,0.12);border:1px solid rgba(34,197,94,0.25);color:#86efac;border-radius:var(--radius-md);cursor:pointer;font-family:'Syne',sans-serif;font-weight:700;font-size:14px;" onclick="closeModal('modal-denuncia');toastMsg('Denuncia marcada como resuelta ✓')">✅ Resolver</button>
        </div>
    </div>
</div>

<div id="toast" style="
    position:fixed;bottom:28px;right:28px;
    background:var(--brown-card);
    border:1px solid var(--orange);
    color:var(--text);
    padding:12px 20px;
    border-radius:var(--radius-md);
    font-size:14px;
    font-weight:500;
    z-index:9999;
    transform:translateY(80px);
    opacity:0;
    transition:all .3s ease;
    pointer-events:none;
    box-shadow:0 8px 24px rgba(0,0,0,0.4);"></div>

<script src="<?= base_url('assets/script/empleado_panel.js') ?>"></script>
<script>
function responderIntercambio(idIntercambio, accion)
{
    var datos = new FormData();
    datos.append('id_intercambio', idIntercambio);
    datos.append('accion', accion);

    fetch('<?= base_url('empleado/intercambio/estado') ?>', {
        method: 'POST',
        body: datos
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (!data.ok) throw new Error(data.error || 'No se pudo actualizar la solicitud.');
        var fila = document.getElementById('fila-intercambio-' + idIntercambio);
        if (fila) fila.remove();
        if (typeof toastMsg === 'function') {
            toastMsg(accion === 'aceptado' ? 'Solicitud aceptada ✓' : 'Solicitud rechazada ✗');
        }
    })
    .catch(function(err) {
        alert('⚠️ ' + err.message);
    });
}
</script>
</body>
</html>