<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Roll-ON' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/theme.css">
    <style>
        <?= $extraCss ?? '' ?>
    </style>
</head>
<body>
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
    <div class="header">
        <h1><i class="fas fa-cog"></i> ROLL-ON</h1>
        <div class="header-right">
            <span><?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?></span>
            <button class="theme-toggle" onclick="toggleTheme()">
                <i class="fas fa-moon"></i> Modo
            </button>
            <a href="/"><i class="fas fa-external-link-alt"></i> Ver Cotizador</a>
            <a href="/auth/logout">Cerrar Sesión</a>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Inicializar tema guardado
        if (localStorage.getItem('theme') === 'light') {
            document.body.classList.add('light-mode');
        }

        function toggleTheme() {
            document.body.classList.toggle('light-mode');
            localStorage.setItem('theme', document.body.classList.contains('light-mode') ? 'light' : 'dark');
        }
    </script>
    <div class="container">