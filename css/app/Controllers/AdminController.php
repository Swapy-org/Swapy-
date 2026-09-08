<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\SoporteModel;

class AdminController extends BaseController
{
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    /**
     * Verifica que quien entra sea administrador.
     */
    private function verificarAdministrador()
    {
        if (
            !$this->session->get('isLoggedIn') ||
            strtolower((string)$this->session->get('rol')) !== 'administrador'
        ) {
            return redirect()->to(base_url('?login_error=acceso'));
        }

        return null;
    }

    /**
     * Página de solicitudes de entrega (solo lectura).
     */
    public function solicitudes()
    {
        $acceso = $this->verificarAdministrador();

        if ($acceso !== null) {
            return $acceso;
        }

        $db = \Config\Database::connect();

        $solicitudesEntrega = $db->query("
            SELECT i.*,
                   p.nombre_producto,
                   us.correo AS correo_solicitante,
                   up.correo AS correo_propietario
            FROM intercambios i
            LEFT JOIN productos p ON p.id_producto = i.id_producto
            LEFT JOIN usuario us ON us.id_usuario = i.id_solicitante
            LEFT JOIN usuario up ON up.id_usuario = i.id_propietario
            ORDER BY i.creado_en DESC
        ")->getResultArray();

        return view('admin_solicitudes', [
            'solicitudesEntrega' => $solicitudesEntrega
        ]);
    }

    /**
     * Guarda la respuesta del admin a una pregunta frecuente enviada por un cliente.
     */
    public function responderPregunta()
    {
        $acceso = $this->verificarAdministrador();

        if ($acceso !== null) {
            return $acceso;
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(base_url('admin_preguntas'));
        }

        $idPregunta = (int)($this->request->getPost('id_pregunta') ?? 0);

        $respuesta = trim(
            (string)($this->request->getPost('respuesta') ?? '')
        );

        if ($idPregunta <= 0 || $respuesta === '') {
            return redirect()->to(
                base_url('admin_preguntas?msg=error')
            );
        }

        try {

            $soporteModel = new SoporteModel();

            $ok = $soporteModel->responderPregunta($idPregunta, $respuesta);

            if (!$ok) {
                return redirect()->to(
                    base_url('admin_preguntas?msg=error')
                );
            }

            return redirect()->to(
                base_url('admin_preguntas?msg=respondida')
            );

        } catch (\Throwable $e) {

            log_message(
                'error',
                'AdminController::responderPregunta - ' . $e->getMessage()
            );

            return redirect()->to(
                base_url('admin_preguntas?msg=error')
            );
        }
    }

    /**
     * Guarda la resolución del admin a una denuncia enviada por un cliente.
     */
    public function responderDenuncia()
    {
        $acceso = $this->verificarAdministrador();

        if ($acceso !== null) {
            return $acceso;
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(base_url('admin_denuncias'));
        }

        $idDenuncia = (int)($this->request->getPost('id_denuncia') ?? 0);

        $estado = trim(
            (string)($this->request->getPost('estado') ?? 'resuelta')
        );

        $respuesta = trim(
            (string)($this->request->getPost('respuesta') ?? '')
        );

        $estadosValidos = [
            'resuelta',
            'rechazada',
            'en_revision'
        ];

        if (
            $idDenuncia <= 0 ||
            $respuesta === '' ||
            !in_array($estado, $estadosValidos, true)
        ) {
            return redirect()->to(
                base_url('admin_denuncias?msg=error')
            );
        }

        try {

            $soporteModel = new SoporteModel();

            $ok = $soporteModel->responderDenuncia($idDenuncia, $respuesta, $estado);

            if (!$ok) {
                return redirect()->to(
                    base_url('admin_denuncias?msg=error')
                );
            }

            return redirect()->to(
                base_url('admin_denuncias?msg=respondida')
            );

        } catch (\Throwable $e) {

            log_message(
                'error',
                'AdminController::responderDenuncia - ' . $e->getMessage()
            );

            return redirect()->to(
                base_url('admin_denuncias?msg=error')
            );
        }
    }

    /**
     * Panel principal y acciones del administrador.
     */
    public function index($subaccion = null)
    {
        $acceso = $this->verificarAdministrador();

        if ($acceso !== null) {
            return $acceso;
        }

        $model = new UsuarioModel();

        $accion = $subaccion;

        if (empty($accion)) {
            $accion = $this->request->getGet('accion') ?? '';
        }

        /*
        |--------------------------------------------------------------------------
        | CREAR USUARIO / EMPLEADO
        |--------------------------------------------------------------------------
        */

        if ($accion === 'crear') {

            if ($this->request->getMethod() !== 'POST') {
                return redirect()->to(base_url('admin'));
            }

            $documentoTipo = (int)($this->request->getPost('documento') ?? 0);

            $numeroDocumento = trim(
                (string)($this->request->getPost('numero_documento') ?? '')
            );

            $correo = trim(
                (string)($this->request->getPost('correo') ?? '')
            );

            $rol = trim(
                (string)($this->request->getPost('rol') ?? '')
            );

            $estado = trim(
                (string)($this->request->getPost('estado') ?? 'Activo')
            );

            $contrasena = (string)(
                $this->request->getPost('contrasena') ?? ''
            );

            $confirmarContrasena = (string)(
                $this->request->getPost('confirmar_contrasena') ?? ''
            );

            if (
                $documentoTipo <= 0 ||
                empty($numeroDocumento) ||
                empty($correo) ||
                empty($rol) ||
                empty($contrasena) ||
                empty($confirmarContrasena)
            ) {
                return redirect()->to(
                    base_url('admin?msg=error_campos')
                );
            }

            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                return redirect()->to(
                    base_url('admin?msg=error_correo')
                );
            }

            if (strlen($contrasena) < 6) {
                return redirect()->to(
                    base_url('admin?msg=error_contrasena')
                );
            }

            if ($contrasena !== $confirmarContrasena) {
                return redirect()->to(
                    base_url('admin?msg=error_contrasena_no_coincide')
                );
            }

            $rolesPermitidos = [
                'Administrador',
                'Empleado',
                'Cliente'
            ];

            if (!in_array($rol, $rolesPermitidos, true)) {
                return redirect()->to(
                    base_url('admin?msg=error')
                );
            }

            $estadosPermitidos = [
                'Activo',
                'Inactivo'
            ];

            if (!in_array($estado, $estadosPermitidos, true)) {
                $estado = 'Activo';
            }

            if ($model->correoExiste($correo)) {
                return redirect()->to(
                    base_url('admin?msg=correo_duplicado')
                );
            }

            if ($model->numeroDocumentoExiste($numeroDocumento)) {
                return redirect()->to(
                    base_url('admin?msg=documento_duplicado')
                );
            }

            if ($rol === 'Empleado') {

                if (strlen($correo) > 30) {
                    return redirect()->to(
                        base_url('admin?msg=error_correo')
                    );
                }

                if ($model->empleadoDuplicado($documentoTipo, $numeroDocumento)) {
                    return redirect()->to(
                        base_url('admin?msg=documento_duplicado')
                    );
                }

                if ($model->empleadoCorreoExiste($correo)) {
                    return redirect()->to(
                        base_url('admin?msg=correo_duplicado')
                    );
                }
            }

            try {

                $resultado = $model->crearDesdeAdmin([
                    'pkfk_id_doc'       => $documentoTipo,
                    'numero_documento'  => $numeroDocumento,
                    'correo'            => $correo,
                    'contrasena'        => $contrasena,
                    'rol'               => $rol,
                    'estado'            => $estado
                ]);

                if (!$resultado['ok']) {

                    log_message(
                        'error',
                        'Error creando usuario/empleado: ' .
                        ($resultado['error'] ?? 'Error desconocido')
                    );

                    if (($resultado['tipo'] ?? '') === 'empleado') {
                        return redirect()->to(
                            base_url('admin?msg=error_empleado')
                        );
                    }

                    return redirect()->to(
                        base_url('admin?msg=error')
                    );
                }

                return redirect()->to(
                    base_url('admin?msg=creado')
                );

            } catch (\Throwable $e) {

                log_message(
                    'error',
                    'AdminController::crear - ' .
                    $e->getMessage()
                );

                return redirect()->to(
                    base_url('admin?msg=error')
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | EDITAR USUARIO
        |--------------------------------------------------------------------------
        */

        if ($accion === 'editar') {

            if ($this->request->getMethod() !== 'POST') {
                return redirect()->to(
                    base_url('admin')
                );
            }

            $id = (int)(
                $this->request->getPost('id') ?? 0
            );

            $documentoTipo = (int)(
                $this->request->getPost('documento') ?? 0
            );

            $numeroDocumento = trim(
                (string)($this->request->getPost('numero_documento') ?? '')
            );

            $correo = trim(
                (string)($this->request->getPost('correo') ?? '')
            );

            $rol = trim(
                (string)($this->request->getPost('rol') ?? '')
            );

            $contrasenaAnterior = (string)(
                $this->request->getPost('contrasena_anterior') ?? ''
            );

            $contrasena = (string)(
                $this->request->getPost('contrasena') ?? ''
            );

            $confirmarContrasena = (string)(
                $this->request->getPost('confirmar_contrasena') ?? ''
            );

            if (
                $id <= 0 ||
                $documentoTipo <= 0 ||
                empty($numeroDocumento) ||
                empty($correo) ||
                empty($rol)
            ) {
                return redirect()->to(
                    base_url('admin?msg=error_campos')
                );
            }

            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                return redirect()->to(
                    base_url('admin?msg=error_correo')
                );
            }

            if ($contrasena !== '') {

                if (strlen($contrasena) < 6) {
                    return redirect()->to(
                        base_url('admin?msg=error_contrasena')
                    );
                }

                if ($contrasena !== $confirmarContrasena) {
                    return redirect()->to(
                        base_url('admin?msg=error_contrasena_no_coincide')
                    );
                }

                $usuarioActual = $model->obtenerPorId($id);

                if (
                    $usuarioActual &&
                    !password_verify($contrasenaAnterior, $usuarioActual['contrasena'])
                ) {
                    return redirect()->to(
                        base_url('admin?msg=error_contrasena_actual')
                    );
                }
            }

            if ($model->correoExiste($correo, $id)) {
                return redirect()->to(
                    base_url('admin?msg=correo_duplicado')
                );
            }

            if ($rol === 'Empleado' && strlen($correo) > 30) {
                return redirect()->to(
                    base_url('admin?msg=error_correo')
                );
            }

            try {

                $ok = $model->editarDesdeAdmin(
                    $id,
                    $documentoTipo,
                    $numeroDocumento,
                    $correo,
                    $rol,
                    $contrasena
                );

                if (!$ok) {
                    return redirect()->to(
                        base_url('admin?msg=error')
                    );
                }

                return redirect()->to(
                    base_url('admin?msg=editado')
                );

            } catch (\Throwable $e) {

                log_message(
                    'error',
                    'AdminController::editar - ' .
                    $e->getMessage()
                );

                return redirect()->to(
                    base_url('admin?msg=error')
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CAMBIAR ESTADO
        |--------------------------------------------------------------------------
        */

        if ($accion === 'cambiar_estado') {

            $id = (int)(
                $this->request->getGet('id') ?? 0
            );

            $estado = trim(
                (string)($this->request->getGet('estado') ?? '')
            );

            if ($id <= 0) {
                return redirect()->to(
                    base_url('admin?msg=error')
                );
            }

            $estadosValidos = [
                'Activo',
                'Inactivo'
            ];

            if (!in_array($estado, $estadosValidos, true)) {
                return redirect()->to(
                    base_url('admin?msg=error')
                );
            }

            try {

                $ok = $model->cambiarEstado($id, $estado);

                if (!$ok) {
                    return redirect()->to(
                        base_url('admin?msg=error')
                    );
                }

                $msg = $estado === 'Activo'
                    ? 'activado'
                    : 'desactivado';

                return redirect()->to(
                    base_url('admin?msg=' . $msg)
                );

            } catch (\Throwable $e) {

                log_message(
                    'error',
                    'AdminController::cambiar_estado - ' .
                    $e->getMessage()
                );

                return redirect()->to(
                    base_url('admin?msg=error')
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ELIMINAR
        |--------------------------------------------------------------------------
        */

        if ($accion === 'eliminar') {

            $id = (int)(
                $this->request->getGet('id') ?? 0
            );

            if ($id <= 0 || !$model->existePorId($id)) {
                return redirect()->to(
                    base_url('admin?msg=error')
                );
            }

            try {

                $ok = $model->eliminarCompleto($id);

                if (!$ok) {
                    return redirect()->to(
                        base_url('admin?msg=error')
                    );
                }

                return redirect()->to(
                    base_url('admin?msg=eliminado')
                );

            } catch (\Throwable $e) {

                log_message(
                    'error',
                    'AdminController::eliminar - ' .
                    $e->getMessage()
                );

                return redirect()->to(
                    base_url('admin?msg=error')
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PANEL
        |--------------------------------------------------------------------------
        */

        return view('admin');
    }
}