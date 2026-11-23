<?php
$titulo = 'Disponibilidad';
$pagina_actual = 'disponibilidad';
$base = '../../';
require $base . 'includes/header.php';
?>
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
<?php
require $base . 'includes/footer.php';
?>