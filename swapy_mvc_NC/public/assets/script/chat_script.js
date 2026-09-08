const contacts = [
    { id: 1, name: "Ana García",      emoji: "👩",  color: "#e91e8c", online: true,  unread: 2, preview: "¿Vamos al cine esta noche?",    time: "2m" },
    { id: 2, name: "Carlos López",    emoji: "👨",  color: "#1565c0", online: true,  unread: 0, preview: "Perfecto, nos vemos allá 👍",   time: "15m" },
    { id: 3, name: "María Pérez",     emoji: "👩‍🦱", color: "#7b1fa2", online: false, unread: 1, preview: "Gracias por todo!",              time: "1h" },
    { id: 4, name: "Grupo Amigos 🎉", emoji: "👥",  color: "#e65100", online: true,  unread: 5, preview: "Javi: jajaja exacto!!",          time: "30m" },
    { id: 5, name: "Luis Rodríguez",  emoji: "🧑",  color: "#1b5e20", online: false, unread: 0, preview: "Ok! Mañana hablamos",            time: "Ayer" },
    { id: 6, name: "Sara Martínez",   emoji: "👧",  color: "#b71c1c", online: true,  unread: 0, preview: "Enviaste una imagen",            time: "Ayer" },
];
 
const histories = {
    1: [{ role: "received", text: "Hola! ¿Cómo estás?" }, { role: "sent", text: "Muy bien, gracias! ¿Y tú?" }, { role: "received", text: "¿Vamos al cine esta noche?" }],
    2: [{ role: "received", text: "Oye, quedamos a las 7?" }, { role: "sent", text: "Sí, perfecto!" }, { role: "received", text: "Perfecto, nos vemos allá 👍" }],
    3: [{ role: "sent", text: "Fue un placer ayudarte!" }, { role: "received", text: "Gracias por todo!" }],
    4: [{ role: "received", text: "Grupo Amigos: quién viene mañana?" }, { role: "sent", text: "Yo voy seguro!" }, { role: "received", text: "Javi: jajaja exacto!!" }],
    5: [{ role: "sent", text: "Te llamo luego?" }, { role: "received", text: "Ok! Mañana hablamos" }],
    6: [{ role: "received", text: "Mira esta foto que saqué" }, { role: "sent", text: "Qué bonita! Dónde fue?" }, { role: "received", text: "En la playa el fin de semana" }],
};

// =========================================================================
// CORREGIDO: Envía los datos reales del intercambio a la tabla de la Base de Datos
// =========================================================================
function enviarPropuesta(event) {
    event.preventDefault();

    var formElement = document.getElementById('form-intercambio');
    var formData = new FormData(formElement);

    // Usamos la ruta directa. Si usas el puerto 8080 cambialo a: 'http://localhost:8080/soporte/enviarPropuesta'
    fetch('/soporte/enviarPropuesta', { 
        method: 'POST',
        body: formData
    })
    .then(function(r) {
        if (!r.ok) {
            throw new Error('Código de estado del servidor: ' + r.status);
        }
        return r.json();
    })
    .then(function(data) {
        if (data.ok) {
            alert('¡AHORA SÍ! Guardado correctamente en la base de datos.');
            closeModal('modal-propuesta');
            formElement.reset();
        } else {
            // AQUÍ TE VA A DECIR EL ERROR REAL EN PANTALLA
            alert('ALERTA DEL SISTEMA: ' + data.error);
        }
    })
    .catch(function(err) {
        alert('Error de conexión o de red: ' + err.message);
    });
}
// ⚠️ Clave Anthropic para simulación en el Front
const ANTHROPIC_API_KEY = "TU_API_KEY_AQUI";
 
