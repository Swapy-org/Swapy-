// ── Manejo de Modales ──
function abrirModal(id) { 
    document.getElementById(id).classList.add('active'); 
}

function cerrarModal(id) { 
    document.getElementById(id).classList.remove('active'); 
}

// Cerrar modales haciendo clic en la zona oscura exterior
document.querySelectorAll('.modal-overlay').forEach(function(o) {
    o.addEventListener('click', function(e) { 
        if(e.target === o) o.classList.remove('active'); 
    });
});

// ── Cargar Datos en Modal Editar ──
function abrirEditar(id, doc, correo, rol) {
    document.getElementById('e-id').value     = id;
    document.getElementById('e-doc').value    = doc;
    document.getElementById('e-correo').value = correo;
    document.getElementById('e-rol').value    = rol;
    
    // Si tienes un campo de contraseña en el editar, se limpia al abrir
    var passField = document.getElementById('p2');
    if (passField) passField.value = '';
    
    abrirModal('modal-editar');
}

// ── Confirmación de Eliminación con Ruta Adaptada ──
function confirmarEliminar(id, correo) {
    document.getElementById('confirm-txt').textContent = 'Se eliminará: ' + correo;
    
    // Ahora apunta a la ruta amigable del framework pasando la acción por parámetro
    document.getElementById('confirm-href').href = 'admin?accion=eliminar&id=' + id;
    
    abrirModal('modal-confirmar');
}

// ── Alternar Visibilidad de Contraseñas (Ver / Ocultar) ──
function tp(id) {
    var i = document.getElementById(id);
    i.type = i.type === 'password' ? 'text' : 'password';
}

function togglePassCell(el) {
    if(el.textContent === '••••••••') {
        el.textContent = el.dataset.pass;
        el.style.letterSpacing = 'normal';
    } else {
        el.textContent = '••••••••';
        el.style.letterSpacing = '2px';
    }
}

// ── Filtro de Búsqueda en Tiempo Real en la Tabla ──
function filtrar(v) {
    document.querySelectorAll('#tabla tbody tr').forEach(function(r) {
        r.style.display = r.textContent.toLowerCase().includes(v.toLowerCase()) ? '' : 'none';
    });
}