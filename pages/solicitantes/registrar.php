<?php
$titulo = 'Datos del Solicitante';
$pagina_actual = 'solicitantes';
$base = '../../';
require $base . 'includes/header.php';
?>
            <h1>Datos del Solicitante</h1>
            
            <div class="panel form-panel">
                <form action="solicitantes.php" method="POST">
                    
                    <div class="form-group full-width">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" maxlength="150" required>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="numero_institucional">Número Institucional</label>
                            <input type="text" id="numero_institucional" name="numero_institucional" maxlength="50" required>
                        </div>

                        <div class="form-group">
                            <label for="id_departamento">Departamento</label>
                            <select id="id_departamento" name="id_departamento" required>
                                <option value="">Seleccione un departamento...</option>
                                <!-- Opciones cargadas desde la tabla departamentos -->
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="solicitantes.php" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
<?php
require $base . 'includes/footer.php';
?>