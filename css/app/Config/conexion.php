<?php

$db = \Config\Database::connect();
$db->initialize();

$conexion = $db->connID;

if (!$conexion) {
    die('Error de conexion con la base de datos.');
}

mysqli_set_charset($conexion, 'utf8mb4');

$GLOBALS['conexion'] = $conexion;
