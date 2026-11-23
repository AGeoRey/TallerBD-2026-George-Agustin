<?php
$titulo = 'Equipos';
$pagina_actual = 'equipos';
$base = '../../';
require $base . 'includes/header.php';
?>
            <h1>Inventario de Equipos</h1>
            
            <div class="panel">
                <div class="toolbar">
                    <form action="equipos.php" method="GET" class="filters">
                        <input type="text" name="buscar" placeholder="Buscar equipo..." class="input-search">
                        <select name="condicion" class="select-filter">
                            <option value="">Todas las condiciones</option>
                            <option value="Disponible">Disponible</option>
                            <option value="Prestado">Prestado</option>
                            <option value="Mantenimiento">Mantenimiento</option>
                            <option value="Baja">Baja</option>
                        </select>
                        <button type="submit" class="btn-secondary">Filtrar</button>
                    </form>
                    <a href="registrar.php" class="btn-primary btn-add">+ Registrar Equipo</a>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID Inventario</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th>Marca</th>
                                <th>Condición</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los registros se cargarán dinámicamente con PHP -->
                        </tbody>
                    </table>
                </div>
            </div>
<?php
require $base . 'includes/footer.php';
?>