<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_probability', '0');
    session_start();
}

$base = $base ?? '../../';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ' . $base . 'pages/login.php');
    exit;
}

function esAdmin() {
    return ($_SESSION['rol'] ?? '') === 'Administrador';
}

function requerirAdmin($base) {
    if (!esAdmin()) {
        header('Location: ' . $base . 'pages/inicio.php');
        exit;
    }
}