// ---------- Navegación entre secciones (SPA simple, sin recargar) ----------
const TITULOS = {
    inicio: 'Inicio / Dashboard',
    intercambios: 'Reporte / Intercambios',
    solicitudes: 'Logística / Solicitudes de Entrega',
    entregas: 'Logística / Mis Entregas',
    denuncias: 'Seguridad / Denuncias',
    empleados: 'Administración / Empleados',
    errores: 'Soporte / Errores Frecuentes',
    perfil: 'Mi cuenta / Perfil'
};

function go(seccion) {
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.getElementById('sec-' + seccion).classList.add('active');
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    document.getElementById('nav-' + seccion).classList.add('active');
    document.getElementById('topbar-title').innerHTML =
        seccion.charAt(0).toUpperCase() + seccion.slice(1) + ' <span>/ ' + (TITULOS[seccion] || '') + '</span>';
    if (window.innerWidth <= 980) document.getElementById('sidebar').classList.remove('open');
}

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
}

function toggleError(header) {
    const body = header.nextElementSibling;
    const arrow = header.querySelector('.err-arrow');
    const open = body.style.display === 'block';
    body.style.display = open ? 'none' : 'block';
    if (arrow) arrow.style.transform = open ? 'rotate(0deg)' : 'rotate(180deg)';
}

function filtrarTabla(input, tablaId) {
    const filtro = input.value.toLowerCase();
    const filas = document.querySelectorAll('#' + tablaId + ' tbody tr');
    filas.forEach(fila => {
        fila.style.display = fila.textContent.toLowerCase().includes(filtro) ? '' : 'none';
    });
}

function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

let toastTimer;
function toastMsg(msg) {
    const toast = document.getElementById('toast');
    toast.textContent = msg;
    toast.style.transform = 'translateY(0)';
    toast.style.opacity = '1';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
        toast.style.transform = 'translateY(80px)';
        toast.style.opacity = '0';
    }, 2600);
}

// ---------- Helper genérico para llamar a la API del panel ----------
async function apiCall(method, url, body) {
    const res = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: body ? JSON.stringify(body) : undefined
    });
    if (res.status === 401) {
        toastMsg('Tu sesión expiró, vuelve a iniciar sesión.');
        setTimeout(() => window.location.href = '/spempleados', 1200);
        throw new Error('No autenticado');
    }
    const data = await res.json().catch(() => ({}));
    if (!res.ok || data.ok === false) {
        throw new Error(data.error || 'Ocurrió un error, intenta de nuevo.');
    }
    return data;
}
async function apiPost(url, body) { return apiCall('POST', url, body); }

function recargar() { setTimeout(() => window.location.reload(), 700); }

// ---------- Solicitudes de entrega (por asignar) ----------
async function aceptarSolicitud(id) {
    try {
        await apiPost(`/api/empleado/entregas/${id}/aceptar`);
        toastMsg('Solicitud aceptada ✓ Revisa "Mis Entregas"');
        recargar();
    } catch (e) { toastMsg(e.message); }
}

async function rechazarSolicitud(id) {
    try {
        await apiPost(`/api/empleado/entregas/${id}/rechazar`);
        toastMsg('Solicitud rechazada');
        recargar();
    } catch (e) { toastMsg(e.message); }
}

// ---------- CRUD Solicitudes de Entrega ----------
function abrirNuevaEntrega() {
    document.getElementById('entrega-modal-titulo').textContent = 'Nueva Solicitud';
    document.getElementById('entrega-id').value = '';
    document.getElementById('entrega-solicitante').value = '';
    document.getElementById('entrega-recogida').value = '';
    document.getElementById('entrega-entrega').value = '';
    document.getElementById('entrega-hora').value = '';
    openModal('modal-entrega');
}

document.addEventListener('click', (ev) => {
    const btn = ev.target.closest('.editar-entrega');
    if (!btn) return;
    document.getElementById('entrega-modal-titulo').textContent = `Editar Solicitud S-${btn.dataset.id}`;
    document.getElementById('entrega-id').value = btn.dataset.id;
    document.getElementById('entrega-intercambio').value = btn.dataset.intercambio;
    document.getElementById('entrega-solicitante').value = btn.dataset.solicitante || '';
    document.getElementById('entrega-recogida').value = btn.dataset.recogida || '';
    document.getElementById('entrega-entrega').value = btn.dataset.entrega || '';
    document.getElementById('entrega-hora').value = btn.dataset.hora || '';
    openModal('modal-entrega');
});

