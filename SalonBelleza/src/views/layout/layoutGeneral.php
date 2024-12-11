<?php require_once __DIR__ . '/header.php'; ?>

<main style="padding: 20px; margin-bottom: 50px;">
    <?= isset($contenido) ? $contenido : '<p>No hay contenido para mostrar.</p>'; ?>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
