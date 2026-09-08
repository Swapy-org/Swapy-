<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Swapy — Preguntas Frecuentes</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= base_url('assets/style/pqrf_styles.css') ?>" />
</head>
<body>

<div class="hero">
    <div class="hero-badge">Centro de ayuda</div>
    <h1>Preguntas Frecuentes</h1>
    <p>Encuentra respuestas rápidas a las dudas más comunes. Si no encuentras lo que buscas, agrégala al final.</p>
    <div class="search-wrap">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input id="search" type="text" placeholder="Buscar una pregunta..." autocomplete="off" />
    </div>
</div>

<div class="stats-bar">
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Preguntas</div>
            <div class="stat-value" id="total-count">0</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Contribuidores</div>
            <div class="stat-value" id="contrib-count">1</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/>
                <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
            </svg>
        </div>
        <div>
            <div class="stat-label">Votos útil</div>
            <div class="stat-value" id="votes-count">0</div>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 36px;">
    <div class="categories">
        <button class="cat-btn active" data-cat="todas">Todas</button>
        <button class="cat-btn" data-cat="general">General</button>
        <button class="cat-btn" data-cat="cuenta">Cuenta</button>
        <button class="cat-btn" data-cat="pagos">Pagos</button>
        <button class="cat-btn" data-cat="tecnico">Técnico</button>
        <button class="cat-btn" data-cat="privacidad">Privacidad</button>
    </div>

    <div class="section-label" id="results-label">Mostrando todas las preguntas</div>
    <div class="faq-list" id="faq-list"></div>

    <div class="no-results" id="no-results">
        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <p>No se encontraron preguntas para "<span id="search-term"></span>"</p>
    </div>
</div>

<div class="add-section">
    <div class="add-card">
        <div class="add-header" id="add-header">
            <div class="add-header-left">
                <div class="add-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                </div>
                <div>
                    <h2>Agregar nueva pregunta</h2>
                    <p>¿No encontraste tu duda? Compártela con la comunidad</p>
                </div>
            </div>
            <button class="add-toggle" id="add-toggle">+</button>
        </div>

        <div class="add-form-wrap" id="add-form-wrap">
            <form class="add-form" action="<?= base_url('faq/guardar') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="form-row">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="new-q">Pregunta *</label>
                        <input type="text" id="new-q" name="pregunta" placeholder="¿Cómo puedo...?" maxlength="150" required />
                        <div class="char-count"><span id="q-count">0</span>/150</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="new-cat">Categoría</label>
                        <select id="new-cat" name="categoria">
                            <option value="general">General</option>
                            <option value="cuenta">Cuenta</option>
                            <option value="pagos">Pagos</option>
                            <option value="tecnico">Técnico</option>
                            <option value="privacidad">Privacidad</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="new-author">Tu nombre (opcional)</label>
                        <input type="text" id="new-author" name="autor" placeholder="Anónimo" maxlength="40" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="new-a">Respuesta *</label>
                    <textarea id="new-a" name="respuesta" placeholder="Escribe una respuesta clara y detallada..." maxlength="600" required></textarea>
                    <div class="char-count"><span id="a-count">0</span>/600</div>
                </div>

                <div class="submit-row">
                    <button type="button" class="btn-cancel" id="btn-cancel">Cancelar</button>
                    <button type="submit" class="btn-submit" id="btn-submit" disabled>
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        Publicar pregunta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="footer-note" style="margin-top: 20px;">
        Las preguntas se guardan en esta sesión. Recarga la página para restaurar las originales.
    </div>
</div>

<div class="toast" id="toast">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path d="M20 6 9 17l-5-5"/>
    </svg>
    <span id="toast-msg">¡Pregunta publicada!</span>
</div>

<script src="<?= base_url('assets/script/pqrf_script.js') ?>" defer></script>

</body>
</html>