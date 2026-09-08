<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoModel extends Model
{
    protected $table = 'empleado';

    protected $primaryKey = 'id_empleado';

    protected $returnType = 'array';

    protected $allowedFields = [
        'pkfk_id_doc',
        'id_empleado',
        'correo',
        'contrasena'
    ];

    /*
    |--------------------------------------------------------------------------
    | OBTENER TODOS LOS EMPLEADOS
    |--------------------------------------------------------------------------
    */

    public function obtenerTodos(): array
    {
        $db = \Config\Database::connect();

        return $db->table('empleado e')
            ->select('
                e.pkfk_id_doc,
                e.id_empleado,
                e.correo,
                e.contrasena,
                u.id_usuario,
                u.rol,
                u.estado,
                u.numero_documento,
                t.tipo_doc
            ')
            ->join(
                'usuario u',
                'u.correo = e.correo',
                'left'
            )
            ->join(
                't_doc t',
                't.id_doc = e.pkfk_id_doc',
                'left'
            )
            ->orderBy('e.id_empleado', 'DESC')
            ->get()
            ->getResultArray();
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR EMPLEADO
    |--------------------------------------------------------------------------
    */

    public function buscarPorId(int $idEmpleado): ?array
    {
        return $this
            ->where('id_empleado', $idEmpleado)
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR POR CORREO
    |--------------------------------------------------------------------------
    */

    public function buscarPorCorreo(string $correo): ?array
    {
        return $this
            ->where('correo', $correo)
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | COMPROBAR EXISTENCIA
    |--------------------------------------------------------------------------
    */

    public function existe(
        int $idDoc,
        int $idEmpleado
    ): bool {

        return $this
            ->where('pkfk_id_doc', $idDoc)
            ->where('id_empleado', $idEmpleado)
            ->countAllResults() > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | CREAR EMPLEADO
    |--------------------------------------------------------------------------
    */

    public function crear(
        int $idDoc,
        int $idEmpleado,
        string $correo,
        string $contrasena
    ): bool {

        return $this->insert([
            'pkfk_id_doc' => $idDoc,
            'id_empleado' => $idEmpleado,
            'correo' => $correo,
            'contrasena' => password_hash(
                $contrasena,
                PASSWORD_DEFAULT
            )
        ]) !== false;
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINAR EMPLEADO
    |--------------------------------------------------------------------------
    */

    public function eliminar(
        int $idDoc,
        int $idEmpleado
    ): bool {

        return $this
            ->where('pkfk_id_doc', $idDoc)
            ->where('id_empleado', $idEmpleado)
            ->delete();
    }
}