const aiReplies = async (userText, contact) => {
    const prompt = `Eres ${contact.name}, un amigo en una conversación de Messenger. Responde de forma muy corta, casual e informal en español (máximo 2 frases), usando emojis ocasionalmente. El usuario te dijo: "${userText}"`;
    try {
        const res = await fetch("https://api.anthropic.com/v1/messages", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "x-api-key": ANTHROPIC_API_KEY,
                "anthropic-version": "2023-06-01",
                "anthropic-dangerous-direct-browser-access": "true"
            },
            body: JSON.stringify({
                model: "claude-sonnet-4-20250514",
                max_tokens: 100,
                messages: [{ role: "user", content: prompt }]
            })
        });
        const data = await res.json();
        return data.content?.[0]?.text || "jaja sí! 😄";
    } catch {
        const fallbacks = ["jaja exacto! 😄", "Claro que sí!", "Qué interesante 🤔", "Totalmente de acuerdo!", "Haha sí 😂", "Buena idea!"];
        return fallbacks[Math.floor(Math.random() * fallbacks.length)];
    }
};
 
let activeId = 1;
const messages = {};
contacts.forEach(c => { messages[c.id] = [...(histories[c.id] || [])]; });
 
const chatList     = document.getElementById("chat-list");
const messagesDiv  = document.getElementById("messages");
const msgInput     = document.getElementById("msg-input");
const sendBtn      = document.getElementById("send-btn");
const headerAvatar = document.getElementById("header-avatar");
const headerName   = document.getElementById("header-name");
 
function getContact(id) { return contacts.find(c => c.id === id); }
 
function renderSidebar() {
    if (!chatList) return;
    chatList.innerHTML = "";
    contacts.forEach(c => {
        const div = document.createElement("div");
        div.className = "chat-item" + (c.id === activeId ? " active" : "");
        div.innerHTML = `
            <div class="avatar">
                <div class="avatar-img" style="background:${c.color}">${c.emoji}</div>
                ${c.online ? '<div class="online-dot"></div>' : ''}
            </div>
            <div class="chat-info">
                <div class="chat-name">${c.name}</div>
                <div class="chat-preview">${messages[c.id]?.slice(-1)[0]?.text || c.preview}</div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px">
                <span class="chat-time">${c.time}</span>
                ${c.unread > 0 ? `<span class="unread-badge">${c.unread}</span>` : ''}
            </div>
        `;
        div.onclick = () => { activeId = c.id; c.unread = 0; renderSidebar(); loadChat(c.id); };
        chatList.appendChild(div);
    });
}
 
function loadChat(id) {
    const c = getContact(id);
    if (!headerAvatar || !headerName || !messagesDiv) return;
    
    headerAvatar.textContent = c.emoji;
    headerAvatar.style.background = c.color;
    headerName.textContent = c.name;
 
    messagesDiv.innerHTML = "";
    const timeDiv = document.createElement("div");
    timeDiv.className = "msg-time";
    timeDiv.textContent = "Hoy";
    messagesDiv.appendChild(timeDiv);
 
    messages[id].forEach(m => addBubble(m.role, m.text, false));
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}
 
function addBubble(role, text, animate = true) {
    const c = getContact(activeId);
    const row = document.createElement("div");
    row.className = `msg-row ${role}`;
    if (!animate) row.style.animation = "none";
 
    const mini = document.createElement("div");
    mini.className = "mini-avatar";
    mini.style.background = c.color;
    mini.textContent = c.emoji;
 
    const bubble = document.createElement("div");
    bubble.className = `bubble ${role}`;
    bubble.textContent = text;
 
    row.appendChild(mini);
    row.appendChild(bubble);
    messagesDiv.appendChild(row);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}
 
function showTyping() {
    const row = document.createElement("div");
    row.className = "msg-row received";
    row.id = "typing-row";
    const c = getContact(activeId);
 
    const mini = document.createElement("div");
    mini.className = "mini-avatar";
    mini.style.background = c.color;
    mini.textContent = c.emoji;
 
    const typing = document.createElement("div");
    typing.className = "typing-bubble";
    typing.innerHTML = '<div class="dot"></div><div class="dot"></div><div class="dot"></div>';
 
    row.appendChild(mini);
    row.appendChild(typing);
    messagesDiv.appendChild(row);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
    return row;
}
 