async function guardarEntrega() {
    const id = document.getElementById('entrega-id').value;
    const body = {
        idIntercambio: document.getElementById('entrega-intercambio').value,
        solicitante: document.getElementById('entrega-solicitante').value.trim(),
        direccionRecogida: document.getElementById('entrega-recogida').value.trim(),
        direccionEntrega: document.getElementById('entrega-entrega').value.trim(),
        horaLimite: document.getElementById('entrega-hora').value
    };
    if (!body.direccionRecogida || !body.direccionEntrega) {
        toastMsg('Completa las dos direcciones.'); return;
    }
    try {
        if (id) {
            await apiCall('PUT', `/api/empleado/entregas/${id}`, body);
            toastMsg('Solicitud actualizada ✓');
        } else {
            await apiPost('/api/empleado/entregas', body);
            toastMsg('Solicitud creada correctamente ✓');
        }
        closeModal('modal-entrega');
        recargar();
    } catch (e) { toastMsg(e.message); }
}

async function eliminarSolicitud(id) {
    if (!confirm(`¿Eliminar la solicitud S-${id}? Esta acción no se puede deshacer.`)) return;
    try {
        await apiCall('DELETE', `/api/empleado/entregas/${id}`);
        toastMsg('Solicitud eliminada');
        recargar();
    } catch (e) { toastMsg(e.message); }
}

// ---------- Mis entregas (flujo de entrega física) ----------
async function marcarEnRuta(id) {
    try {
        await apiPost(`/api/empleado/entregas/${id}/en-ruta`);
        toastMsg('Recogido — en camino al destino 🚚');
        recargar();
    } catch (e) { toastMsg(e.message); }
}

async function marcarEntregada(id) {
    try {
        await apiPost(`/api/empleado/entregas/${id}/entregada`);
        toastMsg('Entrega confirmada ✓');
        recargar();
    } catch (e) { toastMsg(e.message); }
}

async function intentoFallido(id) {
    try {
        const data = await apiPost(`/api/empleado/entregas/${id}/intento-fallido`);
        toastMsg(data.estado === 'Fallida'
            ? 'Entrega marcada como fallida tras 3 intentos'
            : 'Intento registrado, se reagendará automáticamente');
        recargar();
    } catch (e) { toastMsg(e.message); }
}

// ---------- Denuncias ----------
let denunciaActualId = null;

document.addEventListener('click', (ev) => {
    const btn = ev.target.closest('.abrir-denuncia');
    if (!btn) return;
    denunciaActualId = btn.dataset.id;
    document.getElementById('denuncia-titulo').textContent = `Denuncia #${denunciaActualId} — ${btn.dataset.tipo}`;
    document.getElementById('denuncia-desc').value = btn.dataset.desc;
    document.getElementById('denuncia-respuesta').value = '';
    openModal('modal-denuncia');
});

async function enviarRespuestaDenuncia() {
    const respuesta = document.getElementById('denuncia-respuesta').value.trim();
    if (!respuesta) { toastMsg('Escribe una respuesta antes de enviar.'); return; }
    try {
        await apiPost(`/api/empleado/denuncias/${denunciaActualId}/responder`, { respuesta });
        closeModal('modal-denuncia');
        toastMsg('Respuesta enviada ✓');
        recargar();
    } catch (e) { toastMsg(e.message); }
}

async function resolverDenunciaDesdeModal() {
    try {
        await apiPost(`/api/empleado/denuncias/${denunciaActualId}/resolver`);
        closeModal('modal-denuncia');
        toastMsg('Denuncia marcada como resuelta ✓');
        recargar();
    } catch (e) { toastMsg(e.message); }
}

async function resolverDenuncia(id) {
    try {
        await apiPost(`/api/empleado/denuncias/${id}/resolver`);
        toastMsg('Denuncia marcada como resuelta ✓');
        recargar();
    } catch (e) { toastMsg(e.message); }
}

// ---------- Perfil ----------
async function guardarPerfil() {
    const body = {
        nombreCompleto: document.getElementById('p-nombre').value.trim(),
        correo: document.getElementById('p-correo').value.trim(),
        telefono: document.getElementById('p-telefono').value.trim(),
        ciudad: document.getElementById('p-ciudad').value.trim(),
        medioTransporte: document.getElementById('p-transporte').value.trim()
    };
    try {
        await apiPost('/api/empleado/perfil/actualizar', body);
        toastMsg('Cambios guardados correctamente ✓');
    } catch (e) { toastMsg(e.message); }
}

