<?php

namespace App\Controllers;

use App\Models\ProductoModel;

class ConversacionController extends BaseController
{
    public function abrir()
    {
        $usuarioId = (int) session()->get('id_usuario');
        $productoId = (int) $this->request->getPost('id_producto');

        if ($usuarioId <= 0 || $productoId <= 0) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'error' => 'Datos de conversación inválidos.']);
        }

        $producto = (new ProductoModel())->find($productoId);
        $destinatarioId = (int) ($producto['fk_id_usuario'] ?? 0);

        if (!$producto || $destinatarioId <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false, 'error' => 'El producto no tiene un destinatario válido.']);
        }

        $db = \Config\Database::connect();
        $tabla = $db->table('conversaciones');
        if ($destinatarioId === $usuarioId) {
            $conversacion = $tabla
                ->where('id_producto', $productoId)
                ->where('id_destinatario', $usuarioId)
                ->orderBy('actualizado_en', 'DESC')
                ->get()
                ->getFirstRow('array');

            if (!$conversacion) {
                return $this->response->setStatusCode(400)->setJSON(['ok' => false, 'error' => 'Todavía no hay una conversación para este producto.']);
            }
        } else {
            $conversacion = $tabla
                ->where('id_producto', $productoId)
                ->where('id_iniciador', $usuarioId)
                ->where('id_destinatario', $destinatarioId)
                ->get()
                ->getFirstRow('array');
        }

        if (!$conversacion) {
            $tabla->insert([
                'id_producto' => $productoId,
                'id_iniciador' => $usuarioId,
                'id_destinatario' => $destinatarioId,
                'ultimo_mensaje' => null,
                'actualizado_en' => date('Y-m-d H:i:s')
            ]);
            $conversacion = $tabla->where('id_conversacion', $db->insertID())->get()->getFirstRow('array');
        }

        return $this->response->setJSON(['ok' => true, 'conversacion' => $conversacion]);
    }

    public function listar()
    {
        $usuarioId = (int) session()->get('id_usuario');

        if ($usuarioId <= 0) {
            return $this->response->setStatusCode(401)->setJSON([
                'ok' => false,
                'error' => 'Debes iniciar sesión.'
            ]);
        }

        try {

            $db = \Config\Database::connect();

            $conversaciones = $db->table('conversaciones c')
                ->select('c.*, p.nombre_producto, p.imagen, u.username, u.correo')
                ->join('productos p', 'p.id_producto = c.id_producto')
                ->join('usuario u', 'u.id_usuario = c.id_destinatario')
                ->groupStart()
                    ->where('c.id_iniciador', $usuarioId)
                    ->orWhere('c.id_destinatario', $usuarioId)
                ->groupEnd()
                ->orderBy('c.actualizado_en', 'DESC')
                ->get()
                ->getResultArray();

            return $this->response->setJSON([
                'ok' => true,
                'conversaciones' => $conversaciones
            ]);

        } catch (\Throwable $e) {

            log_message('error', 'ConversacionController::listar - ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'ok' => false,
                'error' => 'No se pudieron cargar las conversaciones: ' . $e->getMessage()
            ]);
        }
    }

    public function mensajes(int $idConversacion)
    {
        $usuarioId = (int) session()->get('id_usuario');

        if ($usuarioId <= 0) {
            return $this->response->setStatusCode(401)->setJSON([
                'ok' => false,
                'error' => 'Debes iniciar sesión.'
            ]);
        }

        try {

            $db = \Config\Database::connect();

            $conversacion = $db->table('conversaciones')
                ->where('id_conversacion', $idConversacion)
                ->groupStart()
                    ->where('id_iniciador', $usuarioId)
                    ->orWhere('id_destinatario', $usuarioId)
                ->groupEnd()
                ->get()
                ->getFirstRow('array');

            if (!$conversacion) {
                return $this->response->setStatusCode(404)->setJSON([
                    'ok' => false,
                    'error' => 'Conversación no encontrada.'
                ]);
            }

            $mensajes = $db->table('mensajes_chat')
                ->where('id_conversacion', $idConversacion)
                ->orderBy('creado_en', 'ASC')
                ->get()
                ->getResultArray();

            return $this->response->setJSON([
                'ok' => true,
                'conversacion' => $conversacion,
                'mensajes' => $mensajes
            ]);

        } catch (\Throwable $e) {

            log_message('error', 'ConversacionController::mensajes - ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'ok' => false,
                'error' => 'No se pudieron cargar los mensajes: ' . $e->getMessage()
            ]);
        }
    }

    public static function guardarMensaje(int $idConversacion, int $usuarioId, string $texto, string $tipo = 'usuario'): void
    {
        $db = \Config\Database::connect();
        $db->table('mensajes_chat')->insert([
            'id_conversacion' => $idConversacion,
            'id_usuario' => $usuarioId,
            'tipo' => $tipo,
            'contenido' => $texto,
            'creado_en' => date('Y-m-d H:i:s')
        ]);
        $db->table('conversaciones')->where('id_conversacion', $idConversacion)->update([
            'ultimo_mensaje' => $texto,
            'actualizado_en' => date('Y-m-d H:i:s')
        ]);
    }

    public function finalizar()
    {
        $usuarioId = (int) session()->get('id_usuario');
        $productoId = (int) $this->request->getPost('id_producto');

        if ($usuarioId <= 0 || $productoId <= 0) {
            return $this->response->setStatusCode(401)->setJSON([
                'ok' => false,
                'error' => 'No se pudo identificar el intercambio.'
            ]);
        }

        $db = \Config\Database::connect();
        $conversaciones = $db->table('conversaciones')
            ->select('id_conversacion')
            ->where('id_producto', $productoId)
            ->groupStart()
                ->where('id_iniciador', $usuarioId)
                ->orWhere('id_destinatario', $usuarioId)
            ->groupEnd()
            ->get()
            ->getResultArray();

        if (!$conversaciones) {
            return $this->response->setStatusCode(404)->setJSON([
                'ok' => false,
                'error' => 'No se encontró la conversación del producto.'
            ]);
        }

        $db->transBegin();
        try {
            $db->table('conversaciones')->where('id_producto', $productoId)->delete();
            $db->table('productos')->where('id_producto', $productoId)->delete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException('No se pudo eliminar el producto.');
            }

            $db->transCommit();

            return $this->response->setJSON([
                'ok' => true,
                'mensaje' => 'Intercambio finalizado. El producto y sus conversaciones fueron retirados.'
            ]);
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'ConversacionController::finalizar - ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'ok' => false,
                'error' => 'No se pudo finalizar el intercambio.'
            ]);
        }
    }

    public function solicitudEntrega()
    {
        $usuarioId = (int) session()->get('id_usuario');
        $productoId = (int) $this->request->getPost('id_producto');

        $direccion = trim((string) $this->request->getPost('direccion'));
        $descripcionLugar = trim((string) $this->request->getPost('descripcion_lugar'));
        $descripcionProducto = trim((string) $this->request->getPost('descripcion_producto'));

        if ($usuarioId <= 0 || $productoId <= 0) {
            return $this->response->setStatusCode(401)->setJSON([
                'ok' => false,
                'error' => 'Debes iniciar sesión y seleccionar un producto.'
            ]);
        }

        if ($direccion === '' || $descripcionLugar === '' || $descripcionProducto === '') {
            return $this->response->setStatusCode(422)->setJSON([
                'ok' => false,
                'error' => 'Completa todos los datos de la solicitud.'
            ]);
        }

        $producto = (new ProductoModel())->find($productoId);
        $propietarioId = (int) ($producto['fk_id_usuario'] ?? 0);

        if (!$producto || $propietarioId <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'ok' => false,
                'error' => 'El producto no tiene un propietario válido.'
            ]);
        }

        $db = \Config\Database::connect();

        try {

            $db->table('intercambios')->insert([
                'id_producto'            => $productoId,
                'id_solicitante'         => $usuarioId,
                'id_propietario'         => $propietarioId,
                'confirmado_solicitante' => 1,
                'confirmado_propietario' => 0,
                'direccion'              => $direccion,
                'descripcion_lugar'      => $descripcionLugar,
                'descripcion_producto'   => $descripcionProducto,
                'estado'                 => 'pendiente'
            ]);

            return $this->response->setJSON([
                'ok' => true,
                'id_intercambio' => $db->insertID(),
                'mensaje' => 'Solicitud enviada correctamente.'
            ]);

        } catch (\Throwable $e) {

            log_message('error', 'ConversacionController::solicitudEntrega - ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'ok' => false,
                'error' => 'No se pudo registrar la solicitud.'
            ]);
        }
    }
}