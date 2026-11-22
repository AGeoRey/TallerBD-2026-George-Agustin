<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogos | Control de Préstamos</title>
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
                <li><a href="../consultas/disponibilidad.php">Disponibilidad</a></li>
                <li><a href="../consultas/historial.php">Historial</a></li>
                <li><a href="catalogos.php" class="active">Catálogos</a></li>
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
            <h1>Administración de Catálogos</h1>
            
            <div class="dashboard-cards">
                
                <!-- Catálogo de Departamentos -->
                <div class="panel">
                    <h2>Departamentos</h2>
                    <form action="catalogos.php" method="GET" class="toolbar">
                        <input type="text" name="nuevo_departamento" placeholder="Nombre del área..." class="input-search" required>
                        <button type="submit" class="btn-primary btn-add">Agregar</button>
                    </form>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Registros dinámicos -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Catálogo de Categorías -->
                <div class="panel">
                    <h2>Categorías</h2>
                    <form action="catalogos.php" method="GET" class="toolbar">
                        <input type="text" name="nueva_categoria" placeholder="Tipo de equipo..." class="input-search" required>
                        <button type="submit" class="btn-primary btn-add">Agregar</button>
                    </form>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Registros dinámicos -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Catálogo de Marcas -->
                <div class="panel">
                    <h2>Marcas</h2>
                    <form action="catalogos.php" method="GET" class="toolbar">
                        <input type="text" name="nueva_marca" placeholder="Fabricante..." class="input-search" required>
                        <button type="submit" class="btn-primary btn-add">Agregar</button>
                    </form>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Registros dinámicos -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <script src="../../js/app.js"></script>
</body>
</html>