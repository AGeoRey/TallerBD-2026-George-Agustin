<?php
$titulo = 'Inicio';
$pagina_actual = 'inicio';
$base = '../';
require $base . 'includes/header.php';
?>
            <h1>Resumen General</h1>
            
            <!-- TARJETAS DE INDICADORES -->
            <div class="dashboard-cards">
                <div class="card">
                    <h3>Total de Equipos</h3>
                    <p class="card-number">-</p>
                </div>
                <div class="card">
                    <h3>Disponibles</h3>
                    <p class="card-number">-</p>
                </div>
                <div class="card">
                    <h3>Prestados</h3>
                    <p class="card-number">-</p>
                </div>
                <div class="card">
                    <h3>En Mantenimiento</h3>
                    <p class="card-number">-</p>
                </div>
            </div>

            <!-- TABLA DE DATOS -->
            <div class="panel">
                <h2>Préstamos Activos</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th>Solicitante</th>
                                <th>Fecha Préstamo</th>
                                <th>Devolución Prevista</th>
                                <th>Estado</th>
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