<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudModel extends Model
{
    protected $table            = 'solicitudes_entrega';
    protected $primaryKey       = 'id_solicitud';
    protected $useAutoIncrement = true; // <-- AGREGA ESTA LÍNEA
    protected $returnType       = 'array';
    protected $allowedFields    = ['producto_ofertado', 'producto_ofrecido', 'estado', 'fecha_creacion'];
}