<?php
$session = \Config\Services::session();
if (!$session->get('isLoggedIn')) {
    return redirect()->to(base_url('?login_error=acceso'))->send();
    exit();
}

$idProductoChat = (int) (service('request')->getGet('producto') ?? 0);
$productoChat = null;

if ($idProductoChat > 0) {
    $productoModel = new \App\Models\ProductoModel();
    $productoChat = $productoModel->find($idProductoChat);
}
$soporteMsg = session()->getFlashdata('soporte_msg');
$soporteErr = session()->getFlashdata('soporte_error');

/*
|--------------------------------------------------------------------------
| MIS PREGUNTAS / DENUNCIAS (para la pestaña "Mis Preguntas" del soporte)
|--------------------------------------------------------------------------
*/
$soporteModel = new \App\Models\SoporteModel();
$idUsuarioActual = (int) ($session->get('id_usuario') ?? 0);
$misPreguntas = $idUsuarioActual > 0 ? $soporteModel->obtenerPreguntasPorUsuario($idUsuarioActual) : [];
$misDenuncias = $idUsuarioActual > 0 ? $soporteModel->obtenerDenunciasPorUsuario($idUsuarioActual) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Mensajes | Swapy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/style/chats_style.css') ?>">
</head>

<body>
<div id="app">

    <div id="sidebar">
        <div id="sidebar-header">
            <span id="sidebar-title">Mensajes</span>
            <div id="sidebar-icons">
                <button class="icon-btn" title="Nuevo chat">✏️</button>
                <button class="icon-btn" title="Opciones">⋯</button>
            </div>
        </div>

        <div id="search-box">
            <span id="search-icon">🔍</span>
            <input type="text" placeholder="Buscar conversaciones…" />
        </div>

        <div id="chats-label">Recientes</div>

        <div id="chat-list">
            <div class="chat-item">
                <div class="chat-info">
                    <div class="chat-name">Cargando conversaciones…</div>
                </div>
            </div>
        </div>

        <div id="support-section">
            <button id="support-open-btn" onclick="openSupport()">
                🎧 Soporte Técnico
                <span class="support-badge">!</span>
            </button>
        </div>
    </div>

    <div id="main">
        <div id="chat-header">
            <button onclick="window.location.href='<?= base_url('index2') ?>'" class="back-btn" title="Volver">←</button>

            <div class="avatar">
                <div class="avatar-img" id="header-avatar"><?= $productoChat ? strtoupper(substr($productoChat['nombre_producto'], 0, 1)) : '?' ?></div>
                <div class="online-dot"></div>
            </div>

            <div id="header-info">
                <div id="header-name"><?= $productoChat ? htmlspecialchars($productoChat['nombre_producto']) : 'Selecciona un producto' ?></div>
                <div id="header-status">Activo ahora</div>
            </div>

            <div id="header-actions">
                <button type="button" class="btn-header-intercambio" onclick="iniciarConfirmacionIntercambio()">
                    🤝 Confirmar intercambio
                </button>
                <button class="header-btn" title="Llamada">📞</button>
                <button class="header-btn" title="Videollamada">📹</button>
                <button class="header-btn" title="Información">ℹ️</button>
            </div>
        </div>

        <div id="messages">
            <?php if ($productoChat): ?>
            <div class="msg-group in">
                <div class="msg-bubble">
                    ¡Hola! Estoy interesado(a) en hacer un intercambio por <strong><?= htmlspecialchars($productoChat['nombre_producto']) ?></strong>.
                    Cuéntame, ¿qué buscas a cambio?
                </div>
                <div class="msg-time">Ahora</div>
            </div>
            <?php else: ?>
            <div class="no-results" style="margin: auto; text-align:center; color: var(--text-2);">
                <p>Selecciona un producto desde el marketplace para empezar a chatear.</p>
            </div>
            <?php endif; ?>
        </div>

        <div id="input-area">
            <button class="input-side-btn" title="Adjuntar">➕</button>
            <button class="input-side-btn" title="Foto">📷</button>
            <button class="input-side-btn" title="Imagen">🖼️</button>
            <button class="input-side-btn" title="Audio">🎤</button>

            <div id="msg-input-wrap">
                <textarea id="msg-input" placeholder="Escribe un mensaje…" rows="1" <?= $productoChat ? '' : 'disabled' ?>></textarea>
            </div>

            <button id="send-btn" disabled title="Enviar">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                    <line x1="22" y1="2" x2="11" y2="13"/>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<div id="modal-confirmar-intercambio" class="modal-bg">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Confirmar intercambio</h3>
            <span class="close-btn" onclick="closeModal('modal-confirmar-intercambio')">&times;</span>
        </div>
        <p>¿Estás seguro de realizar un intercambio?</p>
        <div class="modal-footer">
            <button type="button" class="btn-secundario" onclick="closeModal('modal-confirmar-intercambio')">Cancelar</button>
            <button type="button" class="btn-primario" onclick="esperarConfirmacionIntercambio()">Confirmar intercambio</button>
        </div>
    </div>
