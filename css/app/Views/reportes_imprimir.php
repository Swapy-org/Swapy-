<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Swapy - <?= date('d/m/Y') ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #fff;
            color: #333;
            padding: 40px;
            max-width: 900px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 { color: #2c3e50; font-size: 28px; margin-bottom: 8px; }
        .header p { color: #7f8c8d; font-size: 14px; }
        
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .kpi-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .kpi-value {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
        }
        .kpi-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            margin-top: 5px;
        }
        
        h2 {
            color: #2c3e50;
            font-size: 18px;
            margin: 25px 0 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13px;
        }
        th {
            background: #2c3e50;
            color: #fff;
            padding: 10px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        tr:nth-child(even) { background: #f8f9fa; }
        
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-admin { background: #fee; color: #c33; }
        .badge-cliente { background: #e3f2fd; color: #1976d2; }
        .badge-empleado { background: #e8f5e9; color: #388e3c; }
        .badge-activo { background: #e8f5e9; color: #388e3c; }
        .badge-inactivo { background: #ffebee; color: #c62828; }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2c3e50;
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .print-btn:hover { background: #34495e; }
        
        @media print {
            .print-btn { display: none; }
            body { padding: 20px; }
        }
    </style>
</head>
<body>

    <button class="print-btn" onclick="window.print()">🖨️ Imprimir / Guardar PDF</button>

    <div class="header">
        <h1>📊 SWAPY - Reporte de Plataforma</h1>
        <p>Generado el <?= date('d/m/Y \a \l\a\s H:i:s') ?></p>
    </div>

    <!-- KPIs -->
    <div class="kpi-grid">
        <div class="kpi-box">
            <div class="kpi-value"><?= number_format($totalUsuarios) ?></div>
            <div class="kpi-label">Total Usuarios</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-value"><?= number_format($usuariosActivos) ?></div>
            <div class="kpi-label">Usuarios Activos</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-value"><?= number_format($usuariosInactivos) ?></div>
            <div class="kpi-label">Usuarios Inactivos</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-value"><?= number_format($totalProductos) ?></div>
            <div class="kpi-label">Total Productos</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-value"><?= number_format($totalCategorias) ?></div>
            <div class="kpi-label">Categorías</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-value">$<?= number_format($valorTotalProductos, 0, ',', '.') ?></div>
            <div class="kpi-label">Valor Total</div>
        </div>
    </div>

    <!-- Usuarios por Rol -->
    <h2>👤 Usuarios por Rol</h2>
    <table>
        <thead>
            <tr><th>Rol</th><th>Cantidad</th><th>Porcentaje</th></tr>
        </thead>
        <tbody>
            <?php 
            $maxTotal = max(array_column($usuariosPorRol, 'total')) ?? 1;
            foreach ($usuariosPorRol as $rol): 
                $pct = round(($rol['total'] / array_sum(array_column($usuariosPorRol, 'total'))) * 100);
            ?>
            <tr>
                <td><span class="badge badge-<?= strtolower($rol['rol']) ?>"><?= $rol['rol'] ?></span></td>
                <td><?= $rol['total'] ?></td>
                <td><?= $pct ?>%</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Productos por Categoría -->
    <h2>📦 Productos por Categoría</h2>
    <table>
        <thead>
            <tr><th>Categoría</th><th>Productos</th><th>Valor Total</th></tr>
        </thead>
        <tbody>
            <?php foreach ($productosPorCategoria as $cat): ?>
            <tr>
                <td><?= $cat['n_categoria'] ?></td>
                <td><?= $cat['total'] ?></td>
                <td>$<?= number_format($cat['valor_total'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Top Productos -->
    <h2>🏆 Top 10 Productos Más Valiosos</h2>
    <table>
        <thead>
            <tr><th>#</th><th>Producto</th><th>Categoría</th><th>Publicado por</th><th>Valor</th></tr>
        </thead>
        <tbody>
            <?php foreach ($topProductos as $i => $prod): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $prod['nombre_producto'] ?></td>
                <td><?= $prod['n_categoria'] ?? 'Sin cat.' ?></td>
                <td><?= $prod['username'] ?? 'Anónimo' ?></td>
                <td>$<?= number_format($prod['valor_estimado'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Últimos Usuarios -->
    <h2>🆕 Últimos Usuarios Registrados</h2>
    <table>
        <thead>
            <tr><th>ID</th><th>Usuario</th><th>Correo</th><th>Rol</th><th>Estado</th></tr>
        </thead>
        <tbody>
            <?php foreach ($ultimosUsuarios as $user): ?>
            <tr>
                <td>#<?= $user['id_usuario'] ?></td>
                <td><?= $user['username'] ?? 'N/A' ?></td>
                <td><?= $user['correo'] ?? 'N/A' ?></td>
                <td><span class="badge badge-<?= strtolower($user['rol'] ?? 'usuario') ?>"><?= $user['rol'] ?? 'Sin rol' ?></span></td>
                <td><span class="badge badge-<?= ($user['estado'] ?? '') === 'activo' ? 'activo' : 'inactivo' ?>"><?= $user['estado'] ?? 'inactivo' ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Reporte generado por Swapy Admin | <?= date('Y') ?></p>
        <p>Página <?= $totalUsuarios > 0 ? '1' : '' ?> de 1</p>
    </div>

</body>
</html>