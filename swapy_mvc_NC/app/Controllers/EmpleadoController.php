<?php

namespace App\Controllers;

class EmpleadoController extends BaseController
{
    // 1. Muestra el formulario de Login para el empleado
    public function index()
    {
        return view('spempleados'); 
    }

    // 2. Procesa el inicio de sesión soportando hash y texto plano por si se crea desde el admin
    public function login()
    {
        $session = \Config\Services::session();
        $correo = $this->request->getPost('correo');
        $password = $this->request->getPost('password');

        $db = \Config\Database::connect();
        
        // Buscamos directamente en la tabla 'empleado'
        $emp = $db->query("SELECT * FROM empleado WHERE correo = ? LIMIT 1", [$correo])->getRowArray();

        // 1. Validar si el correo existe
        if (!$emp) {
            return redirect()->to('empleado')->with('error', 'El correo no existe en la tabla empleado.');
        }

        // 2. Validar contraseña: Comprueba si es un hash válido Y coincide, O si está en texto plano
        $passwordValida = false;
        
        // Verificamos si la contraseña en BD parece un hash de PHP y pasa la verificación
        if (password_get_info($emp['contrasena'])['algo'] !== 0) {
            if (password_verify($password, $emp['contrasena'])) {
                $passwordValida = true;
            }
        } 
        // Si no es un hash (o falló el hash), comparamos por texto plano por si el admin la guardó así
        if (!$passwordValida && $password === $emp['contrasena']) {
            $passwordValida = true;
        }

        if (!$passwordValida) {
            return redirect()->to('empleado')->with('error', 'La contraseña es incorrecta.');
        }

        // Si todo está bien, buscamos los datos personales vinculados
        $persona = $db->query("SELECT * FROM persona WHERE fkpk_id_doc = ? AND documento = ? LIMIT 1", [$emp['pkfk_id_doc'], $emp['id_empleado']])->getRowArray();

        $session->set([
            'isLoggedIn'  => true,
            'id_empleado' => $emp['id_empleado'],
            'id_doc'      => $emp['pkfk_id_doc'],
            'correo'      => $emp['correo'],
            'nombre'      => $persona ? $persona['primer_nombre'] . ' ' . $persona['primer_apellido'] : 'Empleado',
            'rol'         => 'Empleado'
        ]);
        
        return redirect()->to('empleado/dashboard');
    }

