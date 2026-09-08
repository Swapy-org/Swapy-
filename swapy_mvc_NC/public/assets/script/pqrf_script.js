
const CAT_COLORS = {
  general:    { bg: '#EAF3DE', color: '#3B6D11' },
  cuenta:     { bg: '#E6F1FB', color: '#185FA5' },
  pagos:      { bg: '#FAEEDA', color: '#854F0B' },
  tecnico:    { bg: '#EEEDFE', color: '#534AB7' },
  privacidad: { bg: '#FBEAF0', color: '#993556' },
};
 
let faqs = [
  { id: 1, q: '¿Cómo puedo crear una cuenta?', a: 'Puedes crear tu cuenta haciendo clic en el botón "Registrarse" en la esquina superior derecha. Completa el formulario con tu nombre, correo electrónico y una contraseña segura. Recibirás un correo de verificación para activar tu cuenta.', cat: 'cuenta', author: 'Equipo Soporte', votes: 14 },
  { id: 2, q: '¿Cuáles son los métodos de pago aceptados?', a: 'Aceptamos tarjetas de crédito y débito (Visa, Mastercard, American Express), PayPal, transferencias bancarias y pagos en efectivo a través de corresponsales bancarios. Todos los pagos están protegidos con encriptación SSL de 256 bits.', cat: 'pagos', author: 'Equipo Soporte', votes: 9 },
  { id: 3, q: '¿Cómo puedo recuperar mi contraseña olvidada?', a: 'Haz clic en "¿Olvidaste tu contraseña?" en la pantalla de inicio de sesión. Ingresa tu correo electrónico registrado y recibirás un enlace de recuperación válido por 24 horas. Si no ves el correo, revisa tu carpeta de spam.', cat: 'cuenta', author: 'Equipo Soporte', votes: 21 },
  { id: 4, q: '¿La plataforma es compatible con dispositivos móviles?', a: 'Sí, nuestra plataforma es completamente responsiva y funciona en smartphones y tablets con iOS 13+ y Android 8+. También contamos con aplicaciones nativas disponibles en App Store y Google Play para una mejor experiencia móvil.', cat: 'tecnico', author: 'Equipo Soporte', votes: 7 },
  { id: 5, q: '¿Cómo cancelo mi suscripción?', a: 'Para cancelar tu suscripción, ve a Configuración > Planes y Facturación > Cancelar suscripción. La cancelación es efectiva al final del período facturado actual y no realizamos reembolsos parciales. Conservarás acceso completo hasta la fecha de vencimiento.', cat: 'pagos', author: 'Equipo Soporte', votes: 5 },
  { id: 6, q: '¿Mis datos personales están seguros?', a: 'La seguridad de tus datos es nuestra prioridad. Utilizamos encriptación de extremo a extremo, servidores certificados ISO 27001 y nunca vendemos tu información a terceros. Puedes consultar nuestra política de privacidad completa en el pie de página del sitio.', cat: 'privacidad', author: 'Equipo Soporte', votes: 18 },
  { id: 7, q: '¿Qué hago si la página no carga correctamente?', a: 'Primero intenta limpiar el caché y las cookies de tu navegador (Ctrl+Shift+Del). Si el problema persiste, prueba en modo incógnito o en otro navegador. También verifica tu conexión a internet. Si ninguna solución funciona, contáctanos indicando tu sistema operativo y navegador.', cat: 'tecnico', author: 'Equipo Soporte', votes: 11 },
  { id: 8, q: '¿Puedo usar mi cuenta en múltiples dispositivos?', a: 'Sí, tu cuenta puede utilizarse simultáneamente en hasta 3 dispositivos con el plan básico y en dispositivos ilimitados con el plan Premium. Las sesiones se sincronizan automáticamente en tiempo real. Puedes gestionar y cerrar sesiones activas desde Configuración > Seguridad.', cat: 'general', author: 'Equipo Soporte', votes: 6 },
];
 
let nextId = 100;
let activeCat = 'todas';
let totalVotes = faqs.reduce((s, f) => s + f.votes, 0);
let contributors = 1;
 
function tagHtml(cat) {
  const c = CAT_COLORS[cat] || { bg: '#f1f3f5', color: '#6c757d' };
  return `<span class="cat-tag" style="background:${c.bg};color:${c.color}">${cat.charAt(0).toUpperCase()+cat.slice(1)}</span>`;
}
 
