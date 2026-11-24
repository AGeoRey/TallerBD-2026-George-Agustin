<?php
ini_set('session.gc_probability', '0');
session_start();

if (isset($_SESSION['id_usuario'])) {
    header('Location: inicio.php');
    exit;
}

require_once '../includes/conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id_usuario, username, password_hash, rol FROM usuarios_app WHERE username = ?');
    $stmt->execute([$username]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['username'] = $usuario['username'];
        $_SESSION['rol'] = $usuario['rol'];
        header('Location: inicio.php');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso | Control de Préstamos</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body class="login-layout">
    <div class="login-container">
        <h1>Control de Equipo Tecnológico</h1>

        <?php if ($error !== ''): ?>
            <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form id="loginForm" method="POST">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" maxlength="50" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Iniciar Sesión</button>
        </form>
    </div>
    <script src="../js/app.js"></script>
</body>
</html>