    // 3. Muestra el Panel del Empleado (Dashboard) con tus tablas REALES de Swapy
    public function dashboard()
    {
        $session = \Config\Services::session();
        
        // Protección de ruta: si no está logueado o no es empleado, va para afuera
        if (!$session->get('isLoggedIn') || $session->get('rol') !== 'Empleado') {
            return redirect()->to('empleado')->with('error', 'Debes iniciar sesión primero.');
        }

        $db = \Config\Database::connect();
        $id_empleado = $session->get('id_empleado');
        $id_doc = $session->get('id_doc');

        // 1. Obtener información detallada del perfil del Empleado (unión entre empleado y persona)
        $data['empleado'] = $db->query("
            SELECT e.*, p.primer_nombre, p.segundo_nombre, p.primer_apellido, p.segundo_apellido, p.documento 
            FROM empleado e 
            JOIN persona p ON e.pkfk_id_doc = p.fkpk_id_doc AND e.id_empleado = p.documento 
            WHERE e.id_empleado = ? AND e.pkfk_id_doc = ?
        ", [$id_empleado, $id_doc])->getRowArray();

        // 2. Estadísticas para las tarjetas del Dashboard (Adaptadas a tus tablas)
        $data['stats'] = [
            'intercambios_hoy'     => ($row = $db->query("SELECT COUNT(*) as total FROM intercambio")->getRow()) ? $row->total : 0,
            'preguntas_pendientes' => ($row = $db->query("SELECT COUNT(*) as total FROM usuario_preguntas WHERE estado = 'pendiente'")->getRow()) ? $row->total : 0,
            'denuncias_activas'    => ($row = $db->query("SELECT COUNT(*) as total FROM usuario_denuncias WHERE estado = 'pendiente'")->getRow()) ? $row->total : 0,
            'productos_totales'    => ($row = $db->query("SELECT COUNT(*) as total FROM productos")->getRow()) ? $row->total : 0
        ];

        // 3. Historial de Intercambios (Para la tabla de Gestión del panel)
        $data['intercambios'] = $db->query("SELECT * FROM intercambio ORDER BY id_intercambio DESC LIMIT 10")->getResultArray();

        // 4. Preguntas de usuarios pendientes por responder
        $data['solicitudes'] = $db->query("SELECT * FROM usuario_preguntas WHERE estado = 'pendiente' ORDER BY fecha_creacion ASC LIMIT 10")->getResultArray();

        // 5. Denuncias Activas de usuarios para el centro de control
        $data['denuncias'] = $db->query("SELECT * FROM usuario_denuncias WHERE estado = 'pendiente' ORDER BY fecha_creacion DESC LIMIT 10")->getResultArray();

        // 6. Solicitudes de entrega pendientes (tabla intercambios, formulario del chat)
        $data['solicitudes_entrega'] = $db->query("
            SELECT i.*,
                   p.nombre_producto,
                   us.correo AS correo_solicitante,
                   up.correo AS correo_propietario
            FROM intercambios i
            LEFT JOIN productos p ON p.id_producto = i.id_producto
            LEFT JOIN usuario us ON us.id_usuario = i.id_solicitante
            LEFT JOIN usuario up ON up.id_usuario = i.id_propietario
            WHERE i.estado = 'pendiente'
            ORDER BY i.creado_en DESC
        ")->getResultArray();

        // Retornamos el panel pasándole el array de datos mapeados
        return view('empleado_panel', $data); 
    }

    // 4. Cierre de sesión seguro
    public function logout()
    {
        $session = \Config\Services::session();
        $session->destroy();
        return redirect()->to('empleado');
    }

    // 5. Cambiar estado de usuario vía AJAX
    public function cambiarEstado()
    {
        $idUsuario = $this->request->getPost('id_usuario');
        $nuevoEstado = $this->request->getPost('estado'); // Recibe 'Activo' o 'Inactivo'

        if (!$idUsuario || !$nuevoEstado) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Datos incompletos']);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('usuario');
        $builder->where('id_usuario', $idUsuario);
        
        if ($builder->update(['estado' => $nuevoEstado])) {
            return $this->response->setJSON(['ok' => true]);
        } else {
            return $this->response->setJSON(['ok' => false, 'error' => 'No se pudo actualizar en la base de datos']);
        }
    }

    // 6. Aceptar o rechazar una solicitud de entrega (tabla intercambios)
    public function actualizarEstadoIntercambio()
    {
        $session = \Config\Services::session();

        if (!$session->get('isLoggedIn') || $session->get('rol') !== 'Empleado') {
            return $this->response->setStatusCode(401)->setJSON([
                'ok' => false,
                'error' => 'No autorizado.'
            ]);
        }

        $idIntercambio = (int) $this->request->getPost('id_intercambio');
        $accion = trim((string) $this->request->getPost('accion'));

        $estadosValidos = [
            'aceptado'  => 'aceptado',
            'rechazado' => 'rechazado'
        ];

        if ($idIntercambio <= 0 || !isset($estadosValidos[$accion])) {
            return $this->response->setJSON([
                'ok' => false,
                'error' => 'Datos inválidos.'
            ]);
        }

        $db = \Config\Database::connect();

        $ok = $db->table('intercambios')
            ->where('id_intercambio', $idIntercambio)
            ->update(['estado' => $estadosValidos[$accion]]);

        if (!$ok) {
            return $this->response->setJSON([
                'ok' => false,
                'error' => 'No se pudo actualizar la solicitud.'
            ]);
        }

        return $this->response->setJSON([
            'ok' => true,
            'estado' => $estadosValidos[$accion]
        ]);
    }
}