async function sendMessage() {
    const text = msgInput.value.trim();
    if (!text) return;
    msgInput.value = "";
    msgInput.style.height = "";
    sendBtn.disabled = true;
 
    messages[activeId].push({ role: "sent", text });
    addBubble("sent", text);
    renderSidebar();
 
    const typingRow = showTyping();
    const delay = 800 + Math.random() * 1000;
    await new Promise(r => setTimeout(r, delay));
 
    const reply = await aiReplies(text, getContact(activeId));
    typingRow.remove();
    messages[activeId].push({ role: "received", text: reply });
    addBubble("received", reply);
    renderSidebar();
}
 
if (msgInput) {
    msgInput.addEventListener("input", () => {
        sendBtn.disabled = !msgInput.value.trim();
        msgInput.style.height = "auto";
        msgInput.style.height = Math.min(msgInput.scrollHeight, 80) + "px";
    });
 
    msgInput.addEventListener("keydown", e => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            if (!sendBtn.disabled) sendMessage();
        }
    });
}
 
if (sendBtn) sendBtn.addEventListener("click", sendMessage);
 
// Inicialización
document.addEventListener("DOMContentLoaded", () => {
    renderSidebar();
    loadChat(1);
});
 
/* ═══════════════════════════════════════
    MODAL DE SOPORTE INTERACTIVO
═══════════════════════════════════════ */
const supportModal = document.getElementById("support-modal");
const openSupport   = document.getElementById("support-open-btn");
const closeSupport  = document.getElementById("close-support");
 
if (openSupport && supportModal) {
    openSupport.addEventListener("click", () => { supportModal.style.display = "flex"; });
}
if (closeSupport && supportModal) {
    closeSupport.addEventListener("click", () => { supportModal.style.display = "none"; });
}
window.addEventListener("click", (e) => {
    if (e.target === supportModal) { supportModal.style.display = "none"; }
});
 
/* ═══════════════════════════════════════
    FUNCIONES PARA TABS DEL SOPORTE
═══════════════════════════════════════ */
function switchSupportTab(tabName) {
    document.querySelectorAll('.support-tab').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.form-tab').forEach(btn => btn.classList.remove('active'));
 
    const selectedTab = document.getElementById('tab-' + tabName);
    if (selectedTab) selectedTab.classList.add('active');
 
    const selectedBtn = document.querySelector('[data-tab="' + tabName + '"]');
    if (selectedBtn) selectedBtn.classList.add('active');
 
    const form = document.getElementById('support-form');
    if (form) form.reset();
}
 
/* ═══════════════════════════════════════
    MANEJO DEL FORMULARIO DE SOPORTE (CI4)
═══════════════════════════════════════ */
const supportForm = document.getElementById('support-form');
if (supportForm) {
    supportForm.addEventListener('submit', async (e) => {
        e.preventDefault();
 
        const formData = new FormData(supportForm);
        const tipoSoporte = formData.get('tipo_soporte');
        
        let url = '';
        if (tipoSoporte === 'pregunta') {
            url = '/soporte/crear_pregunta';
        } else if (tipoSoporte === 'denuncia') {
            url = '/soporte/crear_denuncia';
        } else {
            alert('Por favor selecciona un tipo de soporte');
            return;
        }
 
        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });
 
            const result = await response.json();
 
            if (response.ok) {
                alert('✅ ' + (tipoSoporte === 'pregunta' ? 'Pregunta' : 'Denuncia') + ' enviada correctamente.');
                supportForm.reset();
                if (supportModal) supportModal.style.display = 'none';
                switchSupportTab('faq');
            } else {
                let errMsg = result.error || 'Intenta de nuevo';
                alert('❌ Error: ' + errMsg);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('❌ Error al enviar al servidor de Swapy.');
        }
    });
}