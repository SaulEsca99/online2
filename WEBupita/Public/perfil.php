<?php require_once __DIR__ . '/../includes/auth.php'; ?>
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<main class="content">
    <h1 class="page-title">Mi Perfil</h1>

    <p>👋 Hola, <strong><?= $_SESSION['usuario_nombre'] ?></strong>. Bienvenido a tu panel personal.</p>

    <div style="margin-top: 20px;">
        <ul style="line-height: 1.8;">
            <li><a href="mapa-interactivo.php">🗺 Ver el Mapa Interactivo</a></li>
            <li><a href="favoritos.php">⭐ Ver mis Lugares Favoritos</a></li>
            <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
        </ul>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
