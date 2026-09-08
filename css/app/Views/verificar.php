<?php $correo = $_GET['correo'] ?? ''; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Cuenta</title>
    <link rel="stylesheet" href="<?= base_url('assets/style/verificar_style.css') ?>">
</head>
<body>
    <div class="box">
        <h2>Verificar Cuenta</h2>
        <p>Ingresa el codigo enviado al correo</p>

        <form method="POST" action="<?= base_url('auth?accion=verificar') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="correo" value="<?= esc($correo) ?>">
            <input type="text" name="codigo" placeholder="Codigo de 6 digitos" maxlength="6" required>
            <button type="submit">Verificar Cuenta</button>
        </form>
    </div>
</body>
</html>
