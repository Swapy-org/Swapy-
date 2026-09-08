<?php
$session = \Config\Services::session();
$rolUsuario = $session->get('rol') ?? 'Cliente';
$nombreUsuario = $session->get('username') ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes — Panel Admin | Swapy</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'DM Sans', sans-serif;
            background: #0a1628;
            color: #e8f2ff;
            min-height: 100vh;
        }
        
        .admin-nav {
            background: linear-gradient(135deg, #0f1f33 0%, #1a2d4a 100%);
            border-bottom: 1px solid #2a3f54;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .nav-brand {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.4rem;
            color: #4a9fd4;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .nav-brand svg {
            width: 28px;
            height: 28px;
        }
        
        .nav-links {
            display: flex;
            gap: 8px;
        }
        
        .nav-link {
            color: #8faec8;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(74, 159, 212, 0.15);
            color: #4a9fd4;
        }
        
        .nav-user {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #8faec8;
            font-size: 0.9rem;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4a9fd4, #6b8cce);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            font-size: 0.85rem;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px;
        }
        
        .page-header {
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .page-title-group {
            flex: 1;
        }
        
        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }
        
        .page-subtitle {
            color: #8faec8;
            font-size: 0.95rem;
        }

        .btn-pdf {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: #fff;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        
        .btn-pdf:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
        }
        
        .btn-pdf svg {
            width: 20px;
            height: 20px;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }
        
        .kpi-card {
            background: linear-gradient(135deg, #0f1f33 0%, #162a40 100%);
            border: 1px solid #2a3f54;
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .kpi-card:hover {
            transform: translateY(-4px);
            border-color: #4a9fd4;
            box-shadow: 0 12px 40px rgba(74, 159, 212, 0.15);
        }
        
        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #4a9fd4, #6b8cce);
        }
        
        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 1.5rem;
        }
        
        .kpi-icon.blue { background: rgba(74, 159, 212, 0.15); }
        .kpi-icon.green { background: rgba(46, 204, 113, 0.15); }
        .kpi-icon.purple { background: rgba(155, 89, 182, 0.15); }
        .kpi-icon.orange { background: rgba(230, 126, 34, 0.15); }
        .kpi-icon.red { background: rgba(231, 76, 60, 0.15); }
        .kpi-icon.yellow { background: rgba(241, 196, 15, 0.15); }
        
        .kpi-value {
            font-family: 'Syne', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
        }
        
        .kpi-label {
            color: #8faec8;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .kpi-delta {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.8rem;
            margin-top: 8px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .kpi-delta.positive {
            background: rgba(46, 204, 113, 0.15);
            color: #2ecc71;
        }
        
        .kpi-delta.negative {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
        }

        .section {
            background: linear-gradient(135deg, #0f1f33 0%, #162a40 100%);
            border: 1px solid #2a3f54;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #2a3f54;
        }
        
        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(74, 159, 212, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 16px;
        }
        
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            text-align: left;
            padding: 12px 16px;
            color: #8faec8;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #2a3f54;
        }
        
        .data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #1a2d4a;
            font-size: 0.9rem;
        }
        
        .data-table tr:hover td {
            background: rgba(74, 159, 212, 0.05);
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-admin { background: rgba(231, 76, 60, 0.2); color: #e74c3c; }
        .badge-cliente { background: rgba(74, 159, 212, 0.2); color: #4a9fd4; }
        .badge-empleado { background: rgba(46, 204, 113, 0.2); color: #2ecc71; }
        .badge-activo { background: rgba(46, 204, 113, 0.2); color: #2ecc71; }
        .badge-inactivo { background: rgba(149, 165, 166, 0.2); color: #95a5a6; }
        .badge-si { background: rgba(46, 204, 113, 0.2); color: #2ecc71; }
        .badge-no { background: rgba(231, 76, 60, 0.2); color: #e74c3c; }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #1a2d4a;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 8px;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.6s ease;
        }
        
        .progress-fill.blue { background: linear-gradient(90deg, #4a9fd4, #6b8cce); }
        .progress-fill.green { background: linear-gradient(90deg, #2ecc71, #27ae60); }
        .progress-fill.orange { background: linear-gradient(90deg, #e67e22, #d35400); }

        @media (max-width: 768px) {
            .container { padding: 16px; }
            .kpi-grid { grid-template-columns: 1fr; }
            .chart-grid { grid-template-columns: 1fr; }
            .nav-links { display: none; }
            .page-header { flex-direction: column; gap: 16px; }
        }
    </style>
</head>
<body>

    <nav class="admin-nav">
        <div class="nav-brand">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/>
            </svg>
            Swapy Admin
        </div>
        <div class="nav-links">
            <a href="<?= base_url('admin') ?>" class="nav-link">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Usuarios
            </a>
            <a href="<?= base_url('index2') ?>" class="nav-link">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                </svg>
                Marketplace
            </a>
            <a href="<?= base_url('reportes') ?>" class="nav-link active">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/>
                </svg>
                Reportes
            </a>
        </div>
        <div class="nav-user">
            <span><?= htmlspecialchars($nombreUsuario) ?></span>
            <div class="user-avatar"><?= strtoupper(substr($nombreUsuario, 0, 1)) ?></div>
            <a href="<?= base_url('auth?accion=logout') ?>" style="color: #e74c3c; text-decoration: none; margin-left: 16px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </a>
        </div>
    </nav>

    <div class="container">
        
        <div class="page-header">
            <div class="page-title-group">
                <h1 class="page-title">📊 Panel de Reportes</h1>
                <p class="page-subtitle">Estadísticas y métricas de la plataforma en tiempo real</p>
            </div>
            <a href="<?= base_url('reportes/imprimir') ?>" class="btn-pdf" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 9V2h12v7"/>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <path d="M6 14h12v8H6z"/>
                </svg>
                Imprimir / Guardar PDF
            </a>
        </div>

        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon blue">👥</div>
                <div class="kpi-value"><?= number_format($totalUsuarios) ?></div>
                <div class="kpi-label">Total Usuarios</div>
                <div class="kpi-delta positive">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                    <?= $usuariosActivos ?> activos
                </div>
            </div>
            
            <div class="kpi-card">
                <div class="kpi-icon green">📦</div>
                <div class="kpi-value"><?= number_format($totalProductos) ?></div>
                <div class="kpi-label">Total Productos</div>
                <div class="kpi-delta positive">
                    <?= $productosConImagen ?> con imagen
                </div>
            </div>
            
            <div class="kpi-card">
                <div class="kpi-icon purple">🏷️</div>
                <div class="kpi-value"><?= number_format($totalCategorias) ?></div>
                <div class="kpi-label">Categorías</div>
                <div class="kpi-delta positive">
                    Activas
                </div>
            </div>
            
            <div class="kpi-card">
                <div class="kpi-icon orange">💰</div>
                <div class="kpi-value">$<?= number_format($valorTotalProductos, 0, ',', '.') ?></div>
                <div class="kpi-label">Valor Total Estimado</div>
                <div class="kpi-delta positive">
                    En productos
                </div>
            </div>
            
            <div class="kpi-card">
                <div class="kpi-icon yellow">✅</div>
                <div class="kpi-value"><?= number_format($usuariosVerificados) ?></div>
                <div class="kpi-label">Usuarios Verificados</div>
                <div class="kpi-delta <?= ($usuariosVerificados / max($totalUsuarios, 1) * 100) > 50 ? 'positive' : 'negative' ?>">
                    <?= round(($usuariosVerificados / max($totalUsuarios, 1)) * 100) ?>% del total
                </div>
            </div>
            
            <div class="kpi-card">
                <div class="kpi-icon red">📷</div>
                <div class="kpi-value"><?= number_format($productosSinImagen) ?></div>
                <div class="kpi-label">Sin Imagen</div>
                <div class="kpi-delta negative">
                    Pendientes
                </div>
            </div>
        </div>

        <div class="chart-grid">
            <div class="section">
                <div class="section-header">
                    <div class="section-title">
                        <div class="section-icon">📊</div>
                        Productos por Categoría
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="chartCategorias"></canvas>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">
                    <div class="section-title">
                        <div class="section-icon">🎭</div>
                        Distribución de Usuarios
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="chartRoles"></canvas>
                </div>
            </div>
        </div>

        <div class="chart-grid">
            <div class="section">
                <div class="section-header">
                    <div class="section-title">
                        <div class="section-icon">🏆</div>
                        Top 10 Productos Más Valiosos
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Publicado por</th>
                            <th style="text-align: right;">Valor Estimado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topProductos as $i => $prod): ?>
                        <tr>
                            <td style="font-weight: 700; color: #4a9fd4;"><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($prod['nombre_producto']) ?></td>
                            <td><span class="badge badge-cliente"><?= htmlspecialchars($prod['n_categoria'] ?? 'Sin cat.') ?></span></td>
                            <td><?= htmlspecialchars($prod['username'] ?? 'Anónimo') ?></td>
                            <td style="text-align: right; font-weight: 600; color: #2ecc71;">
                                $<?= number_format($prod['valor_estimado'] ?? 0, 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="section">
                <div class="section-header">
                    <div class="section-title">
                        <div class="section-icon">👤</div>
                        Usuarios por Rol
                    </div>
                </div>
                <?php 
                    $maxUsuariosRol = max(array_column($usuariosPorRol, 'total')) ?? 1;
                    $coloresRol = ['administrador' => 'blue', 'cliente' => 'green', 'empleado' => 'orange', 'Sin rol' => 'red'];
                ?>
                <?php foreach ($usuariosPorRol as $rol): 
                    $porcentaje = ($rol['total'] / $maxUsuariosRol) * 100;
                    $color = $coloresRol[$rol['rol']] ?? 'blue';
                    $rolClass = 'badge-' . strtolower(str_replace(' ', '-', $rol['rol']));
                ?>
                <div style="margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="display: flex; align-items: center; gap: 8px;">
                            <span class="badge <?= $rolClass ?>"><?= htmlspecialchars($rol['rol']) ?></span>
                            <span style="font-size: 0.9rem;"><?= $rol['total'] ?> usuarios</span>
                        </span>
                        <span style="font-weight: 700; color: #4a9fd4;"><?= round($porcentaje) ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill <?= $color ?>" style="width: <?= $porcentaje ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="chart-grid">
            <div class="section">
                <div class="section-header">
                    <div class="section-title">
                        <div class="section-icon">🆕</div>
                        Últimos Productos Publicados
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Usuario</th>
                            <th style="text-align: right;">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimosProductos as $prod): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <?php if (!empty($prod['imagen']) && $prod['imagen'] !== 'null'): ?>
                                        <img src="<?= base_url('assets/IMG/' . $prod['imagen']) ?>" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;" alt="">
                                    <?php else: ?>
                                        <div style="width: 40px; height: 40px; border-radius: 8px; background: #1a2d4a; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">📷</div>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($prod['nombre_producto']) ?>
                                </div>
                            </td>
                            <td><span class="badge badge-cliente"><?= htmlspecialchars($prod['n_categoria'] ?? 'Sin cat.') ?></span></td>
                            <td><?= htmlspecialchars($prod['username'] ?? 'Anónimo') ?></td>
                            <td style="text-align: right; font-weight: 600;">$<?= number_format($prod['valor_estimado'] ?? 0, 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="section">
                <div class="section-header">
                    <div class="section-title">
                        <div class="section-icon">👋</div>
                        Últimos Usuarios Registrados
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Verificado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimosUsuarios as $user): 
                            $rolClass = 'badge-' . strtolower($user['rol'] ?? 'usuario');
                            $estadoClass = ($user['estado'] ?? '') === 'activo' ? 'badge-activo' : 'badge-inactivo';
                            $verifClass = (strtoupper($user['verificado'] ?? '') === 'SI' || $user['verificado'] == '1') ? 'badge-si' : 'badge-no';
                            $verifText = (strtoupper($user['verificado'] ?? '') === 'SI' || $user['verificado'] == '1') ? '✓ Sí' : '✗ No';
                        ?>
                        <tr>
                            <td style="font-weight: 700; color: #4a9fd4;">#<?= $user['id_usuario'] ?></td>
                            <td><?= htmlspecialchars($user['username'] ?? 'N/A') ?></td>
                            <td style="font-size: 0.85rem; color: #8faec8;"><?= htmlspecialchars($user['correo'] ?? 'N/A') ?></td>
                            <td><span class="badge <?= $rolClass ?>"><?= htmlspecialchars($user['rol'] ?? 'Sin rol') ?></span></td>
                            <td><span class="badge <?= $estadoClass ?>"><?= htmlspecialchars($user['estado'] ?? 'inactivo') ?></span></td>
                            <td><span class="badge <?= $verifClass ?>"><?= $verifText ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        const ctxCat = document.getElementById('chartCategorias').getContext('2d');
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($productosPorCategoria, 'n_categoria')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($productosPorCategoria, 'total')) ?>,
                    backgroundColor: [
                        '#4a9fd4', '#2ecc71', '#9b59b6', '#e67e22', 
                        '#e74c3c', '#f1c40f', '#1abc9c', '#34495e'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { color: '#8faec8', font: { family: 'DM Sans', size: 12 }, padding: 16 }
                    },
                    tooltip: {
                        backgroundColor: '#0f1f33',
                        titleColor: '#fff',
                        bodyColor: '#e8f2ff',
                        borderColor: '#2a3f54',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' productos';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });

        const ctxRol = document.getElementById('chartRoles').getContext('2d');
        new Chart(ctxRol, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($usuariosPorRol, 'rol')) ?>,
                datasets: [{
                    label: 'Usuarios',
                    data: <?= json_encode(array_column($usuariosPorRol, 'total')) ?>,
                    backgroundColor: ['#4a9fd4', '#2ecc71', '#e67e22', '#e74c3c'],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f1f33',
                        titleColor: '#fff',
                        bodyColor: '#e8f2ff',
                        borderColor: '#2a3f54',
                        borderWidth: 1,
                        padding: 12
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#1a2d4a' },
                        ticks: { color: '#8faec8', font: { family: 'DM Sans' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#8faec8', font: { family: 'DM Sans' } }
                    }
                }
            }
        });
    </script>

</body>
</html>