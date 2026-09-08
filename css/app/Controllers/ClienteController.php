<?php

namespace App\Controllers;

class ClienteController extends BaseController
{
    public function index()
    {
        $session = \Config\Services::session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('?login_error=acceso'));
        }

        $data['usuario'] = [
            'nombre' => $session->get('username') ?? $session->get('correo'),
            'correo' => $session->get('correo'),
            'rol'    => $session->get('rol')
        ];

        return view('cliente', $data);
    }
}