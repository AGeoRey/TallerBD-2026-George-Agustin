<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial | Control de Préstamos</title>
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
                <li><a href="../mantenimiento/mantenimiento.php">Mantenimiento</a></li>
                <li><a href="disponibilidad.php">Disponibilidad</a></li>
                <li><a href="historial.php" class="active">Historial</a></li>
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
            <h1>Historial de Movimientos</h1>
            
            <div class="panel">
                <div class="toolbar">
                    <form action="historial.php" method="GET" class="filters">
                        <select class="select-filter" name="criterio_busqueda">
                            <option value="equipo">Por ID de Equipo</option>
                            <option value="solicitante">Por Número Institucional</option>
                        </select>
                        <input type="text" name="valor_busqueda" placeholder="Ingrese el identificador..." class="input-search" required>
                        <button type="submit" class="btn-primary">Buscar Historial</button>
                    </form>
                </div>

                <h2>Historial de Préstamos</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Equipo</th>
                                <th>Solicitante</th>
                                <th>Fecha Salida</th>
                                <th>Fecha Recepción</th>
                                <th>Condición de Regreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los registros de préstamos se cargarán aquí -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Este panel se mostrará con PHP únicamente cuando criterio_busqueda sea "equipo" -->
            <div class="panel">
                <h2>Historial de Estados</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Estado Anterior</th>
                                <th>Estado Nuevo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Registros generados automáticamente por el trigger en historial_estados -->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script src="../../js/app.js"></script>
</body>
</html>