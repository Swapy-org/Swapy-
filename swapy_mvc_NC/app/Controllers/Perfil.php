<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\ProductoModel;

class Perfil extends BaseController
{
    public function index()
    {
        $session = session();
        $usuarioId = $session->get('id_usuario');

        if (!$session->get('isLoggedIn') || !$usuarioId) {
            return redirect()->to(base_url('?login_error=sesion'));
        }

        $model = new UsuarioModel();
        $usuario = $model->obtenerPorId((int) $usuarioId);

        if (!$usuario) {
            $session->destroy();
            return redirect()->to(base_url('?login_error=sesion'));
        }

        return view('perfil', [
            'usuario' => $usuario,
            'estadisticas' => [
                'calificacion' => 5.0,
                'canjes' => 0,
                'productos' => 0,
            ],
            'resenas' => [],
        ]);
    }

    public function editar()
    {
        $session = session();
        $usuarioId = $session->get('id_usuario');

        if (!$session->get('isLoggedIn') || !$usuarioId) {
            return redirect()->to(base_url('?login_error=sesion'));
        }

        $model = new UsuarioModel();
        $usuario = $model->obtenerPorId((int) $usuarioId);

        if (!$usuario) {
            $session->destroy();
            return redirect()->to(base_url('?login_error=sesion'));
        }

        return view('editarperfil', [
            'usuario' => $usuario,
            'categoriasDisponibles' => (new ProductoModel())->obtenerCategorias(),
            'exito' => $session->getFlashdata('exito'),
            'errores' => $session->getFlashdata('errores'),
        ]);
    }

    public function actualizar()
    {
        $session = session();
        $usuarioId = $session->get('id_usuario');

        if (!$session->get('isLoggedIn') || !$usuarioId) {
            return redirect()->to(base_url('?login_error=sesion'));
        }

        $reglas = [
            'username' => 'required|max_length[50]',
            'telefono' => 'permit_empty|max_length[20]',
            'descripcion' => 'permit_empty|max_length[500]',
            'foto' => 'permit_empty|is_image[foto]|max_size[foto,2048]',
        ];

        $mensajes = [
            'username' => [
                'required' => 'El usuario es obligatorio.',
                'max_length' => 'El usuario no puede superar 50 caracteres.',
            ],
            'telefono' => [
                'max_length' => 'El telefono no puede superar 20 caracteres.',
            ],
            'descripcion' => [
                'max_length' => 'La descripcion no puede superar 500 caracteres.',
            ],
            'foto' => [
                'is_image' => 'La foto debe ser una imagen valida.',
                'max_size' => 'La foto no puede pesar mas de 2 MB.',
            ],
        ];

        if (!$this->validate($reglas, $mensajes)) {
            $session->setFlashdata('errores', $this->validator->getErrors());
            return redirect()->to(base_url('editarperfil'))->withInput();
        }

        $datos = [
            'username' => trim((string) $this->request->getPost('username')),
            'telefono' => trim((string) $this->request->getPost('telefono')),
            'descripcion' => trim((string) $this->request->getPost('descripcion')),
            'categorias' => $this->normalizarCategorias($this->request->getPost('categorias')),
        ];

        if (mb_strlen($datos['categorias']) > 255) {
            $session->setFlashdata('errores', ['Las categorias no pueden superar 255 caracteres.']);
            return redirect()->to(base_url('editarperfil'))->withInput();
        }

        $quitarFoto = $this->request->getPost('quitar_foto') === '1';
        $foto = $this->request->getFile('foto');

        if ($quitarFoto) {
            $usuarioActual = (new UsuarioModel())->obtenerPorId((int) $usuarioId);
            $this->eliminarFotoPerfil($usuarioActual['foto'] ?? null);
            $datos['foto'] = null;
        } elseif ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $directorio = FCPATH . 'assets/IMG/perfiles';

            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }

            $nombreFoto = $foto->getRandomName();
            $foto->move($directorio, $nombreFoto);
            $datos['foto'] = 'perfiles/' . $nombreFoto;
        }

        $model = new UsuarioModel();

        if (!$model->actualizarPerfil((int) $usuarioId, $datos)) {
            $session->setFlashdata('errores', ['No se pudo actualizar el perfil. Intentalo de nuevo.']);
            return redirect()->to(base_url('editarperfil'))->withInput();
        }

        $session->set('username', $datos['username']);
        $session->setFlashdata('exito', 'Perfil actualizado correctamente.');

        return redirect()->to(base_url('perfil'));
    }

    private function normalizarCategorias($categorias): string
    {
        if (!is_array($categorias)) {
            return trim((string) $categorias);
        }

        $limpias = array_filter(array_map(static fn ($categoria) => trim((string) $categoria), $categorias));

        return implode(', ', array_unique($limpias));
    }

    private function eliminarFotoPerfil(?string $foto): void
    {
        if (empty($foto) || !str_starts_with($foto, 'perfiles/')) {
            return;
        }

        $directorio = realpath(FCPATH . 'assets/IMG/perfiles');
        $archivo = realpath(FCPATH . 'assets/IMG/' . $foto);

        if ($directorio && $archivo && str_starts_with($archivo, $directorio) && is_file($archivo)) {
            unlink($archivo);
        }
    }
}
