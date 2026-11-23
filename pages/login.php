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
        <form id="loginForm" method="POST">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" maxlength="50" required>
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