<?php

namespace App\Controllers;

use App\Models\ProductoModel;

class ProductoController extends BaseController
{
    public function index()
    {
        $session = \Config\Services::session();
        $accion = $_GET['accion'] ?? '';

        if ($accion === 'guardar') {
            return $this->guardar($session);
        }

        if ($accion === 'listar') {
            return $this->listar();
        }

        if ($accion === 'filtrar') {
            return $this->filtrar();
        }

        return redirect()->to(base_url());
    }

    private function guardar($session)
    {
        header('Content-Type: application/json');

        if (!$session->get('isLoggedIn')) {
            echo json_encode(['ok' => false, 'error' => 'Debes iniciar sesión']);
            return;
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
            return;
        }

        $idUsuario = $session->get('id_usuario');
        
        if (!$idUsuario) {
            echo json_encode(['ok' => false, 'error' => 'Sesión inválida']);
            return;
        }

        $nombre = trim($this->request->getPost('nombre_producto') ?? '');
        $descripcion = trim($this->request->getPost('desc_producto') ?? '');
        $categoria = intval($this->request->getPost('fk_id_categoria') ?? 0);
        $valor = floatval($this->request->getPost('valor_estimado') ?? 0);

        if (empty($nombre)) {
            echo json_encode(['ok' => false, 'error' => 'El nombre es obligatorio']);
            return;
        }
        
        if (empty($descripcion)) {
            echo json_encode(['ok' => false, 'error' => 'La descripción es obligatoria']);
            return;
        }
        
        if ($categoria <= 0) {
            echo json_encode(['ok' => false, 'error' => 'La categoría es obligatoria']);
            return;
        }

        $db = \Config\Database::connect();

        // MANEJAR IMAGEN
        $rutaImagen = null;
        $file = $this->request->getFile('imagen');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                $uploadPath = FCPATH . 'assets/IMG/productos';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);
                $rutaImagen = 'productos/' . $newName;
                
            } catch (\Exception $e) {
                error_log('Error imagen: ' . $e->getMessage());
            }
        }

        // Generar ID manual
        $ultimo = $db->query("SELECT MAX(id_producto) as max_id FROM productos")->getRow();
        $nuevoId = ($ultimo->max_id ?? 0) + 1;
        if ($nuevoId <= 0) $nuevoId = 1;

        // Insertar producto
        $ok = $db->query(
            "INSERT INTO productos (id_producto, nombre_producto, valor_estimado, desc_producto, imagen, fk_id_categoria, fk_cod_impulso, fk_id_doc, fk_id_usuario) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $nuevoId,
                $nombre,
                $valor,
                $descripcion,
                $rutaImagen,
                $categoria,
                null,
                1,
                (int)$idUsuario
            ]
        );

        if (!$ok) {
            $error = $db->error();
            echo json_encode([
                'ok' => false, 
                'error' => 'Error SQL: ' . ($error['message'] ?? 'Desconocido')
            ]);
            return;
        }

        // Obtener producto creado
        $producto = $db->query(
            "SELECT p.*, c.n_categoria 
             FROM productos p 
             LEFT JOIN categorias c ON p.fk_id_categoria = c.id_categoria 
             WHERE p.id_producto = ?",
            [$nuevoId]
        )->getRowArray();

        if (!$producto) {
            echo json_encode([
                'ok' => false, 
                'error' => 'Producto creado pero no encontrado'
            ]);
            return;
        }

        echo json_encode([
            'ok' => true,
            'producto' => $producto
        ]);
    }

    private function listar()
    {
        header('Content-Type: application/json');
        
        try {
            $db = \Config\Database::connect();
            
            // SE AGREGO: Condición WHERE p.estado = 'Activo' para omitir productos ocultos
            $productos = $db->query(
                "SELECT p.*, c.n_categoria, u.username, u.correo 
                 FROM productos p 
                 LEFT JOIN categorias c ON p.fk_id_categoria = c.id_categoria 
                 LEFT JOIN usuario u ON p.fk_id_usuario = u.id_usuario 
                 WHERE COALESCE(p.estado, 'Activo') = 'Activo'
                 ORDER BY p.id_producto DESC"
            )->getResultArray();
            
            echo json_encode([
                'ok' => true,
                'productos' => $productos
            ]);
        } catch (\Exception $e) {
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    // Filtrar por categoría y búsqueda
    private function filtrar()
    {
        header('Content-Type: application/json');
        
        try {
            $db = \Config\Database::connect();
            
            $categoriaId = $this->request->getGet('categoria') ?? '';
            $busqueda = $this->request->getGet('q') ?? '';
            
            // SE MODIFICÓ: Forzar la consulta inicial a traer solo los que estén con estado = 'Activo'
            $sql = "SELECT p.*, c.n_categoria, u.username, u.correo 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.fk_id_categoria = c.id_categoria 
                    LEFT JOIN usuario u ON p.fk_id_usuario = u.id_usuario 
                    WHERE COALESCE(p.estado, 'Activo') = 'Activo'";
            
            $params = [];
            
            // Filtro por categoría
            if (!empty($categoriaId) && $categoriaId !== 'todos') {
                $sql .= " AND p.fk_id_categoria = ?";
                $params[] = (int)$categoriaId;
            }
            
            // Filtro por búsqueda (nombre o descripción)
            if (!empty($busqueda)) {
                $sql .= " AND (p.nombre_producto LIKE ? OR p.desc_producto LIKE ?)";
                $params[] = '%' . $busqueda . '%';
                $params[] = '%' . $busqueda . '%';
            }
            
            $sql .= " ORDER BY p.id_producto DESC";
            
            $productos = $db->query($sql, $params)->getResultArray();
            
            echo json_encode([
                'ok' => true,
                'productos' => $productos,
                'filtros' => [
                    'categoria' => $categoriaId,
                    'busqueda' => $busqueda
                ]
            ]);
            
        } catch (\Exception $e) {
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    public function cambiarEstado()
    {
        $idProducto = $this->request->getPost('id_producto');
        $nuevoEstado = $this->request->getPost('estado'); // Ej: 'Intercambiado' o 'Inactivo'

        if (!$idProducto || !$nuevoEstado) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Datos incompletos']);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('productos');
        $builder->where('id_producto', $idProducto);
        
        if ($builder->update(['estado' => $nuevoEstado])) {
            return $this->response->setJSON(['ok' => true]);
        } else {
            return $this->response->setJSON(['ok' => false, 'error' => 'No se pudo actualizar el producto']);
        }
    }
}