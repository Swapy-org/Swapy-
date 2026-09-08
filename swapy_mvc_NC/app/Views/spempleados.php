<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapy Empleado — Portal de Intermediarios</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/style/employer.css?v=2') ?>">
</head>
<body>

   <nav class="navbar">
    <div class="nav-logo">
        <img src="<?= base_url('assets/IMG/swaperu.png') ?>" alt="Swapy">
        <span>Swapy <span class="brand-role">Empleado</span></span>
    </div>
    <button class="nav-login-btn" onclick="window.location.href='<?= base_url('/') ?>'">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/>
        </svg>
        Volver
    </button>
</nav>

    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <div class="eyebrow">Portal de Intermediarios</div>
                <h1>Trabaja con <span>Swapy</span></h1>
                <p>
                    Únete a nuestro equipo de intermediarios y sé parte del ecosistema de intercambios más innovador. 
                    Gestiona intercambios de forma segura, con horarios flexibles y ganancias competitivas.
                </p>
                <div class="hero-cta">
                    <button class="btn-primary" onclick="openModal()">
                        Acceder al portal
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </button>
                    <div class="note">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        Las credenciales son proporcionadas por el administrador
                    </div>
                </div>
            </div>

            <div class="hero-card">
                <div class="card-icon">🚚</div>
                <h3>Tu rol como intermediario</h3>
                <p>
                    Como empleado Swapy, eres el puente de confianza entre nuestros usuarios. 
                    Tu trabajo garantiza que cada intercambio sea seguro y satisfactorio.
                </p>
                <div class="features">
                    <div class="feature-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Recoge productos en punto A
                    </div>
                    <div class="feature-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Entrega en punto B y recoge el otro producto
                    </div>
                    <div class="feature-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Completa el intercambio en punto A
                    </div>
                    <div class="feature-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Gana por cada intercambio completado
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="how-it-works">
        <div class="section-header">
            <span class="eyebrow">Cómo funciona</span>
            <h2>¿Cómo empiezo?</h2>
            <p>El proceso es simple. El administrador te proporciona acceso y tú empiezas a trabajar.</p>
        </div>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h4>Contacta al admin</h4>
                <p>Solicita tu cuenta de empleado al administrador de Swapy. Él creará tu perfil con tus datos.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h4>Recibe tus credenciales</h4>
                <p>El administrador te entregará tu correo y contraseña para acceder al portal de empleados.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h4>Inicia sesión</h4>
                <p>Usa el botón "Iniciar sesión" en esta página para acceder a tu panel de trabajo.</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-brand">
                <div class="logo">Swapy<span>.</span></div>
                <p>Plataforma de intercambios seguros. Conectamos personas, facilitamos trueques.</p>
            </div>
            <div class="footer-links">
                <div class="footer-col">
                    <h4>Plataforma</h4>
                    <ul>
                        <li><a href="#">Cómo funciona</a></li>
                        <li><a href="#">Términos de uso</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Empresa</h4>
                    <ul>
                        <li><a href="#">Sobre nosotros</a></li>
                        <li><a href="#">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Soporte</h4>
                    <ul>
                        <li><a href="#">Centro de ayuda</a></li>
                        <li><a href="#">Privacidad</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© <?= date('Y') ?> Swapy. Todos los derechos reservados.</p>
        </div>
    </footer>

    <div class="modal-overlay" id="loginModal" onclick="closeModalOutside(event)">
        <div class="modal-box">
            <button class="modal-close" onclick="closeModal()">✕</button>
            <h2>Bienvenido de vuelta</h2>
            <p class="subtitle">Ingresa tus credenciales para acceder al portal de empleados.</p>
            
            <?php if(session()->getFlashdata('error')): ?>
                <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #fca5a5; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; font-family: 'DM Sans', sans-serif;">
                    ⚠️ <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('empleado/login') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="correo" placeholder="tu-correo@swapy.com" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="submit-btn">Ingresar al portal →</button>
            </form>

            <div class="modal-footer">
                <p>
                    <strong>¿No tienes cuenta?</strong><br>
                    Las cuentas de empleado son creadas exclusivamente por el administrador.<br>
                    Contacta al admin para solicitar acceso.
                </p>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('loginModal');

        // Abre el modal automáticamente si hay errores al validar las credenciales
        <?php if(session()->getFlashdata('error')): ?>
            window.addEventListener('DOMContentLoaded', () => { openModal(); });
        <?php endif; ?>

        function openModal() {
            modal.classList.add('open');
        }

        function closeModal() {
            modal.classList.remove('open');
        }

        function closeModalOutside(event) {
            if (event.target === modal) {
                closeModal();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>

</body>
</html>