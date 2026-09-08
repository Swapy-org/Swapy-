<?php
$session = \Config\Services::session();

if (!$session->get('isLoggedIn')) {
    header("Location: " . base_url('?login_error=acceso'));
    exit();
}

$correoUsuario = $session->get('correo') ?? 'cliente@swapy.com';
$rolUsuario = $session->get('rol') ?? 'Cliente';
$nombreUsuario = $session->get('username') ?? explode('@', $correoUsuario)[0];
$inicial = strtoupper(substr($nombreUsuario, 0, 1));

$productoModel = new \App\Models\ProductoModel();
$categorias = $productoModel->obtenerCategorias();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapy — Marketplace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/style/index2_style.css') ?>">
    <style>
        .cat-select {
            appearance: auto !important;
            -webkit-appearance: auto !important;
            display: block !important;
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #2a3f54;
            border-radius: 10px;
            background: #0f1f33;
            color: #e8f2ff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            cursor: pointer;
        }
        .cat-select option {
            background: #0f1f33;
            color: #e8f2ff;
            padding: 8px;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .sin-imagen {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #e8f2ff 0%, #d4e8f7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5a7ea0;
            font-family: 'Syne', sans-serif;
            font-size: 0.9rem;
        }
        /* ESTILOS PARA FILTROS ACTIVOS */
        .cat-card.active {
            box-shadow: 0 0 0 3px #4a9fd4, 0 8px 25px rgba(0,0,0,0.15);
            transform: translateY(-3px);
        }
        .filter-chip.active {
            background: #4a9fd4;
            color: #fff;
        }
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #5a7ea0;
            font-family: 'Syne', sans-serif;
            font-size: 1.1rem;
        }
        .no-results svg {
            width: 60px;
            height: 60px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar-container">
        <img src="<?= base_url('assets/IMG/SWAPY.png') ?>" alt="Logo Swapy" class="logo">
        <div class="menu">
            <a href="<?= base_url('index2') ?>" class="link active">
                <span class="link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </span>
                <span class="link-title">Inicio</span>
            </a>
            <a href="#" class="link" onclick="openModal(); return false;">
                <span class="link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                </span>
                <span class="link-title">Publicar</span>
            </a>
            <a href="<?= base_url('chat') ?>" class="link">
                <span class="link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </span>
                <span class="link-title">Mensajes</span>
            </a>
            <a href="<?= base_url('perfil') ?>" class="link">
                <span class="link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <span class="link-title">Perfil</span>
            </a>
            <a href="<?= base_url('auth?accion=logout') ?>" class="link" style="color: #e74c3c;">
                <span class="link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </span>
                <span class="link-title">Salir</span>
            </a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-banner">
        <p class="hero-eyebrow">Bienvenido al mercado de intercambios</p>
        <h1 class="hero-title">Intercambia lo que tienes<br>por lo que <span>necesitas</span></h1>
        <p class="hero-subtitle">Miles de productos esperando encontrar un nuevo dueño</p>
        <div class="search-bar">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" stroke="#8faec8" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Buscar celulares, ropa, electrodomésticos…" onkeyup="filtrarProductos()">
            <button class="search-btn" onclick="filtrarProductos()">Buscar</button>
        </div>
    </section>

    <!-- CATEGORÍAS -->
    <div class="section-header">
        <h2 class="section-title">Explorar categorías</h2>
    </div>
    <section class="categories-wrapper" id="categoriesWrapper">
        <a href="#" class="cat-card blue active" data-categoria="todos" onclick="filtrarPorCategoria('todos', this); return false;">
            <div class="cat-icon-wrap">📋</div>
            <div><div class="cat-card-title">Todos</div></div>
        </a>
        <?php foreach ($categorias as $cat): ?>
            <?php 
                $colores = ['blue', 'green', 'purple', 'pink', 'yellow', 'orange'];
                $color = $colores[($cat['id_categoria'] - 1) % count($colores)];
                $iconos = ['💻', '🏠', '🎮', '👗', '⚽', '🐾', '🚗', '📦', '🔧', '📱'];
                $icono = $iconos[($cat['id_categoria'] - 1) % count($iconos)];
            ?>
            <a href="#" class="cat-card <?= $color ?>" data-categoria="<?= $cat['id_categoria'] ?>" onclick="filtrarPorCategoria('<?= $cat['id_categoria'] ?>', this); return false;">
                <div class="cat-icon-wrap"><?= $icono ?></div>
                <div><div class="cat-card-title"><?= htmlspecialchars($cat['n_categoria']) ?></div></div>
            </a>
        <?php endforeach; ?>
    </section>

    <!-- MARKETPLACE -->
    <div class="marketplace-layout">
        <section class="products-section">
            <div class="filters-bar">
                <button class="filter-chip active" id="chipTodos" onclick="filtrarPorCategoria('todos')">Todos</button>
                <span class="results-meta" id="results-count">Cargando...</span>
            </div>
            <div class="products-grid" id="products-grid">
                <!-- Productos se cargan dinámicamente -->
            </div>
        </section>

        <aside class="sidebar-tools">
            <p class="sidebar-title">Panel lateral</p>
            <div class="sidebar-divider"></div>
            <div class="sidebar-cta">
                <p>¿Tienes algo para intercambiar?</p>
                <button onclick="openModal()">+ Nueva publicación</button>
            </div>
        </aside>
    </div>

    <!-- MODAL PUBLICAR -->
    <div class="modal-overlay" id="modalOverlay" onclick="handleOverlayClick(event)">
        <div class="modal" id="modalBox">
            <div class="modal-header">
                <h2>Nueva <span>publicación</span></h2>
                <div class="modal-close" onclick="closeModal()">✕</div>
            </div>
            <div class="modal-body">
                <div>
                    <label class="field-label">Foto del producto</label>
                    <div class="upload-zone" id="uploadZone">
                        <input type="file" accept="image/*" id="fileInput" name="imagen" onchange="previewImage(event)">
                        <div class="upload-icon" id="uploadIcon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                        </div>
                        <p id="uploadText"><strong>Haz clic</strong> o arrastra una imagen</p>
                    </div>
                </div>

                <div>
                    <label class="field-label">Nombre del producto</label>
                    <input type="text" class="field-input" id="p-nombre" name="nombre_producto" placeholder="ej. Celular Phone X" required>
                </div>

                <div>
                    <label class="field-label">Categoría</label>
                    <div class="cat-select-wrapper">
                        <select class="cat-select" id="p-categoria" name="fk_id_categoria" required>
                            <option value="" disabled selected>Selecciona una categoría…</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['n_categoria']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <small id="cat-debug" style="color: #4a9fd4; font-size: 0.8rem;"></small>
                </div>

                <div>
                    <label class="field-label">Descripción del producto</label>
                    <textarea class="field-textarea" id="p-descripcion" name="desc_producto" placeholder="Describe tu producto…" required></textarea>
                </div>

                <div>
                    <label class="field-label">Valor estimado</label>
                    <div class="price-wrapper">
                        <span class="price-prefix">$</span>
                        <input type="number" class="price-input" id="p-valor" name="valor_estimado" placeholder="ej. 500000" min="0">
                    </div>
                </div>

                <button class="modal-submit" type="button" onclick="publicarProducto()">Publicar intercambio →</button>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/script/publicar_script.js') ?>"></script>

    <script>
    // Variable global para el filtro actual
    var categoriaActual = 'todos';
    var busquedaActual = '';

    // Debug categoría
    document.getElementById('p-categoria').addEventListener('change', function() {
        document.getElementById('cat-debug').textContent = 'Seleccionado: ' + this.value;
    });

    function publicarProducto() {
        var nombre = document.getElementById('p-nombre').value.trim();
        var descripcion = document.getElementById('p-descripcion').value.trim();
        var categoriaSelect = document.getElementById('p-categoria');
        var categoria = categoriaSelect.value;
        var valor = document.getElementById('p-valor').value;
        var fileInput = document.getElementById('fileInput');

        console.log('Nombre:', nombre, 'Desc:', descripcion, 'Cat:', categoria, 'Valor:', valor);

        if (!nombre) { alert('Ingresa el nombre del producto'); return; }
        if (!descripcion) { alert('Ingresa la descripción'); return; }
        if (!categoria || categoria === '') { alert('Selecciona una categoría'); categoriaSelect.focus(); return; }

        var formData = new FormData();
        formData.append('nombre_producto', nombre);
        formData.append('desc_producto', descripcion);
        formData.append('fk_id_categoria', categoria);
        formData.append('valor_estimado', valor);

        if (fileInput.files.length > 0) {
            formData.append('imagen', fileInput.files[0]);
        }

        var btn = document.querySelector('.modal-submit');
        btn.textContent = 'Publicando...';
        btn.disabled = true;

        fetch('<?= base_url('producto?accion=guardar') ?>', {
            method: 'POST',
            body: formData
        })
        .then(function(r) { 
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json(); 
        })
        .then(function(data) {
            btn.textContent = 'Publicar intercambio →';
            btn.disabled = false;

            if (data.ok) {
                agregarCardAlGrid(data.producto);
                limpiarFormulario();
                closeModal();
                alert('¡Producto publicado exitosamente!');
            } else {
                alert('Error: ' + (data.error || 'Error desconocido del servidor'));
            }
        })
        .catch(function(err) {
            btn.textContent = 'Publicar intercambio →';
            btn.disabled = false;
            alert('Error de conexión: ' + err.message);
            console.error('Error:', err);
        });
    }

    function agregarCardAlGrid(producto) {
        var grid = document.getElementById('products-grid');
        
        var imgHtml;
        if (producto.imagen && producto.imagen !== '' && producto.imagen !== 'null') {
            var imgUrl = '<?= base_url('assets/IMG/') ?>' + producto.imagen;
            imgHtml = '<img src="' + imgUrl + '" alt="' + producto.nombre_producto + '" style="width: 100%; height: 200px; object-fit: cover;">';
        } else {
            imgHtml = '<div class="sin-imagen">📷 Sin imagen</div>';
        }

        var valorFormateado = producto.valor_estimado 
            ? parseFloat(producto.valor_estimado).toLocaleString('es-CO') 
            : '0';

        var card = document.createElement('div');
        card.className = 'product-item';
        card.style.animation = 'fadeInUp 0.4s ease';
        
        card.innerHTML = 
            '<div class="card__image-wrap">' +
                imgHtml +
                '<div class="card__badge">NUEVO</div>' +
            '</div>' +
            '<div class="card__content">' +
                '<p class="card__title">' + producto.nombre_producto + '</p>' +
                '<p class="card__description">' + producto.desc_producto + '</p>' +
            '</div>' +
            '<div class="card__footer">' +
                '<span class="card__price">Cambio &gt; $' + valorFormateado + '</span>' +
                '<div class="card__chat-btn" title="Chatear sobre este producto" onclick="irAlChat(' + producto.id_producto + ')">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">' +
                        '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>' +
                    '</svg>' +
                '</div>' +
            '</div>';

        grid.insertBefore(card, grid.firstChild);
        document.getElementById('results-count').textContent = grid.children.length + ' publicaciones';
    }

    // ============================================
    // NUEVO: IR AL CHAT DE UN PRODUCTO ESPECÍFICO
    // ============================================
    function irAlChat(idProducto) {
        window.location.href = '<?= base_url('chat') ?>?producto=' + idProducto;
    }

    function limpiarFormulario() {
        document.getElementById('p-nombre').value = '';
        document.getElementById('p-descripcion').value = '';
        document.getElementById('p-categoria').value = '';
        document.getElementById('p-valor').value = '';
        document.getElementById('fileInput').value = '';
        document.getElementById('cat-debug').textContent = '';
        document.getElementById('uploadText').innerHTML = '<strong>Haz clic</strong> o arrastra una imagen';
        var icon = document.getElementById('uploadIcon');
        if (icon) icon.style.display = '';
        var oldPreview = document.querySelector('.preview-img');
        if (oldPreview) oldPreview.remove();
    }

    // ============================================
    // NUEVO: FILTRAR POR CATEGORÍA
    // ============================================
    function filtrarPorCategoria(categoriaId, elemento) {
        categoriaActual = categoriaId;
        
        // Actualizar UI de categorías
        document.querySelectorAll('.cat-card').forEach(function(card) {
            card.classList.remove('active');
        });
        if (elemento) {
            elemento.classList.add('active');
        } else {
            document.querySelector('.cat-card[data-categoria="' + categoriaId + '"]')?.classList.add('active');
        }
        
        // Actualizar chip de filtros
        document.querySelectorAll('.filter-chip').forEach(function(chip) {
            chip.classList.remove('active');
        });
        document.getElementById('chipTodos').classList.toggle('active', categoriaId === 'todos');
        
        cargarProductosFiltrados();
    }

    // ============================================
    // NUEVO: FILTRAR POR BÚSQUEDA
    // ============================================
    function filtrarProductos() {
        busquedaActual = document.getElementById('searchInput').value.trim();
        cargarProductosFiltrados();
    }

    // ============================================
    // NUEVO: CARGAR PRODUCTOS CON FILTROS
    // ============================================
    function cargarProductosFiltrados() {
        var grid = document.getElementById('products-grid');
        grid.innerHTML = '<div class="no-results"><p>Cargando...</p></div>';
        
        var url = '<?= base_url('producto?accion=filtrar') ?>';
        var params = [];
        
        if (categoriaActual && categoriaActual !== 'todos') {
            params.push('categoria=' + encodeURIComponent(categoriaActual));
        }
        
        if (busquedaActual) {
            params.push('q=' + encodeURIComponent(busquedaActual));
        }
        
        if (params.length > 0) {
            url += '&' + params.join('&');
        }
        
        fetch(url)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.ok && data.productos) {
                grid.innerHTML = '';
                
                if (data.productos.length === 0) {
                    grid.innerHTML = 
                        '<div class="no-results">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">' +
                                '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>' +
                            '</svg>' +
                            '<p>No se encontraron productos</p>' +
                            '<small>Intenta con otra búsqueda o categoría</small>' +
                        '</div>';
                    document.getElementById('results-count').textContent = '0 publicaciones';
                    return;
                }
                
                data.productos.forEach(function(prod) {
                    agregarCardAlGrid(prod);
                });
            } else {
                grid.innerHTML = '<div class="no-results"><p>Error al cargar productos</p></div>';
            }
        })
        .catch(function(err) {
            console.error('Error cargando productos:', err);
            grid.innerHTML = '<div class="no-results"><p>Error de conexión</p></div>';
        });
    }

    function cargarProductos() {
        cargarProductosFiltrados();
    }

    document.addEventListener('DOMContentLoaded', function() {
        cargarProductos();
    });
    </script>

</body>
</html>