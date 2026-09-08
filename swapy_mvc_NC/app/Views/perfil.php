<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | Swapy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/style/perfil.css') ?>">
</head>
<body>
    <div class="p-card">
        <a href="<?= base_url('index2') ?>" class="nav-back"><i class="fas fa-arrow-left"></i> Explorar</a>

        <div class="p-banner">
            <span class="tag-live">En linea</span>
        </div>

        <div class="p-header">
            <div class="p-img-wrap">
                <?php if (!empty($usuario['foto'])): ?>
                    <img src="<?= base_url('assets/IMG/' . $usuario['foto']) ?>" alt="<?= esc($usuario['username'] ?? 'Usuario') ?>" class="p-avatar">
                <?php else: ?>
                    <div class="p-avatar p-avatar-empty"></div>
                <?php endif; ?>

                <?php if (($usuario['verificado'] ?? '') === 'SI'): ?>
                    <div class="p-badge"><i class="fas fa-check-circle"></i></div>
                <?php endif; ?>
            </div>

            <div class="p-info">
                <h2><?= esc($usuario['username'] ?: 'Usuario de Swapy') ?></h2>
                <p class="p-job"><?= esc($usuario['rol'] ?? 'Cliente') ?></p>
                <p class="p-desc"><?= esc($usuario['descripcion'] ?: 'Sin descripcion todavia.') ?></p>
                <div class="p-loc"><i class="fas fa-envelope"></i> <?= esc($usuario['correo'] ?? '') ?></div>
                <div class="p-loc"><i class="fas fa-phone"></i> <?= esc($usuario['telefono'] ?: 'Sin telefono registrado') ?></div>
            </div>

            <div class="p-stats">
                <div class="s-item"><b><?= number_format($estadisticas['calificacion'] ?? 5.0, 1) ?></b><span>Calificacion</span></div>
                <div class="s-item"><b><?= $estadisticas['canjes'] ?? 0 ?></b><span>Canjes</span></div>
                <div class="s-item"><b><?= $estadisticas['productos'] ?? 0 ?></b><span>Productos</span></div>
            </div>

            <div class="p-btns">
                <button class="btn-edit" onclick="window.location.href='<?= base_url('editarperfil') ?>'"><i class="fas fa-pen"></i> Editar Perfil</button>
                <button onclick="logout()" class="btn-out" title="Cerrar Sesion"><i class="fas fa-sign-out-alt"></i></button>
            </div>
        </div>

        <div class="p-body">
            <div class="b-sec">
                <h3><i class="fas fa-info-circle"></i> Datos de cuenta</h3>
                <p>Correo: <?= esc($usuario['correo'] ?? '') ?></p>
                <p>Estado: <?= esc($usuario['estado'] ?? '') ?></p>
                <p>Premium: <?= !empty($usuario['premium']) ? 'Si' : 'No' ?></p>
            </div>
            <div class="b-sec">
                <h3><i class="fas fa-tags"></i> Categorias</h3>
                <div class="tag-wrap">
                    <?php foreach (array_filter(array_map('trim', explode(',', $usuario['categorias'] ?? ''))) as $categoria): ?>
                        <span class="tag"><?= esc($categoria) ?></span>
                    <?php endforeach; ?>

                    <?php if (empty(trim($usuario['categorias'] ?? ''))): ?>
                        <p>Sin categorias seleccionadas.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="p-feed">
            <small>RESENAS RECIENTES</small>

            <?php if (!empty($resenas)): ?>
                <?php foreach ($resenas as $r): ?>
                    <div class="f-item">
                        <div class="mini-p"><?= esc(strtoupper(substr($r['remitente'], 0, 1))) ?></div>
                        <div class="f-msg">
                            <strong><?= esc($r['remitente']) ?> <span class="stars"><?= number_format($r['estrellas'], 1) ?> <i class="fas fa-star"></i></span></strong>
                            <p><?= esc($r['comentario']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="font-size: 14px; color: #888; padding-top: 10px;">Aun no tienes resenas de intercambios.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function logout() {
            window.location.href = "<?= base_url('auth?accion=logout') ?>";
        }
    </script>
</body>
</html>
