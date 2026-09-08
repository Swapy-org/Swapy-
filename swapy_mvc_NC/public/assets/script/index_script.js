// ══════════════════════════════════════
//  FAQ — SOLO PRESENTACIÓN (sin edición)
// ══════════════════════════════════════

const FAQ_DATA = [
  { id: 1, cat: 'cuenta',     q: '¿Cómo puedo crear una cuenta?',                    a: 'Haz clic en "Empezar ahora" o en "Iniciar sesión" y selecciona la opción Registrarse. Solo necesitas un correo electrónico válido y una contraseña segura.' },
  { id: 2, cat: 'pagos',      q: '¿Cuáles son los métodos de pago aceptados?',        a: 'Swapy funciona mediante intercambio de objetos, no dinero real. Sin embargo, cada artículo tiene un valor estimado en COL$ para facilitar intercambios justos entre usuarios.' },
  { id: 3, cat: 'cuenta',     q: '¿Cómo puedo recuperar mi contraseña olvidada?',     a: 'En el panel de inicio de sesión haz clic en "¿Olvidaste tu contraseña?" e ingresa tu correo. Te enviaremos un enlace de recuperación válido por 15 minutos.' },
  { id: 4, cat: 'tecnico',    q: '¿La plataforma es compatible con dispositivos móviles?', a: 'Sí, Swapy está optimizada para funcionar en celulares, tablets y computadores. Próximamente lanzaremos nuestra aplicación móvil oficial.' },
  { id: 5, cat: 'privacidad', q: '¿Mis datos personales están seguros?',              a: 'Absolutamente. Utilizamos cifrado de extremo a extremo y nunca compartimos tu información con terceros sin tu consentimiento. Consulta nuestra Política de Privacidad para más detalles.' },
  { id: 6, cat: 'general',    q: '¿Cómo funciona el intercambio en Swapy?',           a: 'Publicas el artículo que quieres intercambiar, le asignas un valor aproximado y buscas artículos de valor similar. Cuando encuentras algo que te interesa, contactas al dueño por el chat y coordinan el intercambio.' },
  { id: 7, cat: 'general',    q: '¿Hay algún costo por usar Swapy?',                  a: 'No, Swapy es completamente gratuita para los usuarios. Crear una cuenta, publicar artículos y realizar intercambios no tiene ningún costo.' },
  { id: 8, cat: 'tecnico',    q: '¿Qué hago si tengo un problema técnico?',           a: 'Puedes usar nuestro botón de Soporte en la página principal. Nuestro equipo responde en menos de 24 horas hábiles.' },
];

const CAT_COLORS = {
  general:    { bg: '#eaf3de', color: '#3B6D11' },
  cuenta:     { bg: '#e6f1fb', color: '#185FA5' },
  pagos:      { bg: '#faeeda', color: '#854F0B' },
  tecnico:    { bg: '#EEEDFE', color: '#3C3489' },
  privacidad: { bg: '#FAECE7', color: '#993C1D' },
};

let activeCat = 'todas';

function renderFAQ(data) {
  const list = document.getElementById('faq-list');
  const label = document.getElementById('results-label');
  list.innerHTML = '';

  const filtered = activeCat === 'todas' ? data : data.filter(f => f.cat === activeCat);
  label.textContent = `Mostrando ${filtered.length} pregunta${filtered.length !== 1 ? 's' : ''}`;

  if (filtered.length === 0) {
    list.innerHTML = '<p style="text-align:center;color:#adb5bd;padding:32px 0;">No hay preguntas en esta categoría.</p>';
    return;
  }

  filtered.forEach((item, idx) => {
    const colors = CAT_COLORS[item.cat] || { bg: '#f1f3f5', color: '#6c757d' };
    const div = document.createElement('div');
    div.className = 'faq-item';
    div.innerHTML = `
      <button class="faq-question" aria-expanded="false">
        <div class="q-left">
          <span class="q-num">${idx + 1}</span>
          <span class="q-text">${item.q}</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
          <span class="cat-tag" style="background:${colors.bg};color:${colors.color};">${item.cat}</span>
          <span class="chevron">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <path d="M6 9l6 6 6-6"/>
            </svg>
          </span>
        </div>
      </button>
      <div class="faq-answer">
        <div class="faq-answer-inner"><p>${item.a}</p></div>
      </div>`;

    const btn = div.querySelector('.faq-question');
    const ans = div.querySelector('.faq-answer');
    btn.addEventListener('click', () => {
      const isOpen = div.classList.contains('open');
      // Cierra todos
      document.querySelectorAll('.faq-item.open').forEach(el => {
        el.classList.remove('open');
        el.querySelector('.faq-answer').style.maxHeight = '0';
        el.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
      });
      if (!isOpen) {
        div.classList.add('open');
        ans.style.maxHeight = ans.scrollHeight + 'px';
        btn.setAttribute('aria-expanded', 'true');
      }
    });

    list.appendChild(div);
  });
}

// Filtros de categoría
document.querySelectorAll('.cat-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    activeCat = btn.dataset.cat;
    renderFAQ(FAQ_DATA);
  });
});

renderFAQ(FAQ_DATA);

// ══════════════════════════════════════
// MODAL LOGIN / REGISTRO
// ══════════════════════════════════════

function resetRecoverySection() {
    const form = document.getElementById('m-rec-form');
    const ok = document.getElementById('m-rec-ok');
    if (form && ok) {
        form.style.display = 'block';
        ok.style.display = 'none';
    }
}

function openModal(tab = 'login') {
    const modal = document.getElementById('authModal');
    modal.classList.add('active');
    resetRecoverySection();
    switchTab(tab);
}

function closeModal() {
    const modal = document.getElementById('authModal');
    modal.classList.remove('active');
    resetRecoverySection();
}

function switchTab(tab) {
    resetRecoverySection();

    document.querySelectorAll('.m-tab').forEach(btn => {
        btn.classList.remove('active');
    });

    document.querySelectorAll('.m-panel').forEach(panel => {
        panel.classList.remove('active');
    });

    const tabButton = document.getElementById(`m-tab-${tab}`);
    const tabPanel = document.getElementById(`m-panel-${tab}`);
    if (tabButton) tabButton.classList.add('active');
    if (tabPanel) tabPanel.classList.add('active');
}

function mToggle(id, btn) {
    const input = document.getElementById(id);

    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
    } else {
        input.type = 'password';
        btn.textContent = '👁';
    }
}

function mStrength(password) {

    const bars = [
        document.getElementById('msb1'),
        document.getElementById('msb2'),
        document.getElementById('msb3'),
        document.getElementById('msb4')
    ];

    const label = document.getElementById('msl');

    bars.forEach(bar => {
        bar.style.background = '#ddd';
    });

    let score = 0;

    if (password.length >= 8) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    for (let i = 0; i < score; i++) {
        bars[i].style.background = '#4a9fd4';
    }

    const levels = ['Débil', 'Básica', 'Media', 'Fuerte'];
    label.textContent = score > 0 ? levels[score - 1] : '—';
}

function doLogin() {
    alert('Inicio de sesión pendiente de conectar con PHP');
}

function doRegister() {
    alert('Registro pendiente de conectar con PHP');
}

function doRecover() {

    document.getElementById('m-rec-form').style.display = 'none';
    document.getElementById('m-rec-ok').style.display = 'block';
}

window.onclick = function(event) {
    const modal = document.getElementById('authModal');

    if (event.target === modal) {
        closeModal();
    }
};