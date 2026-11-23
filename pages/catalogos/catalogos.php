<?php
$titulo = 'Catálogos';
$pagina_actual = 'catalogos';
$base = '../../';
require $base . 'includes/header.php';
?>
            <h1>Administración de Catálogos</h1>
            
            <div class="catalogos-grid">
                
                <!-- Catálogo de Departamentos -->
                <div class="panel">
                    <h2>Departamentos</h2>
                    <form action="catalogos.php" method="POST" class="toolbar">
                        <input type="text" name="nuevo_departamento" placeholder="Nombre del área..." class="input-search" maxlength="100" required>
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
                    <form action="catalogos.php" method="POST" class="toolbar">
                        <input type="text" name="nueva_categoria" placeholder="Tipo de equipo..." class="input-search" maxlength="100" required>
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
                    <form action="catalogos.php" method="POST" class="toolbar">
                        <input type="text" name="nueva_marca" placeholder="Fabricante..." class="input-search" maxlength="100" required>
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
<?php
require $base . 'includes/footer.php';
?>