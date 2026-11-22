<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportar Falla | Control de Préstamos</title>
    <link rel="stylesheet" href="../../css/estilos.css">
</head>
<body class="admin-layout">
    
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Inventario</h2>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="../inicio.php">Inicio</a></li>
                <li><a href="../equipos/equipos.php">Equipos</a></li>
                <li><a href="../solicitantes/solicitantes.php">Solicitantes</a></li>
                <li><a href="../prestamos/prestamos.php">Préstamos</a></li>
                <li><a href="mantenimiento.php" class="active">Mantenimiento</a></li>
                <li><a href="../consultas/disponibilidad.php">Disponibilidad</a></li>
                <li><a href="../consultas/historial.php">Historial</a></li>
                <li><a href="../catalogos/catalogos.php">Catálogos</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menú">☰ Menú</button>
            <div class="user-info">
                <span>Usuario Administrador</span>
                <a href="../login.php" class="btn-logout">Cerrar sesión</a>
            </div>
        </header>

        <section class="content">
            <h1>Reportar Falla de Equipo</h1>
            
            <div class="panel form-panel">
                <form action="mantenimiento.php" method="GET">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="id_inventario">Equipo</label>
                            <select id="id_inventario" name="id_inventario" required>
                                <option value="">Seleccione un equipo disponible...</option>
                                <!-- Opciones cargadas dinámicamente; preseleccionado si llega por URL -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_reporte">Fecha de Reporte</label>
                            <input type="datetime-local" id="fecha_reporte" name="fecha_reporte" required>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="descripcion_falla">Descripción de la Falla</label>
                        <textarea id="descripcion_falla" name="descripcion_falla" rows="4" placeholder="Detalle el problema técnico detectado en el dispositivo..." required></textarea>
                    </div>

                    <div class="form-actions">
                        <a href="mantenimiento.php" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Registrar Mantenimiento</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script src="../../js/app.js"></script>
</body>
</html>