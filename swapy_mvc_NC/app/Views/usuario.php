<?php
// Protegemos la vista asegurando que solo entren usuarios logueados
$session = \Config\Services::session();
if (!$session->get('isLoggedIn')) {
    return redirect()->to(base_url('?login_error=acceso'))->send();
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messenger Pro | Swapy</title>
    <link rel="stylesheet" href="<?= base_url('assets/style/usuario.css') ?>">
</head>
<body>

    <nav class="top-nav">
        <div class="logo" onclick="window.location.href='<?= base_url('index2') ?>'" style="cursor: pointer;">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 0C8.95 0 0 8.54 0 19.08C0 25.07 2.89 30.39 7.42 33.78V40L13.31 36.76C15.42 37.35 17.65 37.67 20 37.67C31.05 37.67 40 29.13 40 18.59C40 8.05 31.05 0 20 0ZM21.92 24.63L16.74 19.12L6.63 24.63L17.64 12.95L22.95 18.45L32.93 12.95L21.92 24.63Z" fill="#0084FF"/>
            </svg>
            <span>Messenger</span>
        </div>
    </nav>

    <div class="main-layout">
        <aside class="sidebar-left">
            <div class="sidebar-header">
                <h3>Chats</h3>
            </div>
            <div class="chat-list">
                <div class="chat-item active">
                    <div class="avatar"></div>
                    <div class="chat-info">
                        <p class="name">Juan Pérez</p>
                        <p class="last-msg">¡Hola! ¿Cómo vas?</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="chat-window">
            <div class="message-content">
                <div class="message received"><p>Hola, ¿viste el diseño?</p></div>
                <div class="message sent"><p>¡Sí! Me encantan esos tonos azules.</p></div>
            </div>
            <footer class="chat-input">
                <input type="text" placeholder="Escribe un mensaje...">
                <button class="send-btn">➤</button>
            </footer>
        </main>

        <aside class="sidebar-right">
            <div class="user-profile-detail">
                <div class="avatar-large"></div>
                <h4>Juan Pérez</h4>
                <p>Activo ahora</p>
            </div>
            <div class="options-list">
                <div class="option-item"><i class="fas fa-paint-brush"></i> Personalizar chat</div>
                <div class="option-item"><i class="fas fa-images"></i> Multimedia y archivos</div>
                <div class="option-item"><i class="fas fa-shield-alt"></i> Privacidad y ayuda</div>
            </div>
        </aside>
    </div>

</body>
</html>