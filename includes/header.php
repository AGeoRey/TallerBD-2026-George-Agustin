<?php
$titulo = $titulo ?? 'Panel';
$pagina_actual = $pagina_actual ?? '';
$base = $base ?? '../../';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?> | Control de Préstamos</title>
    <link rel="stylesheet" href="<?= $base ?>css/estilos.css">
</head>
<body class="admin-layout">

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Inventario</h2>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="<?= $base ?>pages/inicio.php"<?= $pagina_actual === 'inicio' ? ' class="active"' : '' ?>>Inicio</a></li>
                <li><a href="<?= $base ?>pages/equipos/equipos.php"<?= $pagina_actual === 'equipos' ? ' class="active"' : '' ?>>Equipos</a></li>
                <li><a href="<?= $base ?>pages/solicitantes/solicitantes.php"<?= $pagina_actual === 'solicitantes' ? ' class="active"' : '' ?>>Solicitantes</a></li>
                <li><a href="<?= $base ?>pages/prestamos/prestamos.php"<?= $pagina_actual === 'prestamos' ? ' class="active"' : '' ?>>Préstamos</a></li>
                <li><a href="<?= $base ?>pages/mantenimiento/mantenimiento.php"<?= $pagina_actual === 'mantenimiento' ? ' class="active"' : '' ?>>Mantenimiento</a></li>
                <li><a href="<?= $base ?>pages/consultas/disponibilidad.php"<?= $pagina_actual === 'disponibilidad' ? ' class="active"' : '' ?>>Disponibilidad</a></li>
                <li><a href="<?= $base ?>pages/consultas/historial.php"<?= $pagina_actual === 'historial' ? ' class="active"' : '' ?>>Historial</a></li>
                <li><a href="<?= $base ?>pages/catalogos/catalogos.php"<?= $pagina_actual === 'catalogos' ? ' class="active"' : '' ?>>Catálogos</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menú">☰ Menú</button>
            <div class="user-info">
                <span>Usuario Administrador</span>
                <a href="<?= $base ?>pages/login.php" class="btn-logout">Cerrar sesión</a>
            </div>
        </header>

        <section class="content">