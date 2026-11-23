<?php
$titulo = 'Historial';
$pagina_actual = 'historial';
$base = '../../';
require $base . 'includes/header.php';
?>
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
<?php
require $base . 'includes/footer.php';
?>