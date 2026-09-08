<?php

namespace App\Controllers;

use App\Models\ProductoModel;

class IntercambioController extends BaseController
{
    public function confirmar()
    {
        $session = session();
        $usuarioId = (int) $session->get('id_usuario');
        $productoId = (int) $this->request->getPost('id_producto');

        if (!$session->get('isLoggedIn') || $usuarioId <= 0 || $productoId <= 0) {
            return $this->response->setStatusCode(401)->setJSON([
                'ok' => false,
                'error' => 'Debes iniciar sesión y seleccionar un producto.'
            ]);
        }

        $producto = (new ProductoModel())->find($productoId);
        $propietarioId = (int) ($producto['fk_id_usuario'] ?? 0);

        if (!$producto || $propietarioId <= 0 || $propietarioId === $usuarioId) {
            return $this->response->setStatusCode(400)->setJSON([
                'ok' => false,
                'error' => 'No puedes iniciar un intercambio contigo mismo.'
            ]);
        }

        $db = \Config\Database::connect();
        $tabla = $db->table('intercambios');
        $intercambio = $tabla
            ->where('id_producto', $productoId)
            ->groupStart()
                ->where('id_solicitante', $usuarioId)
                ->where('id_propietario', $propietarioId)
            ->groupEnd()
            ->orGroupStart()
                ->where('id_solicitante', $propietarioId)
                ->where('id_propietario', $usuarioId)
            ->groupEnd()
            ->orderBy('id_intercambio', 'DESC')
            ->get()
            ->getFirstRow('array');

        if (!$intercambio) {
            $tabla->insert([
                'id_producto' => $productoId,
                'id_solicitante' => $usuarioId,
                'id_propietario' => $propietarioId,
                'confirmado_solicitante' => 1,
                'direccion' => '',
                'descripcion_lugar' => '',
                'descripcion_producto' => '',
                'estado' => 'pendiente'
            ]);

            $idIntercambio = $db->insertID();

            return $this->response->setJSON([
                'ok' => true,
                'confirmados' => false,
                'id_intercambio' => $idIntercambio,
                'mensaje' => 'Solicitud enviada. Esperando confirmación del otro usuario.'
            ]);
        }

        $esSolicitante = (int) $intercambio['id_solicitante'] === $usuarioId;
        $campo = $esSolicitante ? 'confirmado_solicitante' : 'confirmado_propietario';
        $tabla->where('id_intercambio', $intercambio['id_intercambio'])->update([
            $campo => 1,
            'estado' => ((int) $intercambio['confirmado_solicitante'] === 1 || $esSolicitante)
                && ((int) $intercambio['confirmado_propietario'] === 1 || !$esSolicitante)
                ? 'confirmado'
                : 'pendiente'
        ]);

        $confirmados = ((int) $intercambio['confirmado_solicitante'] === 1 || $esSolicitante)
            && ((int) $intercambio['confirmado_propietario'] === 1 || !$esSolicitante);

        return $this->response->setJSON([
            'ok' => true,
            'confirmados' => $confirmados,
            'id_intercambio' => (int) $intercambio['id_intercambio'],
            'mensaje' => $confirmados
                ? 'Ambos usuarios confirmaron el intercambio.'
                : 'Confirmación registrada. Esperando al otro usuario.'
        ]);
    }

    public function completar()
    {
        $usuarioId = (int) session()->get('id_usuario');
        $intercambioId = (int) $this->request->getPost('id_intercambio');

        if ($usuarioId <= 0 || $intercambioId <= 0) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'error' => 'Solicitud no válida.']);
        }

        $datos = [
            'direccion' => trim((string) $this->request->getPost('direccion')),
            'descripcion_lugar' => trim((string) $this->request->getPost('descripcion_lugar')),
            'descripcion_producto' => trim((string) $this->request->getPost('descripcion_producto')),
        ];

        if (in_array('', $datos, true)) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'error' => 'Completa todos los datos del intercambio.']);
        }

        $db = \Config\Database::connect();
        $intercambio = $db->table('intercambios')
            ->where('id_intercambio', $intercambioId)
            ->groupStart()
                ->where('id_solicitante', $usuarioId)
                ->orWhere('id_propietario', $usuarioId)
            ->groupEnd()
            ->get()
            ->getFirstRow('array');

        if (!$intercambio) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'error' => 'Intercambio no encontrado.']);
        }

        $db->table('intercambios')->where('id_intercambio', $intercambioId)->update($datos);

        return $this->response->setJSON(['ok' => true, 'mensaje' => 'Datos del intercambio guardados correctamente.']);
    }

    public function estado(int $idIntercambio)
    {
        $usuarioId = (int) session()->get('id_usuario');
        $db = \Config\Database::connect();
        $intercambio = $db->table('intercambios')
            ->where('id_intercambio', $idIntercambio)
            ->groupStart()
                ->where('id_solicitante', $usuarioId)
                ->orWhere('id_propietario', $usuarioId)
            ->groupEnd()
            ->get()
            ->getFirstRow('array');

        if (!$intercambio) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'error' => 'Intercambio no encontrado.']);
        }

        return $this->response->setJSON([
            'ok' => true,
            'confirmados' => $intercambio['estado'] === 'confirmado',
            'estado' => $intercambio['estado']
        ]);
    }
}