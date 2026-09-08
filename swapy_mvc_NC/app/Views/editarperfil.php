<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil | Swapy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/style/perfil.css') ?>">
    <style>
        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 28px;
        }

        .edit-card {
            width: min(680px, 100%);
            background: #fff;
            border: 1px solid rgba(20, 34, 63, .1);
            border-radius: 8px;
            box-shadow: 0 18px 60px rgba(20, 34, 63, .12);
            padding: 28px;
        }

        .edit-head,
        .actions {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            align-items: center;
            flex-wrap: wrap;
        }

        .edit-head {
            margin-bottom: 24px;
        }

        .edit-head h1 {
            margin: 0;
            font-family: Syne, sans-serif;
            font-size: clamp(1.6rem, 4vw, 2.4rem);
        }

        .edit-form,
        .field {
            display: grid;
            gap: 14px;
        }

        .field label {
            font-weight: 700;
            color: #1d2b45;
        }

        .field input,
        .field textarea {
            width: 100%;
            border: 1px solid #d8e0ec;
            border-radius: 8px;
            padding: 12px 14px;
            font: inherit;
            color: #1d2b45;
            background: #fff;
        }

        .field textarea {
            min-height: 120px;
            resize: vertical;
        }

        .current-photo {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #eef4fb;
        }

        .photo-row {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .no-photo {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            border: 2px dashed #c8d5e6;
            display: grid;
            place-items: center;
            color: #64748b;
            font-size: 13px;
            font-weight: 700;
            text-align: center;
        }

        .remove-photo-option {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #b42318;
            font-weight: 700;
        }

        .remove-photo-option input {
            width: auto;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }

        .category-option {
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #d8e0ec;
            border-radius: 8px;
            padding: 10px 12px;
            color: #1d2b45;
            font-weight: 700;
        }

        .msg {
            border-radius: 8px;
            padding: 12px 14px;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .msg.ok {
            background: #e9f8ef;
            color: #166534;
        }

        .msg.err {
            background: #fff0f0;
            color: #b42318;
        }
    </style>
</head>
<body>
    <main class="edit-card">
        <div class="edit-head">
            <h1>Editar perfil</h1>
            <a href="<?= base_url('perfil') ?>" class="nav-back"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>

        <?php if (!empty($exito)): ?>
            <div class="msg ok"><?= esc($exito) ?></div>
        <?php endif; ?>

        <?php if (!empty($errores)): ?>
            <div class="msg err">
                <?php foreach ((array) $errores as $error): ?>
                    <div><?= esc($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form class="edit-form" action="<?= base_url('editarperfil') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="field">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" maxlength="50" value="<?= esc(old('username', $usuario['username'] ?? '')) ?>" required>
            </div>

            <div class="field">
                <label for="telefono">Telefono</label>
                <input type="tel" id="telefono" name="telefono" maxlength="20" value="<?= esc(old('telefono', $usuario['telefono'] ?? '')) ?>">
            </div>

            <div class="field">
                <label for="descripcion">Descripcion</label>
                <textarea id="descripcion" name="descripcion" maxlength="500"><?= esc(old('descripcion', $usuario['descripcion'] ?? '')) ?></textarea>
            </div>

            <div class="field">
                <label for="foto">Foto</label>
                <div class="photo-row">
                    <?php if (!empty($usuario['foto'])): ?>
                        <img src="<?= base_url('assets/IMG/' . $usuario['foto']) ?>" alt="<?= esc($usuario['username'] ?? 'Usuario') ?>" class="current-photo">
                    <?php else: ?>
                        <div class="no-photo">Sin foto</div>
                    <?php endif; ?>

                    <label class="remove-photo-option">
                        <input type="checkbox" name="quitar_foto" value="1">
                        <span>Quitar foto</span>
                    </label>
                </div>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>

            <div class="field">
                <label>Categorias</label>
                <?php
                    $categoriasActuales = old('categorias', $usuario['categorias'] ?? '');
                    $seleccionadas = is_array($categoriasActuales)
                        ? array_filter(array_map('trim', $categoriasActuales))
                        : array_filter(array_map('trim', explode(',', $categoriasActuales)));
                ?>
                <div class="category-grid">
                    <?php foreach (($categoriasDisponibles ?? []) as $categoria): ?>
                        <?php $nombreCategoria = $categoria['n_categoria'] ?? ''; ?>
                        <label class="category-option">
                            <input type="checkbox" name="categorias[]" value="<?= esc($nombreCategoria) ?>" <?= in_array($nombreCategoria, $seleccionadas, true) ? 'checked' : '' ?>>
                            <span><?= esc($nombreCategoria) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="actions">
                <a href="<?= base_url('perfil') ?>" class="btn-out">Cancelar</a>
                <button type="submit" class="btn-edit"><i class="fas fa-save"></i> Guardar cambios</button>
            </div>
        </form>
    </main>
</body>
</html>
