<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

require_once ROOTPATH . 'PHPMailer/src/PHPMailer.php';
require_once ROOTPATH . 'PHPMailer/src/SMTP.php';
require_once ROOTPATH . 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class AuthController extends BaseController
{
    public function index()
    {
        $accion = $this->request->getGet('accion')
            ?? $this->request->getPost('accion')
            ?? '';

        return match ($accion) {
            'login'     => $this->login(),
            'logout'    => $this->logout(),
            'registro'  => $this->registrar(),
            'verificar' => $this->verificarCodigo(),
            default     => redirect()->to(base_url()),
        };
    }


    // =========================================================
    // LOGIN
    // =========================================================
    public function login()
    {
        $session = session();

        $correo = trim(
            (string) $this->request->getPost('correo')
        );

        $password = (string) $this->request->getPost('contrasena');


        // -----------------------------------------------------
        // 1. Validar campos
        // -----------------------------------------------------
        if ($correo === '' || $password === '') {
            return redirect()->to(base_url('?login_error=campos'));
        }


        // -----------------------------------------------------
        // 2. Validar correo
        // -----------------------------------------------------
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return redirect()->to(base_url('?login_error=correo'));
        }


        // -----------------------------------------------------
        // 3. Buscar usuario
        // -----------------------------------------------------
        $model = new UsuarioModel();

        $user = $model->buscarPorCorreo($correo);

        if (!$user) {
            return redirect()->to(base_url('?login_error=invalido'));
        }


        // -----------------------------------------------------
        // 4. Obtener contraseña hasheada
        // -----------------------------------------------------
        $hash = (string) ($user['contrasena'] ?? '');

        if ($hash === '') {
            return redirect()->to(base_url('?login_error=invalido'));
        }


        // -----------------------------------------------------
        // 5. Verificar contraseña
        // -----------------------------------------------------
        if (!password_verify($password, $hash)) {
            return redirect()->to(base_url('?login_error=invalido'));
        }


        // -----------------------------------------------------
        // 6. Verificar estado
        // -----------------------------------------------------
        $estado = strtolower(
            trim((string) ($user['estado'] ?? ''))
        );

        if ($estado !== 'activo') {
            return redirect()->to(base_url('?login_error=inactivo'));
        }


        // -----------------------------------------------------
        // 7. Obtener rol
        // -----------------------------------------------------
        $rol = strtolower(
            trim((string) ($user['rol'] ?? 'cliente'))
        );


        // -----------------------------------------------------
        // 8. Crear sesión
        // -----------------------------------------------------
        $session->set([
            'isLoggedIn' => true,
            'id_usuario' => $user['id_usuario'],
            'username'   => $user['username'] ?? '',
            'correo'     => $user['correo'],
            'rol'        => $user['rol'] ?? 'Cliente',
            'estado'     => $user['estado'] ?? 'Activo',
        ]);


        // -----------------------------------------------------
        // 9. Redireccionar según rol
        // -----------------------------------------------------
        if ($rol === 'administrador') {
            return redirect()->to(base_url('admin'));
        }

        if ($rol === 'empleado') {
            return redirect()->to(base_url('empleado'));
        }

        return redirect()->to(base_url('index2'));
    }


    // =========================================================
    // LOGOUT
    // =========================================================
    public function logout()
    {
        session()->destroy();

        return redirect()->to(base_url());
    }


    // =========================================================
    // REGISTRO
    // =========================================================
    public function registrar()
    {
        $this->response->setContentType('application/json');

        try {

            $model = new UsuarioModel();

            $username = trim(
                (string) $this->request->getPost('username')
            );

            $telefono = trim(
                (string) $this->request->getPost('telefono')
            );

            $correo = trim(
                (string) $this->request->getPost('correo')
            );

            $password = (string)
                $this->request->getPost('contrasena');

            $documento = (int)
                $this->request->getPost('documento');

            $numeroDocumento = trim(
                (string) $this->request->getPost('numero_documento')
            );

            $rol = trim(
                (string) (
                    $this->request->getPost('rol')
                    ?: 'Cliente'
                )
            );

            if (!in_array($rol, ['Administrador', 'Empleado', 'Cliente'], true)) {
                return $this->response->setJSON([
                    'ok' => false,
                    'error' => 'El rol seleccionado no es válido.'
                ]);
            }


            // -------------------------------------------------
            // Validar campos
            // -------------------------------------------------
            if (
                $username === '' ||
                $correo === '' ||
                $password === '' ||
                $documento <= 0 ||
                $numeroDocumento === ''
            ) {
                return $this->response->setJSON([
                    'ok' => false,
                    'error' =>
                        'Todos los campos obligatorios deben estar completos.'
                ]);
            }


            // -------------------------------------------------
            // Validar correo
            // -------------------------------------------------
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                return $this->response->setJSON([
                    'ok' => false,
                    'error' => 'Correo no valido.'
                ]);
            }


            // -------------------------------------------------
            // Validar contraseña
            // -------------------------------------------------
            if (strlen($password) < 6) {
                return $this->response->setJSON([
                    'ok' => false,
                    'error' =>
                        'La contrasena debe tener al menos 6 caracteres.'
                ]);
            }


            // -------------------------------------------------
            // Verificar correo existente
            // -------------------------------------------------
            if ($model->correoExiste($correo)) {
                return $this->response->setJSON([
                    'ok' => false,
                    'error' => 'Este correo ya esta registrado.'
                ]);
            }


            // -------------------------------------------------
            // Registrar
            // -------------------------------------------------
            $registrado = $model->registrar(
                $documento,
                $numeroDocumento,
                $correo,
                $password,
                $rol,
                $username,
                $telefono
            );

            if (!$registrado) {
                return $this->response->setJSON([
                    'ok' => false,
                    'error' => 'No se pudo registrar el usuario.'
                ]);
            }


            // -------------------------------------------------
            // Buscar usuario
            // -------------------------------------------------
            $usuario = $model->buscarPorCorreo($correo);

            $codigo = $usuario['codigo_verificacion'] ?? '';


            // -------------------------------------------------
            // Enviar código
            // -------------------------------------------------
            $enviado = (
                $codigo !== ''
                &&
                $this->enviarEmailOTP(
                    $correo,
                    $username,
                    $codigo
                )
            );


            session()->set(
                'email_verificacion',
                $correo
            );


            return $this->response->setJSON([
                'ok' => true,
                'correo' => $correo,
                'email_enviado' => $enviado,
                'codigo_debug' =>
                    ENVIRONMENT === 'production'
                        ? null
                        : $codigo,
            ]);


        } catch (\Throwable $e) {

            log_message(
                'error',
                'Error en registrar: ' . $e->getMessage()
            );

            return $this->response->setJSON([
                'ok' => false,
                'error' =>
                    'Error del servidor: ' . $e->getMessage()
            ]);
        }
    }


    // =========================================================
    // VERIFICAR CÓDIGO
    // =========================================================
    public function verificarCodigo()
    {
        $this->response->setContentType('application/json');

        try {

            $model = new UsuarioModel();

            $correo = (string) (
                session()->get('email_verificacion')
                ?: $this->request->getPost('correo')
            );

            $codigo = trim(
                (string) $this->request->getPost('codigo')
            );


            if ($correo === '') {
                return $this->response->setJSON([
                    'ok' => false,
                    'error' =>
                        'No hay registro pendiente por verificar.'
                ]);
            }


            if (
                strlen($codigo) !== 6 ||
                !ctype_digit($codigo)
            ) {
                return $this->response->setJSON([
                    'ok' => false,
                    'error' => 'Codigo invalido.'
                ]);
            }


            if (!$model->verificarCodigo($correo, $codigo)) {
                return $this->response->setJSON([
                    'ok' => false,
                    'error' =>
                        'Codigo incorrecto o expirado.'
                ]);
            }


            $model->activarCuenta($correo);

            $usuario = $model->buscarPorCorreo($correo);


            if (!$usuario) {
                return $this->response->setJSON([
                    'ok' => false,
                    'error' => 'Usuario no encontrado.'
                ]);
            }


            session()->set([
                'isLoggedIn' => true,
                'id_usuario' => $usuario['id_usuario'],
                'username'   => $usuario['username'] ?? '',
                'correo'     => $usuario['correo'],
                'rol'        => $usuario['rol'] ?? 'Cliente',
                'estado'     => $usuario['estado'] ?? 'Activo',
            ]);


            return $this->response->setJSON([
                'ok' => true,
                'mensaje' =>
                    'Cuenta verificada exitosamente.'
            ]);


        } catch (\Throwable $e) {

            log_message(
                'error',
                'Error en verificarCodigo: ' . $e->getMessage()
            );

            return $this->response->setJSON([
                'ok' => false,
                'error' =>
                    'Error del servidor: ' . $e->getMessage()
            ]);
        }
    }


    // =========================================================
    // REENVIAR CÓDIGO
    // =========================================================
    public function reenviarCodigo()
    {
        $this->response->setContentType('application/json');

        try {

            $correo = (string)
                session()->get('email_verificacion');


            if ($correo === '') {
                return $this->response->setJSON([
                    'ok' => false,
                    'error' =>
                        'No hay registro pendiente.'
                ]);
            }


            $model = new UsuarioModel();

            $codigo = $model->reenviarOTP($correo);

            $usuario = $model->buscarPorCorreo($correo);


            if ($codigo === null || !$usuario) {
                return $this->response->setJSON([
                    'ok' => false,
                    'error' =>
                        'No se pudo generar un nuevo codigo.'
                ]);
            }


            $enviado = $this->enviarEmailOTP(
                $correo,
                $usuario['username'] ?? 'Usuario',
                $codigo
            );


            return $this->response->setJSON([
                'ok' => true,
                'mensaje' => 'Codigo reenviado.',
                'email_enviado' => $enviado,
                'codigo_debug' =>
                    ENVIRONMENT === 'production'
                        ? null
                        : $codigo,
            ]);


        } catch (\Throwable $e) {

            log_message(
                'error',
                'Error en reenviarCodigo: ' . $e->getMessage()
            );

            return $this->response->setJSON([
                'ok' => false,
                'error' =>
                    'Error del servidor: ' . $e->getMessage()
            ]);
        }
    }


    // =========================================================
    // ENVIAR EMAIL OTP
    // =========================================================
    private function enviarEmailOTP(
        string $correo,
        string $nombre,
        string $codigo
    ): bool {

        try {

            $smtpUser = 'rodriguezjhondavid57@gmail.com';

            $smtpPass = 'sikg xeoz oglo rqsj';


            $mail = new PHPMailer(true);

            $mail->isSMTP();

            $mail->Host = 'smtp.gmail.com';

            $mail->SMTPAuth = true;

            $mail->Username = $smtpUser;

            $mail->Password = str_replace(
                ' ',
                '',
                $smtpPass
            );

            $mail->SMTPSecure =
                PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = 587;

            $mail->SMTPDebug = 0;

            $mail->CharSet = 'UTF-8';


            $mail->setFrom(
                $smtpUser,
                'Swapy'
            );

            $mail->addAddress(
                $correo,
                $nombre
            );


            $mail->isHTML(true);

            $mail->Subject =
                'Tu codigo de verificacion - Swapy';


            $mail->Body =
                "<h2>Hola, {$nombre}</h2>
                 <p>Tu codigo de verificacion es:</p>
                 <h1>{$codigo}</h1>
                 <p>Expira en 10 minutos.</p>";


            $mail->AltBody =
                "Hola {$nombre}, tu codigo de verificacion Swapy es: {$codigo}";


            $mail->send();

            return true;


        } catch (Exception $e) {

            log_message(
                'error',
                'PHPMailer Error: ' . $e->getMessage()
            );

            return false;
        }
    }
}