</div>

<div id="modal-esperando-intercambio" class="modal-bg">
    <div class="modal-box waiting-chat-box">
        <div class="chat-loader" aria-hidden="true"></div>
        <h3>Esperando confirmación</h3>
        <p>Esperando que el otro intercambiario acepte.</p>
        <strong>Continuando en <span id="chat-countdown">10</span> segundos</strong>
    </div>
</div>

<!-- MODAL QUE CONTIENE EL IFRAME DE INDEXX A PANTALLA COMPLETA -->
<div id="modal-indexx" class="modal-bg">
    <div class="indexx-modal-box">
        <button type="button" class="indexx-close" onclick="closeModal('modal-indexx')" title="Cerrar">&times;</button>
        <iframe
            id="indexx-iframe"
            src="<?= base_url('indexx?producto=' . $idProductoChat) ?>"
            title="Solicitud de Swapy"
            class="indexx-frame"
        ></iframe>
    </div>
</div>

<!-- MODAL DE DATOS DE INTERCAMBIO (Formulario final, no usado en el flujo actual del iframe) -->
<div id="modal-datos-intercambio" class="modal-bg">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Datos del intercambio</h3>
            <span class="close-btn" onclick="closeModal('modal-datos-intercambio')">&times;</span>
        </div>
        <p class="simulation-note">Simulación del proceso de intercambio</p>
        <form id="form-datos-intercambio" onsubmit="guardarDatosIntercambio(event)">
            <input type="hidden" id="id-intercambio">
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" required placeholder="Calle, carrera y número">
            </div>
            <div class="form-group">
                <label for="descripcion_lugar">Descripción del lugar</label>
                <textarea id="descripcion_lugar" rows="3" required placeholder="Punto de referencia o indicaciones"></textarea>
            </div>
            <div class="form-group">
                <label for="descripcion_producto">Descripción del producto</label>
                <textarea id="descripcion_producto" rows="3" required placeholder="Estado y características del producto"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secundario" onclick="closeModal('modal-datos-intercambio')">Cancelar</button>
                <button type="submit" class="btn-primario">Guardar datos</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DE SOPORTE TÉCNICO -->
