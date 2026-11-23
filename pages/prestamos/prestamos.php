<?php
$titulo = 'Préstamos';
$pagina_actual = 'prestamos';
$base = '../../';
require $base . 'includes/header.php';
?>
            <h1>Préstamos Activos</h1>
            
            <div class="panel">
                <div class="toolbar">
                    <form action="prestamos.php" method="GET" class="filters">
                        <input type="text" name="buscar" placeholder="Equipo o solicitante..." class="input-search">
                        <select name="estado" class="select-filter">
                            <option value="">Todos los estados</option>
                            <option value="Vigente">Vigente</option>
                            <option value="Vencido">Vencido</option>
                        </select>
                        <button type="submit" class="btn-secondary">Filtrar</button>
                    </form>
                    <a href="registrar.php" class="btn-primary btn-add">+ Registrar Préstamo</a>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Equipo</th>
                                <th>Solicitante</th>
                                <th>Entregado por</th>
                                <th>Fecha Préstamo</th>
                                <th>Devolución Prevista</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Los registros se cargarán dinámicamente con PHP -->
                            <!-- En "Acciones" habrá un botón para redirigir a devolucion.php con el ID del préstamo -->
                        </tbody>
                    </table>
                </div>
            </div>
<?php
require $base . 'includes/footer.php';
?>