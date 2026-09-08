<?php

namespace App\Controllers;

class ReporteController extends BaseController
{
    public function index()
    {
        $session = \Config\Services::session();
        
        if (!$session->get('isLoggedIn') || strtolower($session->get('rol') ?? '') !== 'administrador') {
            return redirect()->to(base_url('?login_error=acceso'));
        }

        $db = \Config\Database::connect();

        // ==================== KPIs GENERALES ====================
        $data['totalUsuarios'] = $db->query("SELECT COUNT(*) as total FROM usuario")->getRow()->total ?? 0;
        $data['totalProductos'] = $db->query("SELECT COUNT(*) as total FROM productos")->getRow()->total ?? 0;
        $data['totalCategorias'] = $db->query("SELECT COUNT(*) as total FROM categorias")->getRow()->total ?? 0;
        $data['usuariosActivos'] = $db->query("SELECT COUNT(*) as total FROM usuario WHERE estado = 'activo'")->getRow()->total ?? 0;
        $data['usuariosInactivos'] = $db->query("SELECT COUNT(*) as total FROM usuario WHERE estado != 'activo' OR estado IS NULL")->getRow()->total ?? 0;
        $data['usuariosVerificados'] = $db->query("SELECT COUNT(*) as total FROM usuario WHERE verificado = 'SI' OR verificado = '1'")->getRow()->total ?? 0;
        $data['productosConImagen'] = $db->query("SELECT COUNT(*) as total FROM productos WHERE imagen IS NOT NULL AND imagen != '' AND imagen != 'null'")->getRow()->total ?? 0;
        $data['productosSinImagen'] = $db->query("SELECT COUNT(*) as total FROM productos WHERE imagen IS NULL OR imagen = '' OR imagen = 'null'")->getRow()->total ?? 0;
        $valorTotal = $db->query("SELECT SUM(valor_estimado) as total FROM productos WHERE valor_estimado > 0")->getRow()->total ?? 0;
        $data['valorTotalProductos'] = $valorTotal;

        // Usuarios por rol
        $data['usuariosPorRol'] = $db->query("
            SELECT CASE WHEN rol IS NULL OR rol = '' THEN 'Sin rol' ELSE rol END as rol, COUNT(*) as total 
            FROM usuario GROUP BY rol ORDER BY total DESC
        ")->getResultArray();

        // Productos por categoría
        $data['productosPorCategoria'] = $db->query("
            SELECT c.n_categoria, COUNT(p.id_producto) as total, COALESCE(SUM(p.valor_estimado), 0) as valor_total
            FROM categorias c
            LEFT JOIN productos p ON c.id_categoria = p.fk_id_categoria
            GROUP BY c.id_categoria, c.n_categoria
            ORDER BY total DESC
        ")->getResultArray();

        // Top productos
        $data['topProductos'] = $db->query("
            SELECT p.nombre_producto, p.valor_estimado, c.n_categoria, u.username
            FROM productos p
            LEFT JOIN categorias c ON p.fk_id_categoria = c.id_categoria
            LEFT JOIN usuario u ON p.fk_id_usuario = u.id_usuario
            WHERE p.valor_estimado > 0
            ORDER BY p.valor_estimado DESC
            LIMIT 10
        ")->getResultArray();

        // Últimos productos
        $data['ultimosProductos'] = $db->query("
            SELECT p.nombre_producto, p.valor_estimado, c.n_categoria, u.username, p.imagen
            FROM productos p
            LEFT JOIN categorias c ON p.fk_id_categoria = c.id_categoria
            LEFT JOIN usuario u ON p.fk_id_usuario = u.id_usuario
            ORDER BY p.id_producto DESC
            LIMIT 5
        ")->getResultArray();

        // Últimos usuarios
        $data['ultimosUsuarios'] = $db->query("
            SELECT id_usuario, username, correo, rol, estado, verificado
            FROM usuario
            ORDER BY id_usuario DESC
            LIMIT 5
        ")->getResultArray();

        return view('reportes', $data);
    }

    // ==================== VERSIÓN IMPRIMIBLE PARA PDF ====================
    public function imprimir()
    {
        $session = \Config\Services::session();
        
        if (!$session->get('isLoggedIn') || strtolower($session->get('rol') ?? '') !== 'administrador') {
            return redirect()->to(base_url('?login_error=acceso'));
        }

        $db = \Config\Database::connect();

        // Obtener todos los datos
        $data['totalUsuarios'] = $db->query("SELECT COUNT(*) as total FROM usuario")->getRow()->total ?? 0;
        $data['totalProductos'] = $db->query("SELECT COUNT(*) as total FROM productos")->getRow()->total ?? 0;
        $data['totalCategorias'] = $db->query("SELECT COUNT(*) as total FROM categorias")->getRow()->total ?? 0;
        $data['usuariosActivos'] = $db->query("SELECT COUNT(*) as total FROM usuario WHERE estado = 'activo'")->getRow()->total ?? 0;
        $data['usuariosInactivos'] = $db->query("SELECT COUNT(*) as total FROM usuario WHERE estado != 'activo' OR estado IS NULL")->getRow()->total ?? 0;
        $data['usuariosVerificados'] = $db->query("SELECT COUNT(*) as total FROM usuario WHERE verificado = 'SI' OR verificado = '1'")->getRow()->total ?? 0;
        $data['productosConImagen'] = $db->query("SELECT COUNT(*) as total FROM productos WHERE imagen IS NOT NULL AND imagen != '' AND imagen != 'null'")->getRow()->total ?? 0;
        $data['productosSinImagen'] = $db->query("SELECT COUNT(*) as total FROM productos WHERE imagen IS NULL OR imagen = '' OR imagen = 'null'")->getRow()->total ?? 0;
        $data['valorTotalProductos'] = $db->query("SELECT SUM(valor_estimado) as total FROM productos WHERE valor_estimado > 0")->getRow()->total ?? 0;

        $data['usuariosPorRol'] = $db->query("
            SELECT CASE WHEN rol IS NULL OR rol = '' THEN 'Sin rol' ELSE rol END as rol, COUNT(*) as total 
            FROM usuario GROUP BY rol ORDER BY total DESC
        ")->getResultArray();

        $data['productosPorCategoria'] = $db->query("
            SELECT c.n_categoria, COUNT(p.id_producto) as total, COALESCE(SUM(p.valor_estimado), 0) as valor_total
            FROM categorias c
            LEFT JOIN productos p ON c.id_categoria = p.fk_id_categoria
            GROUP BY c.id_categoria, c.n_categoria
            ORDER BY total DESC
        ")->getResultArray();

        $data['topProductos'] = $db->query("
            SELECT p.nombre_producto, p.valor_estimado, c.n_categoria, u.username
            FROM productos p
            LEFT JOIN categorias c ON p.fk_id_categoria = c.id_categoria
            LEFT JOIN usuario u ON p.fk_id_usuario = u.id_usuario
            WHERE p.valor_estimado > 0
            ORDER BY p.valor_estimado DESC
            LIMIT 10
        ")->getResultArray();

        $data['ultimosUsuarios'] = $db->query("
            SELECT id_usuario, username, correo, rol, estado, verificado
            FROM usuario
            ORDER BY id_usuario DESC
            LIMIT 10
        ")->getResultArray();

        return view('reportes_imprimir', $data);
    }
}