<div id="support-modal">
    <div id="support-box">
        <div id="support-header">
            <h3>🎧 Soporte Técnico</h3>
            <button id="close-support" onclick="closeSupport()" title="Cerrar">✖</button>
        </div>

        <div id="form-tabs">
            <button type="button" class="form-tab active" data-tab="faq" onclick="switchSupportTab('faq')">❓ Preguntas</button>
            <button type="button" class="form-tab" data-tab="report" onclick="switchSupportTab('report')">🚩 Reportar</button>
            <button type="button" class="form-tab" data-tab="mias" onclick="switchSupportTab('mias')">📋 Mis Preguntas</button>
        </div>

        <form id="support-form" action="<?= base_url('soporte/enviar') ?>" method="POST">
            <?= csrf_field() ?>

            <div id="tab-faq" class="support-tab active">
                <div>
                    <label>Nombre</label>
                    <input type="text" name="faq_nombre" value="<?= htmlspecialchars($session->get('nombre_usuario') ?? '') ?>" placeholder="Tu nombre" required>
                </div>
                <div>
                    <label>Correo</label>
                    <input type="email" name="faq_correo" value="<?= htmlspecialchars($session->get('correo_usuario') ?? '') ?>" placeholder="correo@email.com" required>
                </div>
                <div>
                    <label>Tu pregunta</label>
                    <textarea name="faq_pregunta" rows="5" placeholder="¿En qué podemos ayudarte?" required></textarea>
                </div>
                <button type="submit" name="tipo_soporte" value="pregunta" class="submit-btn">Enviar pregunta →</button>
            </div>

            <div id="tab-report" class="support-tab">
                <div>
                    <label>Nombre</label>
                    <input type="text" name="report_nombre" value="<?= htmlspecialchars($session->get('nombre_usuario') ?? '') ?>" placeholder="Tu nombre">
                </div>
                <div>
                    <label>Correo</label>
                    <input type="email" name="report_correo" value="<?= htmlspecialchars($session->get('correo_usuario') ?? '') ?>" placeholder="correo@email.com">
                </div>
                <div>
                    <label>Tipo de denuncia</label>
                    <select name="report_tipo">
                        <option value="">Selecciona un tipo…</option>
                        <option value="conducta">Conducta inapropiada</option>
                        <option value="fraude">Fraude o estafa</option>
                        <option value="contenido">Contenido ilegal o abusivo</option>
                        <option value="spam">Spam o acoso</option>
                        <option value="otro">Otro problema</option>
                    </select>
                </div>
                <div>
                    <label>Descripción del problema</label>
                    <textarea name="report_descripcion" rows="5" placeholder="Describe detalladamente lo ocurrido…"></textarea>
                </div>
                <button type="submit" name="tipo_soporte" value="denuncia" class="submit-btn">Enviar denuncia →</button>
            </div>
        </form>

        <!-- Pestaña "Mis Preguntas": solo lectura, fuera del <form> porque no envía nada -->
        <div id="tab-mias" class="support-tab">
            <div class="mias-list">
                <?php if (empty($misPreguntas) && empty($misDenuncias)): ?>
                    <p class="mias-empty">Aún no has enviado preguntas ni denuncias.</p>
                <?php else: ?>

                    <?php foreach ($misPreguntas as $p): ?>
                        <?php
                            $estadoP = (string) ($p['estado'] ?? 'pendiente');
                        ?>
                        <div class="mias-item">
                            <div class="mias-item-head">
                                <span class="mias-tag mias-tag-pregunta">❓ Pregunta</span>
                                <span class="mias-estado mias-estado-<?= esc($estadoP) ?>"><?= esc(ucfirst($estadoP)) ?></span>
                            </div>
                            <p class="mias-texto"><?= esc($p['pregunta']) ?></p>
                            <?php if (!empty($p['respuesta'])): ?>
                                <div class="mias-respuesta">
                                    <strong>Respuesta de Swapy:</strong>
                                    <p><?= esc($p['respuesta']) ?></p>
                                </div>
                            <?php else: ?>
                                <p class="mias-pendiente">Aún no ha sido respondida.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <?php foreach ($misDenuncias as $d): ?>
                        <?php
                            $estadoD = (string) ($d['estado'] ?? 'pendiente');
                        ?>
                        <div class="mias-item">
                            <div class="mias-item-head">
                                <span class="mias-tag mias-tag-denuncia">🚩 Denuncia</span>
                                <span class="mias-estado mias-estado-<?= esc($estadoD) ?>"><?= esc(ucfirst(str_replace('_', ' ', $estadoD))) ?></span>
                            </div>
                            <p class="mias-texto"><?= esc($d['descripcion']) ?></p>
                            <?php if (!empty($d['respuesta'])): ?>
                                <div class="mias-respuesta">
                                    <strong>Resolución de Swapy:</strong>
                                    <p><?= esc($d['respuesta']) ?></p>
                                </div>
                            <?php else: ?>
                                <p class="mias-pendiente">Aún está en revisión.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.btn-header-intercambio {
    background-color: #28a745;
    color: white;
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    font-size: 12px;
    font-family: 'DM Sans', sans-serif;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: background 0.2s;
    margin-right: 4px;
}
.btn-header-intercambio:hover {
    background-color: #218838;
}

