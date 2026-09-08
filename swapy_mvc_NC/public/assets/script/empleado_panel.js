// ============================================================
// CONFIGURACIÓN DE SECCIONES Y TÍTULOS (SWAPY)
// ============================================================
const sections = ['inicio', 'intercambios', 'solicitudes', 'denuncias', 'errores', 'perfil'];

const titles = {
    inicio:       'Inicio <span>/ Dashboard</span>',
    intercambios: 'Reporte <span>/ Intercambios</span>',
    solicitudes:  'Solicitudes <span>/ Entrega</span>',
    denuncias:    'Centro <span>/ Denuncias</span>',
    errores:      'Soporte <span>/ Errores Frecuentes</span>',
    perfil:       'Mi <span>/ Perfil</span>',
};

// ============================================================
// 1. NAVEGACIÓN PRINCIPAL
// ============================================================
function go(id) {
    // Ocultar secciones y remover clases activas de la navegación
    sections.forEach(s => {
        document.getElementById('sec-' + s)?.classList.remove('active');
        document.getElementById('nav-' + s)?.classList.remove('active');
    });

    // Activar la sección y el botón correspondiente
    document.getElementById('sec-' + id)?.classList.add('active');
    document.getElementById('nav-' + id)?.classList.add('active');
    
    // Cambiar el título en la barra superior
    const topbarTitle = document.getElementById('topbar-title');
    if (topbarTitle) {
        topbarTitle.innerHTML = titles[id] || id;
    }

    // Cerrar el sidebar automáticamente en pantallas móviles/tablets
    if (window.innerWidth < 900) {
        document.getElementById('sidebar')?.classList.remove('open');
    }
}

function toggleSidebar() {
    document.getElementById('sidebar')?.classList.toggle('open');
}

// ============================================================
// 2. GESTIÓN DE MODALES
// ============================================================
function openModal(id) { 
    document.getElementById(id)?.classList.add('open'); 
}

function closeModal(id) { 
    document.getElementById(id)?.classList.remove('open'); 
}

// Cerrar modales al hacer clic fuera de la caja de contenido (en el fondo oscuro)
document.querySelectorAll('.modal-bg').forEach(m => {
    m.addEventListener('click', e => { 
        if (e.target === m) m.classList.remove('open'); 
    });
});

// Cerrar modales activos al presionar la tecla Escape (ESC)
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-bg.open').forEach(m => m.classList.remove('open'));
    }
});

// ============================================================
// 3. LOGICA DE SOPORTE Y UTILIDADES
// ============================================================

// Buscador dinámico para las tablas de la interfaz
function filtrarTabla(input, tableId) {
    const q = input.value.toLowerCase();
    document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}function modificarEstadoProducto(idProducto, nuevoEstado) {
    var formData = new FormData();
    formData.append('id_producto', idProducto);
    formData.append('estado', nuevoEstado);

    // Usamos ruta relativa sin PHP para que no te dé el error de consola
    fetch('/producto/cambiarEstado', {
        method: 'POST',
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.ok) {
            alert('¡El producto ahora está: ' + nuevoEstado + '!');
            location.reload(); 
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(function(err) {
        alert('Error de conexión: ' + err.message);
    });
}

// Sistema global de notificaciones emergentes (Toast)
function toastMsg(msg) {
    const t = document.getElementById('toast');
    if (t) {
        t.textContent = msg;
        t.style.opacity = '1';
        t.style.transform = 'translateY(0)';
        
        setTimeout(() => {
            t.style.opacity = '0';
            t.style.transform = 'translateY(80px)';
        }, 2800);
    }
}

// ============================================================
// 4. ANIMACIONES E INICIALIZACIÓN
// ============================================================

// Animación de entrada para las barras de progreso al cargar la app
window.addEventListener('load', () => {
    document.querySelectorAll('.progress-fill').forEach(el => {
        const w = el.style.width;
        el.style.width = '0';
        setTimeout(() => { 
            el.style.width = w; 
        }, 200);
    });
});
