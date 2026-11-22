<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento | Control de Préstamos</title>
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
            <h1>Bitácora de Mantenimiento</h1>
            
            <div class="panel">
                <div class="toolbar">
                    <div class="filters">
                        <input type="text" placeholder="Buscar por ID o falla..." class="input-search">
                        <select class="select-filter" name="estado_mantenimiento">
                            <option value="">Todos los estados</option>
                            <option value="En revision">En revisión</option>
                            <option value="Cerrado">Cerrado</option>
                        </select>
                    </div>
                    <a href="registrar.php" class="btn-primary btn-add">+ Reportar Falla</a>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Equipo</th>
                                <th>Fecha de Reporte</th>
                                <th>Descripción de Falla</th>
                                <th>Fecha de Reparación</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los registros se cargarán dinámicamente con PHP -->
                            <!-- En "Acciones" aparecerá el enlace a finalizar.php cuando el reporte siga abierto -->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script src="../../js/app.js"></script>
</body>
</html>