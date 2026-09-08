<?php
/**
 * models/SoporteModel.php
 * Maneja: preguntas frecuentes, denuncias y usuarios premium mediante el Query Builder de CodeIgniter 4.
 */

namespace App\Models;

class SoporteModel {

    private $db;

    public function __construct() {
        // Inicializamos el objeto de base de datos nativo de CodeIgniter 4
        $this->db = \Config\Database::connect();
    }

    /* ══════════════════════════════
       PREGUNTAS FRECUENTES
       ══════════════════════════════ */

    public function crearPregunta($id_usuario, $pregunta) {
        $data = [
            'id_usuario' => $id_usuario,
            'pregunta'   => $pregunta
        ];
        return $this->db->table('usuario_preguntas')->insert($data);
    }

    public function obtenerPreguntas($filtro = 'todas') {
        $builder = $this->db->table('usuario_preguntas up');
        $builder->select('up.*, u.correo');
        $builder->join('usuario u', 'up.id_usuario = u.id_usuario', 'inner');
        
        if ($filtro !== 'todas') {
            $builder->where('up.estado', $filtro);
        }
        
        $builder->orderBy('up.fecha_creacion', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Preguntas enviadas por un usuario específico (para que el cliente
     * las vea en el panel de soporte del chat, junto con la respuesta si ya existe).
     */
    public function obtenerPreguntasPorUsuario($id_usuario) {
        return $this->db->table('usuario_preguntas')
            ->where('id_usuario', $id_usuario)
            ->orderBy('fecha_creacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function responderPregunta($id_pregunta, $respuesta) {
        $data = [
            'respuesta'        => $respuesta,
            'estado'           => 'respondida',
            'fecha_respuesta'  => date('Y-m-d H:i:s')
        ];
        return $this->db->table('usuario_preguntas')
                        ->where('id_pregunta', $id_pregunta)
                        ->update($data);
    }

    public function cerrarPregunta($id_pregunta) {
        $data = ['estado' => 'cerrada'];
        return $this->db->table('usuario_preguntas')
                        ->where('id_pregunta', $id_pregunta)
                        ->update($data);
    }

    public function contarPreguntas($filtro = 'todas') {
        $builder = $this->db->table('usuario_preguntas');
        if ($filtro !== 'todas') {
            $builder->where('estado', $filtro);
        }
        return $builder->countAllResults();
    }

    /* ══════════════════════════════
       DENUNCIAS
       ══════════════════════════════ */

    public function crearDenuncia($id_usuario_denunciante, $tipo, $descripcion, $id_usuario_denunciado = null) {
        $data = [
            'id_usuario_denunciante' => $id_usuario_denunciante,
            'id_usuario_denunciado'  => $id_usuario_denunciado,
            'tipo_denuncia'          => $tipo,
            'descripcion'            => $descripcion
        ];
        return $this->db->table('usuario_denuncias')->insert($data);
    }

    public function obtenerDenuncias($filtro = 'todas') {
        $builder = $this->db->table('usuario_denuncias ud');
        $builder->select('ud.*, u1.correo AS correo_denunciante, u2.correo AS correo_denunciado');
        $builder->join('usuario u1', 'ud.id_usuario_denunciante = u1.id_usuario', 'inner');
        $builder->join('usuario u2', 'ud.id_usuario_denunciado = u2.id_usuario', 'left');
        
        if ($filtro !== 'todas') {
            $builder->where('ud.estado', $filtro);
        }
        
        $builder->orderBy('ud.fecha_creacion', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Denuncias hechas por un usuario específico (para que el cliente
     * las vea en el panel de soporte del chat, junto con la resolución si ya existe).
     */
    public function obtenerDenunciasPorUsuario($id_usuario) {
        return $this->db->table('usuario_denuncias')
            ->where('id_usuario_denunciante', $id_usuario)
            ->orderBy('fecha_creacion', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function responderDenuncia($id_denuncia, $respuesta, $estado = 'resuelta') {
        $data = [
            'respuesta'       => $respuesta,
            'estado'          => $estado,
            'fecha_respuesta' => date('Y-m-d H:i:s')
        ];
        return $this->db->table('usuario_denuncias')
                        ->where('id_denuncia', $id_denuncia)
                        ->update($data);
    }

    public function cambiarEstadoDenuncia($id_denuncia, $estado) {
        $data = ['estado' => $estado];
        return $this->db->table('usuario_denuncias')
                        ->where('id_denuncia', $id_denuncia)
                        ->update($data);
    }

    public function contarDenuncias($filtro = 'todas') {
        $builder = $this->db->table('usuario_denuncias');
        if ($filtro !== 'todas') {
            $builder->where('estado', $filtro);
        }
        return $builder->countAllResults();
    }

    /* ══════════════════════════════
       USUARIOS PREMIUM
       ══════════════════════════════ */

    public function crearPremium($id_usuario, $plan = 'basico', $meses = 1) {
        $fecha_fin = date('Y-m-d H:i:s', strtotime("+$meses months"));
        
        // Usamos una consulta directa limpia para manejar el ON DUPLICATE KEY UPDATE de MySQL
        $sql = "INSERT INTO usuario_premium (id_usuario, plan, fecha_fin, estado) 
                VALUES (?, ?, ?, 'activo')
                ON DUPLICATE KEY UPDATE plan = ?, fecha_fin = ?, estado = 'activo'";
                
        return $this->db->query($sql, [$id_usuario, $plan, $fecha_fin, $plan, $fecha_fin]);
    }

    public function obtenerPremium() {
        $builder = $this->db->table('usuario_premium up');
        $builder->select('up.*, u.correo, u.rol');
        $builder->join('usuario u', 'up.id_usuario = u.id_usuario', 'inner');
        $builder->where('u.rol', 'Cliente');
        $builder->orderBy('up.fecha_inicio', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    public function esUsuarioPremium($id_usuario) {
        $builder = $this->db->table('usuario_premium');
        $builder->where('id_usuario', $id_usuario);
        $builder->where('estado', 'activo');
        $builder->where('fecha_fin >', date('Y-m-d H:i:s'));
        
        return $builder->countAllResults() > 0;
    }

    public function cancelarPremium($id_usuario) {
        $data = ['estado' => 'cancelado'];
        return $this->db->table('usuario_premium')
                        ->where('id_usuario', $id_usuario)
                        ->update($data);
    }

    public function contarPremium() {
        $builder = $this->db->table('usuario_premium');
        $builder->where('estado', 'activo');
        $builder->where('fecha_fin >', date('Y-m-d H:i:s'));
        
        return $builder->countAllResults();
    }
}