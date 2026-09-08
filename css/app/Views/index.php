    <?php
    $loginError = $_GET['login_error'] ?? '';
    $loginMessage = '';
    switch ($loginError) {
        case 'campos':   $loginMessage = 'Por favor completa correo y contraseña.'; break;
        case 'correo':   $loginMessage = 'El correo no es válido.'; break;
        case 'invalido': $loginMessage = 'Correo o contraseña incorrectos.'; break;
        case 'inactivo': $loginMessage = 'La cuenta no está activa. Verifica tu correo o contacta soporte.'; break;
        case 'acceso':   $loginMessage = 'Debes iniciar sesión para acceder a esa página.'; break;
        default:         $loginMessage = '';
    }

    $docTypes = (new \App\Models\UsuarioModel())->obtenerTiposDoc();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Swapy — Intercambia sin límites</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="assets/style/Style.css">
        <link rel="stylesheet" href="assets/style/otp_modal.css">
        <style>
            .emp-left > a,
            .emp-right > a {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                min-height: 44px;
                padding: 12px 20px;
                border-radius: 10px;
                text-decoration: none;
                font-family: 'DM Sans', sans-serif;
                font-size: 0.9rem;
                font-weight: 700;
                transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            }

            .emp-left > a {
                margin-top: 22px;
                background: #2c3e50;
                color: #ffffff;
                box-shadow: 0 8px 18px rgba(44, 62, 80, 0.18);
            }

            .emp-left > a:hover {
                background: #1f2d3a;
                color: #ffffff;
                transform: translateY(-2px);
                box-shadow: 0 11px 22px rgba(44, 62, 80, 0.25);
            }

            .emp-right > a {
                width: 100%;
                margin-top: 12px;
                background: #4a9fd4;
                color: #ffffff;
                box-shadow: 0 7px 16px rgba(74, 159, 212, 0.2);
            }

            .emp-right > a:hover {
                background: #378add;
                color: #ffffff;
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(74, 159, 212, 0.3);
            }

            @media (max-width: 700px) {
                .emp-left > a,
                .emp-right > a {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>

        <!-- ── NAVBAR ── -->
        <nav class="navbar-container">
            <img src="assets/IMG/SWAPY.png" alt="Logo Swapy" class="logo">
            <ul class="nav-links">
                <li><a class="nav-cta" onclick="openModal('login')" style="cursor:pointer;">Iniciar sesión</a></li>
            </ul>
        </nav>

        <!-- ── HERO ── -->
        <section class="main-content" id="como-funciona">
            <div class="info-box">
                <span class="eyebrow">✦ Plataforma de intercambio</span>
                <h1>Intercambia,<br>no compres <span>más.</span></h1>
                <p>Descubre una nueva forma de intercambiar y explorar categorías.<br>
                Navega por nuestro catálogo interactivo 3D y<br>encuentra lo que necesitas hoy mismo.</p>
                <button class="btn-main" type="button" onclick="openModal('login')">
                    Empezar ahora
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h14M13 6l6 6-6 6" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <div class="carrusel">
                <div class="card-3d">
                    <div><img src="assets/IMG/computador.png" alt="Computador" class="carruselim"></div>
                    <div><img src="assets/IMG/ropa.png" alt="Ropa" class="carruselim"></div>
                    <div><img src="assets/IMG/juguetes.png" alt="Juguetes" class="carruselim"></div>
                    <div><img src="assets/IMG/auto.png" alt="Auto" class="carruselim"></div>
                    <div><img src="assets/IMG/casa.png" alt="Casa" class="carruselim"></div>
                    <div><img src="assets/IMG/deporte.png" alt="Deporte" class="carruselim"></div>
                    <div><img src="assets/IMG/otros.png" alt="Otros" class="carruselim"></div>
                    <div><img src="assets/IMG/perro.png" alt="Mascotas" class="carruselim"></div>
                    <div><img src="assets/IMG/planta.png" alt="Plantas" class="carruselim"></div>
                    <div><img src="assets/IMG/libros.png" alt="Libros" class="carruselim"></div>
                </div>
            </div>
        </section>

        <div class="divider-section"><hr></div>
        <p class="texto">Realiza intercambios al producto que desees<br><em>sin gastar dinero.</em></p>

        <div class="contenedor-verde-grande">
            <div class="contenedor-inferior">
                <div class="zona-intercambio">
                    <div class="card">
                        <div class="image"><img src="assets/IMG/planta.png" alt="Planta"></div>
                        <span class="title">Planta de interior</span>
                        <span class="price">COL $200 | 110</span>
                    </div>
                </div>
                <div class="form-container">
                    <span class="heading">Ponle un valor económico a tu producto</span>
                    <span class="c1">Para así hacer un intercambio justo de precios</span>
                    <span class="c2">¡Consigue lo que sueñes y deshazte de lo que no necesitas!</span>
                    <div class="button-container">
                        <div class="send-button" role="button" tabindex="0" onclick="openModal('login')">¡Comienza tu intercambio desde nuestra página!</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="stats-strip">
            <div class="stat-item"><div class="num">12K+</div><div class="label">Usuarios activos</div></div>
            <div class="stat-divider"></div>
            <div class="stat-item"><div class="num">38K</div><div class="label">Intercambios realizados</div></div>
            <div class="stat-divider"></div>
            <div class="stat-item"><div class="num">98%</div><div class="label">Satisfacción</div></div>
            <div class="stat-divider"></div>
            <div class="stat-item"><div class="num">10+</div><div class="label">Categorías</div></div>
        </div>

        <section class="categorias-section" id="categorias">
            <h2>Explora por categoría</h2>
            <div class="categorias-grid">
                <div class="cat-chip"><span class="emoji">💻</span>Tecnología</div>
                <div class="cat-chip"><span class="emoji">👗</span>Ropa</div>
                <div class="cat-chip"><span class="emoji">🧸</span>Juguetes</div>
                <div class="cat-chip"><span class="emoji">🚗</span>Vehículos</div>
                <div class="cat-chip"><span class="emoji">🏠</span>Hogar</div>
                <div class="cat-chip"><span class="emoji">⚽</span>Deportes</div>
                <div class="cat-chip"><span class="emoji">🐶</span>Mascotas</div>
                <div class="cat-chip"><span class="emoji">🌿</span>Plantas</div>
                <div class="cat-chip"><span class="emoji">📚</span>Libros</div>
                <div class="cat-chip"><span class="emoji">✨</span>Otros</div>
            </div>
        </section>

        <div class="hero">
            <div class="hero-badge">Centro de ayuda</div>
            <h1>Preguntas Frecuentes</h1>
            <p>Encuentra respuestas rápidas a las dudas más comunes sobre Swapy.</p>
        </div>

        <div class="container faq-section" id="centro-ayuda" style="margin-top: 36px;">
            <div class="categories">
                <button class="cat-btn active" type="button" data-cat="todas" onclick="openModal('login')">Todas</button>
                <button class="cat-btn" type="button" data-cat="general" onclick="openModal('login')">General</button>
                <button class="cat-btn" type="button" data-cat="cuenta" onclick="openModal('login')">Cuenta</button>
                <button class="cat-btn" type="button" data-cat="pagos" onclick="openModal('login')">Pagos</button>
                <button class="cat-btn" type="button" data-cat="tecnico" onclick="openModal('login')">Técnico</button>
                <button class="cat-btn" type="button" data-cat="privacidad" onclick="openModal('login')">Privacidad</button>
            </div>
            <div class="section-label" id="results-label">Mostrando todas las preguntas</div>
            <div class="faq-list" id="faq-list"></div>
        </div>

        <div class="seccion-interaccion" id="contacto">
            <div class="caja-elegante">
                <h3>¡Usa nuestro chat Swapy!</h3>
                <p>Escríbete directamente con la persona con quien quieres hacer el intercambio. Rápido, seguro y en tiempo real.</p>
                <ul class="feature-list">
                    <li>Mensajes en tiempo real</li>
                    <li>Historial de conversaciones</li>
                    <li>Notificaciones de ofertas</li>
                </ul>
            </div>
            <div class="chat-container">
                <div class="chat-header">
                    <div class="avatar"></div>
                    <span>Rodolfo</span>
                    <div class="status-dot"></div>
                </div>
                <div class="chat-body">
                    <div class="message">¡Hola! ¿Hacemos un intercambio? Te ofrezco un celular por tu compu 😉</div>
                    <div class="message sent">¡Me interesa! ¿Cuál modelo?</div>
                </div>
                <div class="chat-footer">
                    <input type="text" placeholder="Escribe un mensaje...">
                    <button type="button" onclick="openModal('login')">
                        <svg viewBox="0 0 24 24" width="16" height="16"><path fill="white" d="M2,21L23,12L2,3V10L17,12L2,14V21Z"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="seccion-final-flex">
            <div class="artcort">
                <img src="assets/IMG/swapysoport.png" alt="Celular Swapy">
            </div>
            <div class="texto-informativo">
                <h2>Utiliza nuestro soporte</h2>
                <p>Ante cualquier queja de la pagina o denuncia a un usuario hazlo saber en nuestro soporte <br>
                    estaremos atentos a todo lo que necesites.</p>
                <button class="btn-main" type="button" onclick="openModal('login')">Soporte</button>
            </div>
        </div>

        <section class="seccion-empleado" id="carreras">
            <div class="emp-left">
                <span class="emp-badge">
                    <span class="emp-badge-dot"></span>
                    Oportunidad laboral
                </span>
                <h2>¿Quieres ser<br>parte del equipo <span>Swapy?</span></h2>
                <p>
                    Únete a nuestra red de empleados y ayuda a que miles de personas
                    intercambien lo que no necesitan. Gestiona intercambios, apoya a
                    usuarios y crece con nosotros.
                </p>
                <ul class="emp-perks">
                    <li><span class="emp-perk-icon">🛠️</span>Herramientas exclusivas para gestionar intercambios</li>
                    <li><span class="emp-perk-icon">📊</span>Panel de control con métricas en tiempo real</li>
                    <li><span class="emp-perk-icon">💬</span>Soporte directo a usuarios de la plataforma</li>
                    <li><span class="emp-perk-icon">🚀</span>Crecimiento profesional dentro de Swapy</li>
                </ul>
                <a href="<?= base_url('empleado') ?>">
                    Quiero ser empleado
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M5 12h14M13 6l6 6-6 6" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
            <div class="emp-right">
                <div class="emp-card-dark">
                    <div class="emp-card-header">
                        <div class="emp-avatar">SW</div>
                        <div class="emp-card-info">
                            <span class="emp-name">Panel de Empleado</span>
                            <span class="emp-role">Swapy · Empleado Activo</span>
                        </div>
                        <span class="emp-status-badge">● Activo</span>
                    </div>
                    <div class="emp-stats-grid">
                        <div class="emp-stat"><span class="emp-val">248</span><span class="emp-lbl">Intercambios</span></div>
                        <div class="emp-stat"><span class="emp-val">4.9★</span><span class="emp-lbl">Calificación</span></div>
                        <div class="emp-stat"><span class="emp-val">12</span><span class="emp-lbl">Reportes</span></div>
                        <div class="emp-stat"><span class="emp-val">98%</span><span class="emp-lbl">Resolución</span></div>
                    </div>
                </div>
                <div class="emp-mini-card">
                    <span class="emp-mini-icon">🔐</span>
                    <div>
                        <p class="emp-mini-t1">Acceso exclusivo para empleados</p>
                        <p class="emp-mini-t2">Inicia sesión con tus credenciales</p>
                    </div>
                    <span class="emp-mini-arrow">→</span>
                </div>
                <div class="emp-divider-label">¿Ya tienes cuenta de empleado?</div>
                <a href="<?= base_url('empleado') ?>">
                    Iniciar sesión como empleado →
                </a>
            </div>
        </section>

        <footer>
            <div class="footer-top">
                <div class="footer-brand">
                    <div class="footer-logo">Swapy<span>.</span></div>
                    <p>La plataforma colombiana donde intercambias lo que ya no usas por lo que siempre quisiste. Sin dinero, con comunidad.</p>
                </div>
                <div class="footer-col">
                    <h4>Plataforma</h4>
                    <ul>
                        <li><a href="<?= base_url('informacion/como-funciona') ?>">Cómo funciona</a></li>
                        <li><a href="<?= base_url('informacion/explorar-articulos') ?>">Explorar artículos</a></li>
                        <li><a href="#" onclick="openModal('login'); return false;">Publicar</a></li>
                        <li><a href="<?= base_url('informacion/categorias') ?>">Categorías</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Empresa</h4>
                    <ul>
                        <li><a href="<?= base_url('informacion/sobre-nosotros') ?>">Sobre nosotros</a></li>
                        <li><a href="<?= base_url('informacion/blog') ?>">Blog</a></li>
                        <li><a href="<?= base_url('informacion/prensa') ?>">Prensa</a></li>
                        <li><a href="<?= base_url('informacion/carreras') ?>">Carreras</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Soporte</h4>
                    <ul>
                        <li><a href="<?= base_url('informacion/centro-ayuda') ?>">Centro de ayuda</a></li>
                        <li><a href="<?= base_url('informacion/terminos') ?>">Términos de uso</a></li>
                        <li><a href="<?= base_url('informacion/privacidad') ?>">Privacidad</a></li>
                        <li><a href="<?= base_url('informacion/contacto') ?>">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 Swapy. Hecho con ♥ en Colombia.</p>
                <div class="social-links">
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram">ig</a>
                    <a href="https://x.com" target="_blank" rel="noopener noreferrer" aria-label="X">tw</a>
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook">fb</a>
                    <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" aria-label="YouTube">yt</a>
                </div>
            </div>
        </footer>

        <!-- MODAL AUTH -->
        <div id="authModal">
            <div class="m-box">
                <div class="m-stripe"></div>
                <button class="m-close" onclick="closeModal()">✕</button>
                <div class="m-tabs">
                    <button class="m-tab active" id="m-tab-login" onclick="switchTab('login')">Iniciar sesión</button>
                    <button class="m-tab" id="m-tab-register" onclick="switchTab('register')">Registrarse</button>
                    <button class="m-tab" id="m-tab-recover" onclick="switchTab('recover')">Recuperar</button>
                </div>
                <div class="m-body">
                    <!-- LOGIN -->
                    <div class="m-panel active" id="m-panel-login">
                        <p class="m-title">¡Bienvenido de vuelta!</p>
                        <p class="m-sub">Ingresa a tu cuenta Swapy para continuar.</p>
                        <?php if (!empty($loginMessage)): ?>
                            <div class="m-error-message"><?= htmlspecialchars($loginMessage) ?></div>
                        <?php endif; ?>
                        <form id="loginForm" method="POST" action="<?= base_url('index.php/auth/login') ?>">
                            <div class="m-field">
                                <label>Correo electrónico</label>
                                <div class="m-wrap">
                                    <span class="ico">✉</span>
                                    <input type="email" name="correo" placeholder="tu@correo.com" id="l-email" required>
                                </div>
                            </div>
                            <div class="m-field">
                                <label>Contraseña</label>
                                <div class="m-wrap">
                                    <span class="ico">🔒</span>
                                    <input type="password" name="contrasena" placeholder="••••••••" id="l-pass" required>
                                    <button class="m-toggle" type="button" onclick="mToggle('l-pass',this)">👁</button>
                                </div>
                            </div>
                            <a href="#" class="m-forgot" onclick="switchTab('recover');return false;">¿Olvidaste tu contraseña?</a>
                            <button class="m-submit" type="submit">Entrar a Swapy →</button>
                        </form>
                        <p class="m-switch">¿No tienes cuenta? <a onclick="switchTab('register')">Regístrate gratis</a></p>
                    </div>

                    <!-- REGISTRO -->
                    <div class="m-panel" id="m-panel-register">
                        <p class="m-title">Crea tu cuenta</p>
                        <p class="m-sub">Únete a la comunidad Swapy en segundos.</p>
                        <form id="registerForm" method="POST" onsubmit="return false;">
                            <div class="m-row">
                                <div class="m-field">
                                    <label>Usuario</label>
                                    <div class="m-wrap">
                                        <span class="ico">@</span>
                                        <input type="text" name="username" placeholder="mi_usuario" id="r-user" required>
                                    </div>
                                </div>
                                <div class="m-field">
                                    <label>Teléfono</label>
                                    <div class="m-wrap">
                                        <span class="ico">📱</span>
                                        <input type="tel" name="telefono" placeholder="+57 300..." id="r-phone">
                                    </div>
                                </div>
                            </div>
                            <div class="m-field">
                                <label>Tipo de documento</label>
                                <div class="m-wrap">
                                    <span class="ico">🧾</span>
                                    <select name="documento" required>
                                        <option value="">Selecciona un tipo</option>
                                        <?php foreach ($docTypes as $docType): ?>
                                            <option value="<?= htmlspecialchars($docType['id_doc']) ?>"><?= htmlspecialchars($docType['tipo_doc']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="m-field">
                                <label>Número de documento</label>
                                <div class="m-wrap">
                                    <span class="ico">#️⃣</span>
                                    <input type="text" name="numero_documento" placeholder="123456789" id="r-docnum" required>
                                </div>
                            </div>
                            <div class="m-field">
                                <label>Correo electrónico</label>
                                <div class="m-wrap">
                                    <span class="ico">✉</span>
                                    <input type="email" name="correo" placeholder="tu@correo.com" id="r-email" required>
                                </div>
                            </div>
                            <div class="m-field">
                                <label>Contraseña</label>
                                <div class="m-wrap">
                                    <span class="ico">🔒</span>
                                    <input type="password" name="contrasena" placeholder="Mínimo 8 caracteres" id="r-pass" oninput="mStrength(this.value)" required>
                                    <button class="m-toggle" type="button" onclick="mToggle('r-pass',this)">👁</button>
                                </div>
                                <div class="m-strength">
                                    <div class="m-sbar" id="msb1"></div>
                                    <div class="m-sbar" id="msb2"></div>
                                    <div class="m-sbar" id="msb3"></div>
                                    <div class="m-sbar" id="msb4"></div>
                                    <span class="m-slabel" id="msl">—</span>
                                </div>
                            </div>
                            <input type="hidden" name="rol" value="Cliente">
                            <button class="m-submit" type="button" onclick="submitRegistro()">Crear cuenta →</button>
                        </form>
                        <p class="m-terms">Al registrarte aceptas los <a href="#">Términos de uso</a> y la <a href="#">Política de privacidad</a>.</p>
                        <p class="m-switch">¿Ya tienes cuenta? <a onclick="switchTab('login')">Inicia sesión</a></p>
                    </div>

                    <!-- RECUPERAR -->
                    <div class="m-panel" id="m-panel-recover">
                        <div id="m-rec-form">
                            <p class="m-title">Recupera tu cuenta</p>
                            <p class="m-sub">Te enviaremos un enlace para restablecer tu contraseña.</p>
                            <div class="m-field">
                                <label>Correo electrónico</label>
                                <div class="m-wrap">
                                    <span class="ico">✉</span>
                                    <input type="email" placeholder="tu@correo.com" id="rc-email">
                                </div>
                            </div>
                            <button class="m-submit" onclick="doRecover()">Enviar enlace →</button>
                            <p class="m-switch">¿Recordaste? <a onclick="switchTab('login')">Volver a iniciar sesión</a></p>
                        </div>
                        <div class="m-success" id="m-rec-ok">
                            <div class="m-check">✓</div>
                            <h3>¡Correo enviado!</h3>
                            <p>Revisa tu bandeja de entrada.<br>El enlace expira en 15 minutos.</p>
                            <button class="m-submit" style="margin-top:22px;" onclick="switchTab('login')">Volver al inicio</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL OTP -->
        <div id="otpModal">
            <div class="otp-box">
                <button class="otp-close" onclick="closeOtp()" aria-label="Cerrar">✕</button>
                <div id="otp-form-view">
                    <div class="otp-icon-wrap">✉️</div>
                    <p class="otp-title">Verifica tu correo</p>
                    <p class="otp-subtitle">Te enviamos un código de 6 dígitos a:</p>
                    <p class="otp-email-display" id="otp-email-shown">—</p>
                    <div class="otp-error" id="otp-error-msg">
                        Código incorrecto. Revisa tu correo e inténtalo de nuevo.
                    </div>
                    <div class="otp-digits" role="group" aria-label="Código de verificación">
                        <input class="otp-digit" id="otp-d1" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]" autocomplete="one-time-code" aria-label="Dígito 1">
                        <input class="otp-digit" id="otp-d2" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Dígito 2">
                        <input class="otp-digit" id="otp-d3" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Dígito 3">
                        <input class="otp-digit" id="otp-d4" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Dígito 4">
                        <input class="otp-digit" id="otp-d5" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Dígito 5">
                        <input class="otp-digit" id="otp-d6" type="text" inputmode="numeric" maxlength="1" pattern="[0-9]" aria-label="Dígito 6">
                    </div>
                    <button class="otp-submit" id="otp-verify-btn" disabled onclick="verificarCodigo()">
                        Verificar cuenta →
                    </button>
                    <p class="otp-resend-row">
                        ¿No llegó el código?
                        <a class="otp-resend-link" id="otp-resend-link" onclick="reenviarCodigo()">Reenviar</a>
                        <span class="otp-timer" id="otp-timer">(0:59)</span>
                    </p>
                </div>
                <div class="otp-success" id="otp-success-view">
                    <div class="otp-check">✓</div>
                    <p class="otp-success-title">¡Cuenta verificada!</p>
                    <p class="otp-success-sub">Tu registro fue exitoso.<br>Ya puedes iniciar sesión en Swapy.</p>
                    <button class="otp-submit" style="margin-top:8px;" onclick="closeOtp(); switchTab('login');">
                        Ir a iniciar sesión →
                    </button>
                </div>
            </div>
        </div>

        <script src="assets/script/index_script.js" defer></script>

        <script>
        // URL base exacta del proyecto - NO CAMBIES ESTO
        var BASE_URL = '<?= rtrim(base_url(), '/') ?>/index.php/';

        (function () {
            var otpTimer = null;
            var otpSeconds = 59;

            function allDigits() { return document.querySelectorAll('.otp-digit'); }
            function verifyBtn() { return document.getElementById('otp-verify-btn'); }
            function errorBox() { return document.getElementById('otp-error-msg'); }
            function timerEl() { return document.getElementById('otp-timer'); }
            function resendLink() { return document.getElementById('otp-resend-link'); }

            window.openOtp = function (email) {
                document.getElementById('otp-email-shown').textContent = email || '—';
                document.getElementById('otp-form-view').style.display = '';
                document.getElementById('otp-success-view').classList.remove('show');
                errorBox().classList.remove('show');

                allDigits().forEach(function (d) {
                    d.value = '';
                    d.classList.remove('filled', 'error');
                });
                verifyBtn().disabled = true;

                document.getElementById('otpModal').classList.add('open');
                document.body.style.overflow = 'hidden';

                iniciarTimer();
                setTimeout(function () { allDigits()[0].focus(); }, 150);
            };

            window.closeOtp = function () {
                document.getElementById('otpModal').classList.remove('open');
                document.body.style.overflow = '';
                clearInterval(otpTimer);
            };

            document.getElementById('otpModal').addEventListener('click', function (e) {
                if (e.target === this) closeOtp();
            });

            function iniciarTimer() {
                clearInterval(otpTimer);
                otpSeconds = 59;
                resendLink().classList.remove('ready');
                actualizarTimer();

                otpTimer = setInterval(function () {
                    otpSeconds--;
                    actualizarTimer();
                    if (otpSeconds <= 0) {
                        clearInterval(otpTimer);
                        timerEl().textContent = '';
                        resendLink().classList.add('ready');
                    }
                }, 1000);
            }

            function actualizarTimer() {
                timerEl().textContent = otpSeconds > 0
                    ? '(0:' + String(otpSeconds).padStart(2, '0') + ')'
                    : '';
            }

            window.reenviarCodigo = function () {
                if (!resendLink().classList.contains('ready')) return;
                
                var link = resendLink();
                link.textContent = 'Enviando...';

                fetch(BASE_URL + 'auth/reenviarCodigo', {
                    method: 'POST'
                })
                .then(function(r) {
                    if (!r.ok) throw new Error('Error HTTP: ' + r.status);
                    return r.text().then(function(text) {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Respuesta no es JSON:', text);
                            throw new Error('Respuesta del servidor no válida');
                        }
                    });
                })
                .then(function(data) {
                    if (data.ok) {
                        link.textContent = 'Reenviar';
                        allDigits().forEach(function (d) {
                            d.value = '';
                            d.classList.remove('filled', 'error');
                        });
                        verifyBtn().disabled = true;
                        errorBox().classList.remove('show');
                        iniciarTimer();
                        setTimeout(function() { allDigits()[0].focus(); }, 100);
                    } else {
                        alert('Error: ' + (data.error || 'Error desconocido'));
                        link.textContent = 'Reenviar';
                    }
                })
                .catch(function(err) {
                    alert('Error: ' + err.message);
                    link.textContent = 'Reenviar';
                    console.error(err);
                });
            };

            document.addEventListener('DOMContentLoaded', function () {
                var ds = allDigits();

                ds.forEach(function (d, i) {
                    d.addEventListener('input', function (e) {
                        var v = e.target.value.replace(/\D/g, '');
                        e.target.value = v ? v[0] : '';
                        e.target.classList.toggle('filled', !!e.target.value);
                        e.target.classList.remove('error');
                        if (v && i < ds.length - 1) ds[i + 1].focus();
                        verifyBtn().disabled = [].some.call(ds, function (x) { return !x.value; });
                    });

                    d.addEventListener('keydown', function (e) {
                        if (e.key === 'Backspace' && !d.value && i > 0) {
                            ds[i - 1].focus();
                            ds[i - 1].value = '';
                            ds[i - 1].classList.remove('filled');
                            verifyBtn().disabled = true;
                        }
                    });

                    d.addEventListener('paste', function (e) {
                        e.preventDefault();
                        var text = (e.clipboardData || window.clipboardData)
                            .getData('text').replace(/\D/g, '').slice(0, 6);
                        text.split('').forEach(function (c, j) {
                            if (ds[j]) {
                                ds[j].value = c;
                                ds[j].classList.add('filled');
                                ds[j].classList.remove('error');
                            }
                        });
                        verifyBtn().disabled = [].some.call(ds, function (x) { return !x.value; });
                        if (text.length === 6) verifyBtn().focus();
                    });
                });
            });

            window.verificarCodigo = function () {
                var code = [].map.call(allDigits(), function (d) { return d.value; }).join('');

                if (code.length !== 6) {
                    errorBox().textContent = 'Ingresa los 6 dígitos';
                    errorBox().classList.add('show');
                    return;
                }

                var btn = verifyBtn();
                btn.disabled = true;
                btn.textContent = 'Verificando...';

                var formData = new FormData();
                formData.append('codigo', code);

                fetch(BASE_URL + 'auth/verificarCodigo', {
                    method: 'POST',
                    body: formData
                })
                .then(function(r) {
                    if (!r.ok) throw new Error('Error HTTP: ' + r.status);
                    return r.text().then(function(text) {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Respuesta no es JSON:', text);
                            throw new Error('Respuesta del servidor no válida');
                        }
                    });
                })
                .then(function(data) {
                    btn.disabled = false;
                    btn.textContent = 'Verificar cuenta →';

                    if (data.ok) {
                        mostrarExito();
                        setTimeout(function() {
                            closeOtp();
                            switchTab('login');
                        }, 2500);
                    } else {
                        errorBox().textContent = data.error || 'Código incorrecto';
                        errorBox().classList.add('show');
                        mostrarError();
                    }
                })
                .catch(function(err) {
                    btn.disabled = false;
                    btn.textContent = 'Verificar cuenta →';
                    alert('Error: ' + err.message);
                    mostrarError();
                    console.error(err);
                });
            };

            function mostrarError() {
                errorBox().classList.add('show');
                allDigits().forEach(function (d) { d.classList.add('error'); });
                setTimeout(function () {
                    allDigits().forEach(function (d) { d.classList.remove('error'); });
                }, 1800);
            }

            function mostrarExito() {
                document.getElementById('otp-form-view').style.display = 'none';
                document.getElementById('otp-success-view').classList.add('show');
                clearInterval(otpTimer);
            }

        })();

        function submitRegistro() {
            var form = document.getElementById('registerForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            var formData = new FormData(form);
            var btn = form.querySelector('.m-submit');

            btn.disabled = true;
            btn.textContent = 'Enviando...';

            fetch(BASE_URL + 'auth/registrar', {
                method: 'POST',
                body: formData
            })
            .then(function(r) {
                if (!r.ok) throw new Error('Error HTTP: ' + r.status);
                return r.text().then(function(text) {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Respuesta no es JSON:', text);
                        throw new Error('Respuesta del servidor no válida. Revisa los logs de PHP.');
                    }
                });
            })
            .then(function(data) {
                btn.disabled = false;
                btn.textContent = 'Crear cuenta →';

                if (data.ok) {
                    openOtp(data.correo);
                } else {
                    alert('Error: ' + (data.error || 'Error desconocido'));
                }
            })
            .catch(function(err) {
                btn.disabled = false;
                btn.textContent = 'Crear cuenta →';
                alert('Error: ' + err.message);
                console.error(err);
            });
        }
        </script>

        <?php if (!empty($loginMessage)): ?>
        <script>
            window.addEventListener('DOMContentLoaded', function () {
                if (typeof openModal === 'function') openModal('login');
            });
        </script>
        <?php endif; ?>

    </body>
    </html>