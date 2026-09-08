<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class PerfilApi extends BaseController
{
    public function mostrar($id)
    {
        $model = new UsuarioModel();
        $usuario = $model->obtenerPorId((int) $id);

        if (!$usuario) {
            return $this->response->setStatusCode(404)->setJSON([
                'ok' => false,
                'mensaje' => 'Usuario no encontrado.',
            ]);
        }

        unset($usuario['contraseña']);

        return $this->response->setJSON([
            'ok' => true,
            'usuario' => $usuario,
        ]);
    }

    public function actualizar($id)
    {
        $model = new UsuarioModel();
        $usuario = $model->obtenerPorId((int) $id);

        if (!$usuario) {
            return $this->response->setStatusCode(404)->setJSON([
                'ok' => false,
                'mensaje' => 'Usuario no encontrado.',
            ]);
        }

        $payload = $this->request->getJSON(true);

        if (!is_array($payload)) {
            $payload = $this->request->getPost();
        }

        if (empty($payload) && in_array($this->request->getMethod(), ['put', 'patch'], true)) {
            $payload = $this->request->getRawInput();
        }

        $datos = [];
        $errores = [];

        if (array_key_exists('username', $payload)) {
            $username = trim((string) $payload['username']);

            if ($username === '') {
                $errores['username'] = 'El usuario es obligatorio.';
            } elseif (mb_strlen($username) > 50) {
                $errores['username'] = 'El usuario no puede superar 50 caracteres.';
            } else {
                $datos['username'] = $username;
            }
        }

        if (!array_key_exists('username', $datos) && array_key_exists('nombre', $payload)) {
            $nombre = trim((string) $payload['nombre']);

            if ($nombre === '') {
                $errores['nombre'] = 'El nombre es obligatorio.';
            } elseif (mb_strlen($nombre) > 50) {
                $errores['nombre'] = 'El nombre no puede superar 50 caracteres.';
            } else {
                $datos['username'] = $nombre;
            }
        }

        if (array_key_exists('telefono', $payload)) {
            $telefono = trim((string) $payload['telefono']);

            if (mb_strlen($telefono) > 20) {
                $errores['telefono'] = 'El telefono no puede superar 20 caracteres.';
            } else {
                $datos['telefono'] = $telefono;
            }
        }

        if (array_key_exists('descripcion', $payload)) {
            $descripcion = trim((string) $payload['descripcion']);

            if (mb_strlen($descripcion) > 500) {
                $errores['descripcion'] = 'La descripcion no puede superar 500 caracteres.';
            } else {
                $datos['descripcion'] = $descripcion;
            }
        }

        if (array_key_exists('categorias', $payload)) {
            $categorias = $this->normalizarCategorias($payload['categorias']);

            if (mb_strlen($categorias) > 255) {
                $errores['categorias'] = 'Las categorias no pueden superar 255 caracteres.';
            } else {
                $datos['categorias'] = $categorias;
            }
        }

        if (array_key_exists('foto', $payload)) {
            $foto = trim((string) $payload['foto']);

            if (mb_strlen($foto) > 255) {
                $errores['foto'] = 'La ruta de la foto no puede superar 255 caracteres.';
            } else {
                $datos['foto'] = $foto;
            }
        }

        if (!empty($errores)) {
            return $this->response->setStatusCode(422)->setJSON([
                'ok' => false,
                'errores' => $errores,
            ]);
        }

        if (empty($datos)) {
            return $this->response->setStatusCode(400)->setJSON([
                'ok' => false,
                'mensaje' => 'No enviaste datos para actualizar.',
            ]);
        }

        if (!$model->actualizarPerfil((int) $id, $datos)) {
            return $this->response->setStatusCode(500)->setJSON([
                'ok' => false,
                'mensaje' => 'No se pudo actualizar el perfil.',
            ]);
        }

        return $this->response->setJSON([
            'ok' => true,
            'mensaje' => 'Perfil actualizado correctamente.',
            'datos_actualizados' => $datos,
        ]);
    }

    private function normalizarCategorias($categorias): string
    {
        if (!is_array($categorias)) {
            return trim((string) $categorias);
        }

        $limpias = array_filter(array_map(static fn ($categoria) => trim((string) $categoria), $categorias));

        return implode(', ', array_unique($limpias));
    }
}
