<?php
$titulo = 'Solicitantes';
$pagina_actual = 'solicitantes';
$base = '../../';
require $base . 'includes/header.php';
?>
            <h1>Directorio de Solicitantes</h1>
            
            <div class="panel">
                <div class="toolbar">
                    <form action="solicitantes.php" method="GET" class="filters">
                        <input type="text" name="buscar" placeholder="Buscar por nombre o número..." class="input-search">
                        <select name="id_departamento" class="select-filter">
                            <option value="">Todos los departamentos</option>
                            <!-- Opciones cargadas dinámicamente -->
                        </select>
                        <button type="submit" class="btn-secondary">Filtrar</button>
                    </form>
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
<?php
require $base . 'includes/footer.php';
?>