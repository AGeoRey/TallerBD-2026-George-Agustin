<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disponibilidad | Control de Préstamos</title>
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
                <li><a href="disponibilidad.php" class="active">Disponibilidad</a></li>
                <li><a href="historial.php">Historial</a></li>
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
            <h1>Disponibilidad de Equipos</h1>
            
            <div class="panel">
                <div class="toolbar">
                    <form action="disponibilidad.php" method="GET" class="filters">
                        <select class="select-filter" id="tipo_reporte" name="tipo_reporte">
                            <option value="general">Listado general de equipos</option>
                            <option value="disponibles">Equipos disponibles</option>
                            <option value="activos">Préstamos activos (quién tiene qué)</option>
                            <option value="vencidos">Préstamos vencidos</option>
                        </select>
                        <button type="submit" class="btn-primary">Visualizar</button>
                    </form>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <!-- Los encabezados cambiarán con PHP según el reporte seleccionado -->
                                <th>Identificación / Folio</th>
                                <th>Descripción / Solicitante</th>
                                <th>Estado / Condición</th>
                                <th>Fecha Relevante</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Resultados de la consulta -->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script src="../../js/app.js"></script>
</body>
</html>