function renderFaqs() {
  const q = document.getElementById('search').value.toLowerCase().trim();
  const list = document.getElementById('faq-list');
  const noRes = document.getElementById('no-results');
  const label = document.getElementById('results-label');
 
  let filtered = faqs.filter(f => {
    const catOk = activeCat === 'todas' || f.cat === activeCat;
    const searchOk = !q || f.q.toLowerCase().includes(q) || f.a.toLowerCase().includes(q);
    return catOk && searchOk;
  });
 
  list.innerHTML = '';
 
  if (filtered.length === 0) {
    noRes.style.display = 'block';
    document.getElementById('search-term').textContent = q;
    label.textContent = 'Sin resultados';
    return;
  }
 
  noRes.style.display = 'none';
  label.textContent = activeCat === 'todas'
    ? `Mostrando ${filtered.length} preguntas`
    : `${filtered.length} pregunta${filtered.length !== 1 ? 's' : ''} en "${activeCat}"`;
 
  filtered.forEach((f, idx) => {
    const item = document.createElement('div');
    item.className = 'faq-item';
    item.dataset.id = f.id;
 
    item.innerHTML = `
      <button class="faq-question" aria-expanded="false">
        <div class="q-left">
          <span class="q-num">${idx + 1}</span>
          <span class="q-text">${highlight(f.q, q)}</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
          ${tagHtml(f.cat)}
          <div class="chevron">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path d="m6 9 6 6 6-6"/>
            </svg>
          </div>
        </div>
      </button>
      <div class="faq-answer">
        <div class="faq-answer-inner">
          <p>${highlight(f.a, q)}</p>
          <div class="answer-actions">
            <span>¿Fue útil?</span>
            <button class="helpful-btn${f.voted ? ' voted' : ''}" data-id="${f.id}">
              <svg width="12" height="12" fill="${f.voted ? '#fff' : 'none'}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/>
                <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
              </svg>
              ${f.votes} útil${f.votes !== 1 ? 'es' : ''}
            </button>
            <button class="delete-btn" data-id="${f.id}">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 6h18M19 6l-1 14H6L5 6M10 11v6M14 11v6M8 6V4h8v2"/>
              </svg>
              Eliminar
            </button>
          </div>
          <div style="margin-top:10px;font-size:11px;color:var(--gray-400)">Respondido por <strong>${f.author || 'Anónimo'}</strong></div>
        </div>
      </div>
    `;
 
    const btn = item.querySelector('.faq-question');
    const ans = item.querySelector('.faq-answer');
 
    btn.addEventListener('click', () => {
      const isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item.open').forEach(el => {
        el.classList.remove('open');
        el.querySelector('.faq-answer').style.maxHeight = '0';
        el.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
      });
      if (!isOpen) {
        item.classList.add('open');
        ans.style.maxHeight = ans.scrollHeight + 'px';
        btn.setAttribute('aria-expanded', 'true');
      }
    });
 
    item.querySelector('.helpful-btn').addEventListener('click', e => {
      e.stopPropagation();
      const faq = faqs.find(x => x.id === f.id);
      if (!faq.voted) {
        faq.votes++;
        faq.voted = true;
        totalVotes++;
        document.getElementById('votes-count').textContent = totalVotes;
        renderFaqs();
        showToast('¡Gracias por tu voto!');
      }
    });
 
    item.querySelector('.delete-btn').addEventListener('click', e => {
      e.stopPropagation();
      if (confirm('¿Eliminar esta pregunta?')) {
        faqs = faqs.filter(x => x.id !== f.id);
        document.getElementById('total-count').textContent = faqs.length;
        renderFaqs();
        showToast('Pregunta eliminada');
      }
    });
 
    list.appendChild(item);
  });
}
 
function highlight(text, q) {
  if (!q) return text;
  const re = new RegExp(`(${q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
  return text.replace(re, '<mark style="background:#fff3cd;border-radius:2px;padding:0 2px">$1</mark>');
}
 
function showToast(msg) {
  const t = document.getElementById('toast');
  document.getElementById('toast-msg').textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}
 
document.getElementById('search').addEventListener('input', renderFaqs);
 
document.querySelectorAll('.cat-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    activeCat = btn.dataset.cat;
    renderFaqs();
  });
});
 
const addHeader = document.getElementById('add-header');
const addToggle = document.getElementById('add-toggle');
const addFormWrap = document.getElementById('add-form-wrap');
 
addHeader.addEventListener('click', () => {
  const isOpen = addToggle.classList.contains('open');
  addToggle.classList.toggle('open');
  addHeader.classList.toggle('open');
  addFormWrap.style.maxHeight = isOpen ? '0' : addFormWrap.scrollHeight + 400 + 'px';
});
 
document.getElementById('btn-cancel').addEventListener('click', () => {
  addToggle.classList.remove('open');
  addHeader.classList.remove('open');
  addFormWrap.style.maxHeight = '0';
  document.getElementById('new-q').value = '';
  document.getElementById('new-a').value = '';
  document.getElementById('new-author').value = '';
  document.getElementById('q-count').textContent = '0';
  document.getElementById('a-count').textContent = '0';
  document.getElementById('btn-submit').disabled = true;
});
 
function checkForm() {
  const q = document.getElementById('new-q').value.trim();
  const a = document.getElementById('new-a').value.trim();
  document.getElementById('btn-submit').disabled = !(q.length >= 5 && a.length >= 10);
}
 
document.getElementById('new-q').addEventListener('input', function() {
  document.getElementById('q-count').textContent = this.value.length;
  checkForm();
});
 
document.getElementById('new-a').addEventListener('input', function() {
  document.getElementById('a-count').textContent = this.value.length;
  checkForm();
});
 
document.getElementById('btn-submit').addEventListener('click', () => {
  const q = document.getElementById('new-q').value.trim();
  const a = document.getElementById('new-a').value.trim();
  const cat = document.getElementById('new-cat').value;
  const author = document.getElementById('new-author').value.trim() || 'Comunidad';
 
  faqs.unshift({ id: nextId++, q, a, cat, author, votes: 0, voted: false });
 
  contributors++;
  document.getElementById('total-count').textContent = faqs.length;
  document.getElementById('contrib-count').textContent = contributors;
 
  document.getElementById('btn-cancel').click();
  activeCat = 'todas';
  document.querySelectorAll('.cat-btn').forEach(b => {
    b.classList.toggle('active', b.dataset.cat === 'todas');
  });
 
  renderFaqs();
  showToast('¡Pregunta publicada con éxito!');
  window.scrollTo({ top: 0, behavior: 'smooth' });
});
 
document.getElementById('total-count').textContent = faqs.length;
document.getElementById('votes-count').textContent = totalVotes;
renderFaqs();