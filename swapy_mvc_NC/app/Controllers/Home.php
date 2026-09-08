<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT id_doc, tipo_doc
            FROM t_doc
            ORDER BY id_doc
        ");

        $tiposDoc = $query->getResultArray();

        return view('index', [
            'tiposDoc' => $tiposDoc
        ]);
    }
}