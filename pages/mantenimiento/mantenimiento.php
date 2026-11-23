<?php
$titulo = 'Mantenimiento';
$pagina_actual = 'mantenimiento';
$base = '../../';
require $base . 'includes/header.php';
?>
            <h1>Bitácora de Mantenimiento</h1>
            
            <div class="panel">
                <div class="toolbar">
                    <form action="mantenimiento.php" method="GET" class="filters">
                        <input type="text" name="buscar" placeholder="Buscar por ID o falla..." class="input-search">
                        <select name="estado_mantenimiento" class="select-filter">
                            <option value="">Todos los estados</option>
                            <option value="En revision">En revisión</option>
                            <option value="Cerrado">Cerrado</option>
                        </select>
                        <button type="submit" class="btn-secondary">Filtrar</button>
                    </form>
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
<?php
require $base . 'includes/footer.php';
?>