.modal-bg { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; }
.modal-bg.open { display: flex; }
.modal-box { background: white; padding: 25px; border-radius: 12px; width: 90%; max-width: 450px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); color: #333; font-family: 'DM Sans', sans-serif; }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
.modal-header h3 { margin: 0; font-family: 'Syne', sans-serif; color: #111; }
.close-btn { cursor: pointer; font-size: 28px; color: #999; line-height: 1; }
.close-btn:hover { color: #333; }
.form-group { margin-bottom: 18px; display: flex; flex-direction: column; }
.form-group label { margin-bottom: 6px; font-weight: 500; font-size: 14px; color: #555; text-align: left; }
.form-group input, .form-group textarea { padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; font-family: inherit; resize: vertical; }
.simulation-note { margin: -8px 0 18px; color: #6c757d; font-size: 13px; }

/* Ajustado a 100vw y 100vh para que el video no salga cortado */
.indexx-modal-box { position: relative; width: 100vw; height: 100vh; max-width: 100%; max-height: 100%; background: #050811; border-radius: 0; overflow: hidden; }
.indexx-frame { width: 100%; height: 100%; border: 0; display: block; }
.indexx-close { position: absolute; top: 20px; right: 25px; z-index: 10; width: 40px; height: 40px; border: 0; border-radius: 50%; background: rgba(0, 0, 0, .65); color: #fff; font-size: 30px; line-height: 1; cursor: pointer; }
.waiting-chat-box { text-align: center; }
.waiting-chat-box h3 { margin: 14px 0 8px; color: #111; }
.waiting-chat-box p { margin: 0 0 14px; color: #555; }
.waiting-chat-box strong { color: #007bff; }
.chat-loader { width: 48px; height: 48px; margin: 0 auto; border: 5px solid #dce8f5; border-top-color: #007bff; border-radius: 50%; animation: chatSpin .9s linear infinite; }
@keyframes chatSpin { to { transform: rotate(360deg); } }

.modal-footer { display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px; }
.btn-secundario { background: #6c757d; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 500; }
.btn-primario { background: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 500; }
.btn-primario:hover { background: #0056b3; }

/* "Mis Preguntas" */
.mias-list { display: flex; flex-direction: column; gap: 14px; max-height: 420px; overflow-y: auto; padding-right: 4px; }
.mias-empty { color: #6c757d; font-size: 14px; text-align: center; padding: 30px 0; }
.mias-item { border: 1px solid #e9ecef; border-radius: 10px; padding: 14px 16px; background: #f8f9fa; }
.mias-item-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
.mias-tag { font-size: 12px; font-weight: 700; color: #495057; }
.mias-estado { font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 999px; text-transform: capitalize; background: #e9ecef; color: #495057; }
.mias-estado-pendiente { background: #fff3cd; color: #856404; }
.mias-estado-respondida, .mias-estado-resuelta { background: #d4edda; color: #155724; }
.mias-estado-cerrada, .mias-estado-rechazada { background: #e2e3e5; color: #383d41; }
.mias-estado-en_revision { background: #ffe5d0; color: #a55b1e; }
.mias-texto { font-size: 13px; color: #333; line-height: 1.5; margin: 0 0 8px; }
.mias-pendiente { font-size: 12px; color: #adb5bd; font-style: italic; margin: 0; }
.mias-respuesta { background: white; border-radius: 8px; padding: 10px 12px; margin-top: 6px; }
.mias-respuesta strong { font-size: 12px; color: #0d6efd; display: block; margin-bottom: 4px; }
.mias-respuesta p { font-size: 13px; color: #333; margin: 0; line-height: 1.5; }
</style>

<script>
    function openModal(id) {
        document.getElementById(id)?.classList.add('open');
    }

    function closeModal(id) {
        document.getElementById(id)?.classList.remove('open');
    }

    function iniciarConfirmacionIntercambio() {
        openModal('modal-confirmar-intercambio');
    }

    function esperarConfirmacionIntercambio() {
        closeModal('modal-confirmar-intercambio');
        openModal('modal-esperando-intercambio');

        var segundos = 10;
        var contador = document.getElementById('chat-countdown');
        contador.textContent = segundos;
        var temporizador = window.setInterval(function() {
            segundos -= 1;
            contador.textContent = segundos;

            if (segundos <= 0) {
                window.clearInterval(temporizador);
                closeModal('modal-esperando-intercambio');
                openModal('modal-indexx');
            }
        }, 1000);
    }

    window.addEventListener('message', function(event) {
        if (event.data === 'abrir_formulario_datos') {
            closeModal('modal-indexx');
            openModal('modal-datos-intercambio');
        }

        if (event.data && event.data.tipo === 'solicitud_entrega') {
            enviarSolicitudEntrega(event.data.datos);
        }
    });

    function enviarSolicitudEntrega(datos) {
        var formData = new FormData();
        formData.append('id_producto', ID_PRODUCTO_CHAT);
        formData.append('direccion', datos.direccion);
        formData.append('descripcion_lugar', datos.descripcion_lugar);
        formData.append('descripcion_producto', datos.descripcion_producto);

        fetch(BASE_URL_CHAT + 'conversaciones/solicitud-entrega', {
            method: 'POST',
            body: formData
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var iframe = document.getElementById('indexx-iframe');

            if (!data.ok) {
                if (iframe && iframe.contentWindow) {
                    iframe.contentWindow.postMessage('solicitud_entrega_error', '*');
                }
                throw new Error(data.error || 'No se pudo enviar la solicitud.');
            }

            if (iframe && iframe.contentWindow) {
                iframe.contentWindow.postMessage('solicitud_entrega_ok', '*');
            }

            window.setTimeout(function() {
                closeModal('modal-indexx');
            }, 1800);
        })
        .catch(function(error) {
            console.error(error);
        });
    }

    function guardarDatosIntercambio(event) {
        event.preventDefault();
        closeModal('modal-datos-intercambio');
        alert('✅ Simulación completada. Los datos del intercambio fueron registrados.');
    }

    document.querySelectorAll('.modal-bg').forEach(m => {
        m.addEventListener('click', function(e) { 
            if (e.target === this) this.classList.remove('open'); 
        });
    });

    function switchSupportTab(tabName) {
        document.querySelectorAll('.support-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.form-tab').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + tabName).classList.add('active');
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
    }

    function openSupport() {
        document.getElementById('support-modal').classList.add('open');
    }

    function closeSupport() {
        document.getElementById('support-modal').classList.remove('open');
    }

    var ID_PRODUCTO_CHAT = <?= $productoChat ? (int) $productoChat['id_producto'] : 'null' ?>;
    var BASE_URL_CHAT = '<?= rtrim(base_url(), '/') ?>/index.php/';

    var historialChat = [];
    var idConversacionActual = 0;
    var msgInput = document.getElementById('msg-input');
    var sendBtn = document.getElementById('send-btn');
    var messagesBox = document.getElementById('messages');

    function cargarConversaciones()
    {
        fetch(BASE_URL_CHAT + 'conversaciones')
            .then(function(r) {
                return r.json().then(function(data) {
                    return { status: r.status, data: data };
                });
            })
            .then(function(res) {
                var lista = document.getElementById('chat-list');

                if (!res.data.ok) {
                    lista.innerHTML = '<div class="chat-item"><div class="chat-info">'
                        + '<div class="chat-name">Error al cargar</div>'
                        + '<div class="chat-preview">' + escaparHtml(res.data.error || 'Error desconocido') + '</div>'
                        + '</div></div>';
                    console.error('Error al listar conversaciones:', res.status, res.data.error);
                    return;
                }

                lista.innerHTML = '';

                if (!res.data.conversaciones.length) {
                    lista.innerHTML = '<div class="chat-item"><div class="chat-info"><div class="chat-name">Sin conversaciones</div><div class="chat-preview">Abre un producto para iniciar un chat</div></div></div>';
                    return;
                }

                res.data.conversaciones.forEach(function(chat) {
                    var activo = <?= $idProductoChat ?> === Number(chat.id_producto) ? ' active' : '';
                    var item = document.createElement('div');
                    item.className = 'chat-item' + activo;
                    item.onclick = function() {
                        window.location.href = '<?= base_url('chat') ?>?producto=' + chat.id_producto;
                    };
                    item.innerHTML = '<div class="chat-avatar">' + (chat.nombre_producto || '?').charAt(0).toUpperCase() + '<div class="dot"></div></div>'
                        + '<div class="chat-info"><div class="chat-name">' + escaparHtml(chat.nombre_producto || 'Producto') + '</div>'
                        + '<div class="chat-preview">' + escaparHtml(chat.ultimo_mensaje || 'Conversación iniciada') + '</div></div>';
                    lista.appendChild(item);
                });
            })
            .catch(function(err) {
                console.error('Fallo de red al cargar conversaciones:', err);
                document.getElementById('chat-list').innerHTML = '<div class="chat-item"><div class="chat-info"><div class="chat-name">Error de conexión</div></div></div>';
            });
    }

    function escaparHtml(texto) {
        var div = document.createElement('div');
        div.textContent = texto;
        return div.innerHTML;
    }

    function abrirConversacionActual() {
        if (!ID_PRODUCTO_CHAT) return Promise.resolve();
        var datos = new FormData();
        datos.append('id_producto', ID_PRODUCTO_CHAT);
        return fetch(BASE_URL_CHAT + 'conversaciones/abrir', { method: 'POST', body: datos })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.ok) {
                    idConversacionActual = Number(data.conversacion.id_conversacion);
                    return cargarMensajes().then(function() {
                        return cargarConversaciones();
                    });
                }
            });
    }

    function cargarMensajes() {
        return fetch(BASE_URL_CHAT + 'conversaciones/' + idConversacionActual + '/mensajes')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.ok || !data.mensajes.length) return;
                messagesBox.innerHTML = '';
                historialChat = [];
                data.mensajes.forEach(function(mensaje) {
                    var tipo = mensaje.tipo === 'usuario' ? 'out' : 'in';
                    agregarBurbuja(mensaje.contenido, tipo);
                    historialChat.push({ rol: mensaje.tipo === 'usuario' ? 'usuario' : 'vendedor', texto: mensaje.contenido });
                });
            });
    }

    function autoResize() {
        msgInput.style.height = 'auto';
        msgInput.style.height = msgInput.scrollHeight + 'px';
    }

    function actualizarBotonEnviar() {
        sendBtn.disabled = msgInput.value.trim() === '' || !ID_PRODUCTO_CHAT;
    }

    if (msgInput) {
        msgInput.addEventListener('input', function () {
            autoResize();
            actualizarBotonEnviar();
        });

        msgInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                enviarMensaje();
            }
        });
    }

    if (sendBtn) {
        sendBtn.addEventListener('click', enviarMensaje);
    }

    function agregarBurbuja(texto, tipo) {
        var grupo = document.createElement('div');
        grupo.className = 'msg-group ' + tipo;

        var bubble = document.createElement('div');
        bubble.className = 'msg-bubble';
        bubble.textContent = texto;

        var hora = document.createElement('div');
        hora.className = 'msg-time';
        hora.textContent = new Date().toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' });

        grupo.appendChild(bubble);
        grupo.appendChild(hora);
        messagesBox.appendChild(grupo);
        messagesBox.scrollTop = messagesBox.scrollHeight;
    }

    function mostrarEscribiendo() {
        var typing = document.createElement('div');
        typing.className = 'msg-group in';
        typing.id = 'typing-indicator-row';
        typing.innerHTML = '<div class="typing-indicator"><span></span><span></span><span></span></div>';
        messagesBox.appendChild(typing);
        messagesBox.scrollTop = messagesBox.scrollHeight;
    }

    function quitarEscribiendo() {
        var typing = document.getElementById('typing-indicator-row');
        if (typing) typing.remove();
    }

    function enviarMensaje() {
        var texto = msgInput.value.trim();
        if (texto === '' || !ID_PRODUCTO_CHAT) return;

        agregarBurbuja(texto, 'out');
        historialChat.push({ rol: 'usuario', texto: texto });

        msgInput.value = '';
        autoResize();
        actualizarBotonEnviar();

        mostrarEscribiendo();

        var formData = new FormData();
        formData.append('mensaje', texto);
        formData.append('id_producto', ID_PRODUCTO_CHAT);
        formData.append('historial', JSON.stringify(historialChat));
        formData.append('id_conversacion', idConversacionActual);

        fetch(BASE_URL_CHAT + 'chat-ia/responder', {
            method: 'POST',
            body: formData
        })
        .then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function (data) {
            quitarEscribiendo();

            if (data.ok) {
                agregarBurbuja(data.respuesta, 'in');
                historialChat.push({ rol: 'vendedor', texto: data.respuesta });
            } else {
                agregarBurbuja('⚠️ ' + (data.error || 'No se pudo enviar el mensaje.'), 'in');
            }
        })
        .catch(function (err) {
            quitarEscribiendo();
            agregarBurbuja('⚠️ Error de conexión. Intenta de nuevo.', 'in');
            console.error(err);
        });
    }

    <?php if ($soporteMsg === 'pregunta_enviada'): ?>
        closeSupport();
        alert('✅ Tu pregunta fue enviada correctamente. Te responderemos pronto.');
    <?php elseif ($soporteMsg === 'denuncia_enviada'): ?>
        closeSupport();
        alert('✅ Tu denuncia fue enviada correctamente. La revisaremos pronto.');
    <?php elseif ($soporteErr === 'campos'): ?>
        openSupport();
        alert('⚠️ Completa todos los campos antes de enviar.');
    <?php endif; ?>

    abrirConversacionActual()
        .then(function() {
            return cargarConversaciones();
        })
        .catch(function() {
            cargarConversaciones();
        });
</script>
</body>
</html>