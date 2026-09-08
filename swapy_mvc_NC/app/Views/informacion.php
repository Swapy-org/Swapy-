<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo) ?> | Swapy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --ink: #243447; --muted: #65788d; --blue: #4a9fd4; --sky: #eaf4fb; --line: #d9e6ef; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: linear-gradient(145deg, #f8fbfd, var(--sky)); color: var(--ink); font-family: 'DM Sans', sans-serif; }
        header { display: flex; align-items: center; justify-content: space-between; padding: 20px clamp(20px, 6vw, 80px); background: rgba(255,255,255,.86); border-bottom: 1px solid var(--line); }
        .brand { color: var(--ink); font: 800 1.45rem 'Syne', sans-serif; text-decoration: none; }
        .brand span { color: var(--blue); }
        .back { color: var(--blue); font-weight: 700; text-decoration: none; }
        main { width: min(900px, calc(100% - 40px)); margin: 70px auto; }
        .eyebrow { color: var(--blue); font-size: .78rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        h1 { margin: 12px 0; font: 800 clamp(2rem, 5vw, 3.6rem) 'Syne', sans-serif; }
        .intro { max-width: 680px; color: var(--muted); font-size: 1.1rem; line-height: 1.7; }
        .content { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 16px; margin-top: 42px; }
        article { padding: 24px; background: #fff; border: 1px solid var(--line); border-radius: 12px; box-shadow: 0 12px 30px rgba(36,52,71,.06); }
        article h2 { margin: 0 0 10px; font: 700 1.1rem 'Syne', sans-serif; }
        article p { margin: 0; color: var(--muted); line-height: 1.65; }
        footer { width: min(900px, calc(100% - 40px)); margin: 0 auto 40px; color: var(--muted); font-size: .85rem; }
    </style>
</head>
<body>
    <header>
        <a class="brand" href="<?= base_url() ?>">Swapy<span>.</span></a>
        <a class="back" href="<?= base_url() ?>">Volver al inicio</a>
    </header>
    <main>
        <span class="eyebrow"><?= esc($grupo) ?></span>
        <h1><?= esc($titulo) ?></h1>
        <p class="intro"><?= esc($intro) ?></p>
        <section class="content">
            <?php foreach ($secciones as $seccion): ?>
                <article>
                    <h2><?= esc($seccion['titulo']) ?></h2>
                    <p><?= esc($seccion['texto']) ?></p>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
    <footer>Swapy · Intercambios con comunidad en Colombia.</footer>
</body>
</html>
