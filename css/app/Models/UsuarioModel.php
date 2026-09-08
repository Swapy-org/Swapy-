<?php
 
namespace App\Models;
 
use CodeIgniter\Model;
 
class UsuarioModel extends Model
{
    protected $table = 'usuario';
 
    protected $primaryKey = 'id_usuario';
 
    protected $returnType = 'array';
 
    protected $allowedFields = [
        'pkfk_id_doc',
        'username',
        'telefono',
        'correo',
        'numero_documento',
        'contrasena',
        'rol',
        'estado',
        'codigo_verificacion',
        'otp_expira',
        'verificado',
        'premium',
        'nombre',
        'descripcion',
        'foto',
        'categorias'
    ];
 
    /* =========================================================
       BUSCAR POR CORREO
    ========================================================= */
 
    public function buscarPorCorreo(string $correo): ?array
    {
        return $this
            ->where('correo', $correo)
            ->first();
    }
 
    /* =========================================================
       CORREO EXISTE
    ========================================================= */
 
    public function correoExiste(
        string $correo,
        int $exceptoId = 0
    ): bool {
 
        $builder = $this
            ->where('correo', $correo);
 
        if ($exceptoId > 0) {
            $builder->where('id_usuario !=', $exceptoId);
        }
 
        return $builder->countAllResults() > 0;
    }
 
    /* =========================================================
       DOCUMENTO EXISTE
    ========================================================= */
 
    public function numeroDocumentoExiste(
        string $numeroDocumento,
        int $exceptoId = 0
    ): bool {
 
        $builder = $this
            ->where('numero_documento', $numeroDocumento);
 
        if ($exceptoId > 0) {
            $builder->where('id_usuario !=', $exceptoId);
        }
 
        return $builder->countAllResults() > 0;
    }
 
    /* =========================================================
       CREAR DESDE ADMIN
    ========================================================= */
 
    public function crearDesdeAdmin(array $datos): array
    {
        $db = \Config\Database::connect();
 
        $db->transBegin();
 
        try {
 
            /*
            |--------------------------------------------------------------------------
            | HASH DE CONTRASEÑA (bcrypt explícito)
            |--------------------------------------------------------------------------
            */
 
            $hash = password_hash(
                $datos['contrasena'],
                PASSWORD_BCRYPT
            );
 
            /*
            |--------------------------------------------------------------------------
            | INSERTAR EN USUARIO
            |--------------------------------------------------------------------------
            */
 
            $usuarioData = [
                'pkfk_id_doc'      => (int)$datos['pkfk_id_doc'],
                'numero_documento' => $datos['numero_documento'],
                'correo'           => $datos['correo'],
                'contrasena'       => $hash,
                'rol'              => $datos['rol'],
                'estado'           => $datos['estado'],
                'verificado'       => 'SI',
                'premium'          => 0
            ];
 
            $db->table('usuario')->insert($usuarioData);
 
            if ($db->affectedRows() <= 0) {
                throw new \Exception('No se pudo insertar el usuario.');
            }
 
            /*
            |--------------------------------------------------------------------------
            | SI ES EMPLEADO — se replica correo y contraseña EXACTOS
            |--------------------------------------------------------------------------
            */
 
            if (strtolower($datos['rol']) === 'empleado') {

                $idRecuperar = (int)($db->query(
                    'SELECT COALESCE(MAX(id_recuperar_cuenta), 0) + 1 AS siguiente FROM recuperar_cuenta'
                )->getRow()->siguiente ?? 1);

                $db->table('recuperar_cuenta')->insert([
                    'id_recuperar_cuenta' => $idRecuperar,
                    'codigo_verif' => 0
                ]);

                if ($db->affectedRows() <= 0) {
                    throw new \Exception('No se pudo crear la cuenta de recuperación del empleado.');
                }

                $db->table('persona')->insert([
                    'fkpk_id_doc'          => (int)$datos['pkfk_id_doc'],
                    'documento'            => $datos['numero_documento'],
                    'primer_nombre'        => 'Empleado',
                    'primer_apellido'      => 'Swapy',
                    'fk_id_recuperar_cuenta' => $idRecuperar
                ]);

                if ($db->affectedRows() <= 0) {
                    throw new \Exception('No se pudo crear la persona del empleado.');
                }
 
                $empleadoData = [
                    'pkfk_id_doc' => (int)$datos['pkfk_id_doc'],
                    'id_empleado' => $datos['numero_documento'],
                    'correo'      => $datos['correo'],
                    'contrasena'  => $hash
                ];
 
                $db->table('empleado')->insert($empleadoData);
 
                if ($db->affectedRows() <= 0) {
                    throw new \Exception('No se pudo insertar el empleado.');
                }
            }
 
            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */
 
            if ($db->transStatus() === false) {
                throw new \Exception('La transacción falló.');
            }
 
            $db->transCommit();
 
            return ['ok' => true];
 
        } catch (\Throwable $e) {
 
            $db->transRollback();
 
            log_message(
                'error',
                'UsuarioModel::crearDesdeAdmin - ' . $e->getMessage()
            );
 
            return [
                'ok'    => false,
                'tipo'  => strtolower($datos['rol']) === 'empleado' ? 'empleado' : 'usuario',
                'error' => $e->getMessage()
            ];
        }
    }
 
