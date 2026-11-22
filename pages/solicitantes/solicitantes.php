<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitantes | Control de Préstamos</title>
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
                <li><a href="solicitantes.php" class="active">Solicitantes</a></li>
                <li><a href="../prestamos/prestamos.php">Préstamos</a></li>
                <li><a href="../mantenimiento/mantenimiento.php">Mantenimiento</a></li>
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
            <h1>Directorio de Solicitantes</h1>
            
            <div class="panel">
                <div class="toolbar">
                    <div class="filters">
                        <input type="text" placeholder="Buscar por nombre o número..." class="input-search">
                        <select class="select-filter">
                            <option value="">Todos los departamentos</option>
                            <!-- Opciones cargadas dinámicamente -->
                        </select>
                    </div>
                    <a href="registrar.php" class="btn-primary btn-add">+ Registrar Solicitante</a>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Número Institucional</th>
                                <th>Nombre Completo</th>
                                <th>Departamento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los registros se cargarán dinámicamente con PHP -->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script src="../../js/app.js"></script>
</body>
</html>