async function cambiarContrasena() {
    const actual = document.getElementById('pw-actual').value;
    const nueva = document.getElementById('pw-nueva').value;
    if (!nueva || nueva.length < 8) { toastMsg('La nueva contraseña debe tener mínimo 8 caracteres.'); return; }
    try {
        await apiPost('/api/empleado/perfil/cambiar-contrasena', { actual, nueva });
        toastMsg('Contraseña actualizada ✓');
        document.getElementById('pw-actual').value = '';
        document.getElementById('pw-nueva').value = '';
    } catch (e) { toastMsg(e.message); }
}

// ---------- CRUD Empleados ----------
function abrirNuevoEmpleado() {
    document.getElementById('empleado-modal-titulo').textContent = 'Nuevo Empleado';
    document.getElementById('empleado-id').value = '';
    document.getElementById('empleado-numdoc').value = '';
    document.getElementById('empleado-numdoc').disabled = false;
    document.getElementById('empleado-tipo-doc').disabled = false;
    document.getElementById('empleado-pnombre').value = '';
    document.getElementById('empleado-snombre').value = '';
    document.getElementById('empleado-papellido').value = '';
    document.getElementById('empleado-sapellido').value = '';
    document.getElementById('empleado-correo').value = '';
    document.getElementById('empleado-contrasena').value = '';
    document.getElementById('empleado-pass-group').style.display = '';
    openModal('modal-empleado');
}

document.addEventListener('click', (ev) => {
    const btn = ev.target.closest('.editar-empleado');
    if (!btn) return;
    document.getElementById('empleado-modal-titulo').textContent = `Editar Empleado #${btn.dataset.id}`;
    document.getElementById('empleado-id').value = btn.dataset.id;
    document.getElementById('empleado-numdoc').value = btn.dataset.id;
    document.getElementById('empleado-numdoc').disabled = true; // el documento es la llave, no se edita
    document.getElementById('empleado-tipo-doc').disabled = true;
    document.getElementById('empleado-pnombre').value = btn.dataset.pnombre || '';
    document.getElementById('empleado-snombre').value = btn.dataset.snombre || '';
    document.getElementById('empleado-papellido').value = btn.dataset.papellido || '';
    document.getElementById('empleado-sapellido').value = btn.dataset.sapellido || '';
    document.getElementById('empleado-correo').value = btn.dataset.correo || '';
    document.getElementById('empleado-pass-group').style.display = 'none'; // la contraseña se cambia desde "Mi perfil"
    openModal('modal-empleado');
});

async function guardarEmpleado() {
    const id = document.getElementById('empleado-id').value;
    const body = {
        idTipoDoc: document.getElementById('empleado-tipo-doc').value,
        numeroDocumento: document.getElementById('empleado-numdoc').value,
        primerNombre: document.getElementById('empleado-pnombre').value.trim(),
        segundoNombre: document.getElementById('empleado-snombre').value.trim(),
        primerApellido: document.getElementById('empleado-papellido').value.trim(),
        segundoApellido: document.getElementById('empleado-sapellido').value.trim(),
        correo: document.getElementById('empleado-correo').value.trim(),
        contrasena: document.getElementById('empleado-contrasena').value
    };
    if (!body.primerNombre || !body.primerApellido || !body.correo) {
        toastMsg('Nombre, apellido y correo son obligatorios.'); return;
    }
    try {
        if (id) {
            await apiCall('PUT', `/api/empleado/gestion/${id}`, body);
            toastMsg('Empleado actualizado ✓');
        } else {
            if (!body.numeroDocumento || !body.contrasena || body.contrasena.length < 8) {
                toastMsg('Documento y contraseña (mínimo 8 caracteres) son obligatorios.'); return;
            }
            await apiPost('/api/empleado/gestion', body);
            toastMsg('Empleado creado correctamente ✓');
        }
        closeModal('modal-empleado');
        recargar();
    } catch (e) { toastMsg(e.message); }
}

async function eliminarEmpleado(id) {
    if (!confirm(`¿Eliminar al empleado #${id}? Esta acción no se puede deshacer.`)) return;
    try {
        await apiCall('DELETE', `/api/empleado/gestion/${id}`);
        toastMsg('Empleado eliminado');
        recargar();
    } catch (e) { toastMsg(e.message); }
}
