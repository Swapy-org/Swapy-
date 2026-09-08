<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SoporteModel;
use App\Models\SolicitudModel; // <-- IMPORTANTE: Cargamos el modelo de las solicitudes

class SoporteController extends BaseController
{
    public function enviar()
    {
        $session = \Config\Services::session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('?login_error=acceso'));
        }

        if ($this->request->getMethod() !== 'post') {
            return redirect()->back();
        }

        $model = new SoporteModel();
        $idUsuario = $session->get('id_usuario');

        log_message('error', 'SOPORTE DEBUG - llego al controlador. id_usuario: ' . var_export($idUsuario, true));
        log_message('error', 'SOPORTE DEBUG - POST data: ' . json_encode($this->request->getPost()));

        $tipoSoporte = $this->request->getPost('tipo_soporte');

        if ($tipoSoporte === 'pregunta') {
            $pregunta = trim((string) $this->request->getPost('faq_pregunta'));

            if (empty($pregunta) || empty($idUsuario)) {
                log_message('error', 'SOPORTE DEBUG - bloqueado por campos vacios. pregunta="' . $pregunta . '" idUsuario=' . var_export($idUsuario, true));
                return redirect()->back()->with('soporte_error', 'campos');
            }

            $ok = $model->crearPregunta($idUsuario, $pregunta);
            log_message('error', 'SOPORTE DEBUG - resultado insert pregunta: ' . var_export($ok, true));

            if (!$ok) {
                $dbErr = \Config\Database::connect()->error();
                log_message('error', 'SOPORTE DEBUG - error de base de datos: ' . json_encode($dbErr));
                return redirect()->back()->with('soporte_error', 'db');
            }

            return redirect()->back()->with('soporte_msg', 'pregunta_enviada');
        }

        if ($tipoSoporte === 'denuncia') {
            $tipo = trim((string) $this->request->getPost('report_tipo'));
            $descripcion = trim((string) $this->request->getPost('report_descripcion'));

            if (empty($tipo) || empty($descripcion) || empty($idUsuario)) {
                log_message('error', 'SOPORTE DEBUG - bloqueado por campos vacios en denuncia.');
                return redirect()->back()->with('soporte_error', 'campos');
            }

            $ok = $model->crearDenuncia($idUsuario, $tipo, $descripcion);
            log_message('error', 'SOPORTE DEBUG - resultado insert denuncia: ' . var_export($ok, true));

            if (!$ok) {
                $dbErr = \Config\Database::connect()->error();
                log_message('error', 'SOPORTE DEBUG - error de base de datos: ' . json_encode($dbErr));
                return redirect()->back()->with('soporte_error', 'db');
            }

            return redirect()->back()->with('soporte_msg', 'denuncia_enviada');
        }

        log_message('error', 'SOPORTE DEBUG - tipo_soporte no coincide: "' . $tipoSoporte . '"');
        return redirect()->back()->with('soporte_error', 'tipo');
    }

    // =========================================================================
    // NUEVO MÉTODO 1: Guarda la propuesta del Chat (Petición AJAX / Fetch)
    // =========================================================================
 // =========================================================================
    // NUEVO MÉTODO 1: Guarda la propuesta del Chat (Petición AJAX / Fetch)
    // =========================================================================
    // =========================================================================
    // NUEVO MÉTODO 1: Guarda la propuesta del Chat (Petición AJAX / Fetch)
    // =========================================================================
  // =========================================================================
    // NUEVO MÉTODO 1: Guarda la propuesta del Chat (Inserción Directa)
    // =========================================================================
    public function enviarPropuesta()
    {
        // 1. Forzar captura directa de la conexión de base de datos
        $db = \Config\Database::connect();

        $productoOfertado = $this->request->getPost('producto_ofertado');
        $productoOfrecido = $this->request->getPost('producto_ofrecido');

        if (empty($productoOfertado) || empty($productoOfrecido)) {
            return $this->response->setStatusCode(400)->setJSON([
                'ok'    => false, 
                'error' => 'Los datos llegaron VACÍOS.'
            ]);
        }

        $data = [
            'producto_ofertado' => trim((string)$productoOfertado),
            'producto_ofrecido' => trim((string)$productoOfrecido),
            'estado'            => 'Pendiente'
        ];

        // 2. Inserción directa sin pasar por las reglas del archivo Model
        try {
            $builder = $db->table('solicitudes_entrega');
            
            if ($builder->insert($data)) {
                return $this->response->setJSON(['ok' => true]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'ok'    => false, 
                    'error' => 'Error interno al intentar insertar la fila.'
                ]);
            }
        } catch (\Throwable $e) {
            // Esto nos va a pintar el error real de MySQL en el alert de la pantalla
            return $this->response->setStatusCode(200)->setJSON([
                'ok'    => false, 
                'error' => 'MySQL dice: ' . $e->getMessage()
            ]);
        }
    }

    // =========================================================================
    // NUEVO MÉTODO 2: Cambia el estado desde el panel del Empleado
    // =========================================================================
    public function actualizarEstadoSolicitud()
    {
        header('Content-Type: application/json');
        $model = new SolicitudModel();

        $idSolicitud = $this->request->getPost('id_solicitud');
        $nuevoEstado = $this->request->getPost('estado');

        if ($model->update($idSolicitud, ['estado' => $nuevoEstado])) {
            return $this->response->setJSON(['ok' => true]);
        } else {
            return $this->response->setJSON(['ok' => false, 'error' => 'No se pudo actualizar el estado.']);
        }
    }
}