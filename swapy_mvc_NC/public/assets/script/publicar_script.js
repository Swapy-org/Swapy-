/* =========================================
    SWAPY — publicar_script.js
    Lógica del modal de "Crear publicación"
   ========================================= */

function openModal() {
    document.getElementById('modalOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function handleOverlayClick(e) {
    if (e.target === document.getElementById('modalOverlay')) closeModal();
}

function toggleExpand() {
    const section = document.getElementById('expandSection');
    const toggle  = document.getElementById('expandToggle');
    section.classList.toggle('open');
    toggle.classList.toggle('expanded');
}

function previewImage(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
        const zone = document.getElementById('uploadZone');
        const old = zone.querySelector('.preview-img');
        if (old) old.remove();
        document.getElementById('uploadIcon').style.display = 'none';
        document.getElementById('uploadText').style.display = 'none';
        const img = document.createElement('img');
        img.src = ev.target.result;
        img.className = 'preview-img';
        zone.appendChild(img);
    };
    reader.readAsDataURL(file);
}

// Cerrar modal con ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
});