    /* =========================================================
       EDITAR DESDE ADMIN
    ========================================================= */
 
    public function editarDesdeAdmin(
        int $id,
        int $idDoc,
        string $numeroDocumento,
        string $correo,
        string $rol,
        string $contrasena = ''
    ): bool {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $usuario = $this->find($id);
            if (!$usuario) {
                throw new \Exception('Usuario no encontrado.');
            }

            $correoAnterior = (string) $usuario['correo'];
            $documentoAnterior = (string) ($usuario['numero_documento'] ?? '');
            $idDocAnterior = (int) ($usuario['pkfk_id_doc'] ?? 0);
            $rolAnterior = strtolower((string) ($usuario['rol'] ?? ''));

            $data = [
                'pkfk_id_doc'      => $idDoc,
                'numero_documento' => $numeroDocumento,
                'correo'           => $correo,
                'rol'              => $rol
            ];
 
            /*
            |--------------------------------------------------------------------------
            | CAMBIAR CONTRASEÑA (bcrypt explícito)
            |--------------------------------------------------------------------------
            */
 
            if ($contrasena !== '') {
                $data['contrasena'] = password_hash($contrasena, PASSWORD_BCRYPT);
            }
            if (!$this->update($id, $data)) {
                throw new \Exception('No se pudo actualizar el usuario.');
            }

            $personaBuilder = $db->table('persona');
            $persona = $personaBuilder
                ->where('fkpk_id_doc', $idDocAnterior)
                ->where('documento', $documentoAnterior)
                ->get()
                ->getFirstRow('array');

            $empleadoBuilder = $db->table('empleado');
            $empleado = $empleadoBuilder
                ->groupStart()
                    ->where('pkfk_id_doc', $idDocAnterior)
                    ->where('id_empleado', $documentoAnterior)
                ->groupEnd()
                ->orWhere('correo', $correoAnterior)
                ->get()
                ->getFirstRow('array');

            if (strtolower($rol) === 'empleado') {
                if (!$persona) {
                    $idRecuperar = (int) ($db->query(
                        'SELECT COALESCE(MAX(id_recuperar_cuenta), 0) + 1 AS siguiente FROM recuperar_cuenta'
                    )->getRow()->siguiente ?? 1);

                    $db->table('recuperar_cuenta')->insert([
                        'id_recuperar_cuenta' => $idRecuperar,
                        'codigo_verif' => 0
                    ]);

                    $db->table('persona')->insert([
                        'fkpk_id_doc' => $idDoc,
                        'documento' => $numeroDocumento,
                        'primer_nombre' => 'Empleado',
                        'primer_apellido' => 'Swapy',
                        'fk_id_recuperar_cuenta' => $idRecuperar
                    ]);
                } elseif ($idDocAnterior !== $idDoc || $documentoAnterior !== $numeroDocumento) {
                    $personaNueva = $persona;
                    $personaNueva['fkpk_id_doc'] = $idDoc;
                    $personaNueva['documento'] = $numeroDocumento;
                    $db->table('persona')->insert($personaNueva);
                }

                $empleadoData = [
                    'pkfk_id_doc' => $idDoc,
                    'id_empleado' => $numeroDocumento,
                    'correo' => $correo,
                    'contrasena' => $contrasena !== ''
                        ? password_hash($contrasena, PASSWORD_BCRYPT)
                        : (string) ($empleado['contrasena'] ?? $usuario['contrasena'])
                ];

                if ($empleado) {
                    $empleadoBuilder
                        ->where('pkfk_id_doc', $empleado['pkfk_id_doc'])
                        ->where('id_empleado', $empleado['id_empleado'])
                        ->update($empleadoData);
                } else {
                    $db->table('empleado')->insert($empleadoData);
                }

                if ($persona && ($idDocAnterior !== $idDoc || $documentoAnterior !== $numeroDocumento)) {
                    $db->table('persona')
                        ->where('fkpk_id_doc', $idDocAnterior)
                        ->where('documento', $documentoAnterior)
                        ->delete();
                }
            } elseif ($empleado) {
                $empleadoBuilder
                    ->where('pkfk_id_doc', $empleado['pkfk_id_doc'])
                    ->where('id_empleado', $empleado['id_empleado'])
                    ->delete();
            }

            if ($db->transStatus() === false) {
                throw new \Exception('Error en la actualización.');
            }
            $db->transCommit();
            return true;
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'UsuarioModel::editarDesdeAdmin - ' . $e->getMessage());
            return false;
        }
    }
 
    /* =========================================================
       OBTENER TODOS
    ========================================================= */
 
    public function obtenerTodos()
    {
        return $this
            ->select('usuario.*, t_doc.tipo_doc')
            ->join('t_doc', 't_doc.id_doc = usuario.pkfk_id_doc', 'left')
            ->orderBy('usuario.id_usuario', 'DESC')
            ->findAll();
    }
 
    /* =========================================================
       CONTADORES
    ========================================================= */
 
    public function contarTotal(): int
    {
        return $this->countAll();
    }
 
    public function contarActivos(): int
    {
        return $this->where('estado', 'Activo')->countAllResults();
    }
 
    public function contarInactivos(): int
    {
        return $this->where('estado', 'Inactivo')->countAllResults();
    }
 
    public function contarAdmins(): int
    {
        return $this->where('rol', 'Administrador')->countAllResults();
    }
 
    public function contarEmpleados(): int
    {
        return $this->where('rol', 'Empleado')->countAllResults();
    }
 
    /* =========================================================
       TIPOS DE DOCUMENTO
    ========================================================= */
 
    public function obtenerTiposDoc(): array
    {
        return \Config\Database::connect()
            ->table('t_doc')
            ->select('id_doc, tipo_doc')
            ->orderBy('id_doc', 'ASC')
            ->get()
            ->getResultArray();
    }
 
    /* =========================================================
       EXISTE POR ID
    ========================================================= */
 
    public function existePorId(int $id): bool
    {
        return $this->where('id_usuario', $id)->countAllResults() > 0;
    }
 
    /* =========================================================
       OBTENER POR ID
    ========================================================= */
 
    public function obtenerPorId(int $id): ?array
    {
        return $this->find($id);
    }
 
    /* =========================================================
       CAMBIAR ESTADO
    ========================================================= */
 
    public function cambiarEstado(int $id, string $estado): bool
    {
        return $this->update($id, ['estado' => $estado]);
    }
 
    /* =========================================================
       ELIMINAR COMPLETO
    ========================================================= */
 
    public function eliminarCompleto(int $id): bool
    {
        $db = \Config\Database::connect();
 
        $db->transBegin();
 
        try {
 
            $usuario = $this->find($id);
 
            if (!$usuario) {
                throw new \Exception('Usuario no encontrado.');
            }
 
            if (strtolower((string)$usuario['rol']) === 'empleado') {
                $db->table('empleado')
                    ->where('correo', $usuario['correo'])
                    ->delete();
            }
 
            $this->delete($id);
 
            if ($db->transStatus() === false) {
                throw new \Exception('No se pudo eliminar.');
            }
 
            $db->transCommit();
 
            return true;
 
        } catch (\Throwable $e) {
 
            $db->transRollback();
 
            log_message(
                'error',
                'UsuarioModel::eliminarCompleto - ' . $e->getMessage()
            );
 
            return false;
        }
    }
 
    /* =========================================================
       PERFIL
    ========================================================= */
 
    public function actualizarPerfil(int $id, array $datos): bool
    {
        $permitidos = [
            'username',
            'telefono',
            'descripcion',
            'foto',
            'categorias'
        ];
 
        $limpios = [];
 
        foreach ($permitidos as $campo) {
            if (array_key_exists($campo, $datos)) {
                $limpios[$campo] = $datos[$campo];
            }
        }
 
        if (empty($limpios)) {
            return true;
        }
 
        return $this->update($id, $limpios);
    }
 
    /* =========================================================
       REGISTRO NORMAL
    ========================================================= */
 
    public function registrar(
        int $idDoc,
        string $numeroDocumento,
        string $correo,
        string $contrasena,
        string $rol,
        string $username = '',
        string $telefono = ''
    ): bool {
 
        $codigo = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
 
        $expira = date('Y-m-d H:i:s', strtotime('+10 minutes'));
 
        return $this->insert([
            'pkfk_id_doc'         => $idDoc,
            'numero_documento'    => $numeroDocumento,
            'username'            => $username,
            'telefono'            => $telefono,
            'correo'              => $correo,
            'contrasena'          => password_hash($contrasena, PASSWORD_BCRYPT),
            'rol'                 => $rol,
            'estado'              => 'pendiente',
            'codigo_verificacion' => $codigo,
            'otp_expira'          => $expira,
            'verificado'          => 'NO',
            'premium'             => 0
        ]) !== false;
    }
 
    /* =========================================================
       VERIFICAR CÓDIGO
    ========================================================= */
 
    public function verificarCodigo(string $correo, string $codigo): bool
    {
        $usuario = $this
            ->where('correo', $correo)
            ->where('codigo_verificacion', $codigo)
            ->where('estado', 'pendiente')
            ->first();
 
        if (!$usuario) {
            return false;
        }
 
        return empty($usuario['otp_expira'])
            || strtotime($usuario['otp_expira']) >= time();
    }
 
    /* =========================================================
       ACTIVAR CUENTA
    ========================================================= */
 
    public function activarCuenta(string $correo): bool
    {
        return $this
            ->where('correo', $correo)
            ->set([
                'estado'              => 'Activo',
                'verificado'          => 'SI',
                'codigo_verificacion' => null,
                'otp_expira'          => null
            ])
            ->update();
    }
 
    /* =========================================================
       REENVIAR OTP
    ========================================================= */
 
    public function reenviarOTP(string $correo): ?string
    {
        $usuario = $this
            ->where('correo', $correo)
            ->where('estado', 'pendiente')
            ->first();
 
        if (!$usuario) {
            return null;
        }
 
        $codigo = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
 
        $expira = date('Y-m-d H:i:s', strtotime('+10 minutes'));
 
        $this->update($usuario['id_usuario'], [
            'codigo_verificacion' => $codigo,
            'otp_expira'          => $expira
        ]);
 
        return $codigo;
    }
}