<?php
$titulo = 'Registrar Equipo';
$pagina_actual = 'equipos';
$base = '../../';
require $base . 'includes/header.php';
?>
            <h1>Datos del Equipo</h1>
            
            <div class="panel form-panel">
                <form action="equipos.php" method="POST">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="id_inventario">Identificación (ID Inventario)</label>
                            <input type="text" id="id_inventario" name="id_inventario" maxlength="50" required>
                        </div>

                        <div class="form-group">
                            <label for="id_categoria">Categoría</label>
                            <select id="id_categoria" name="id_categoria" required>
                                <option value="">Seleccione una categoría...</option>
                                <!-- Opciones cargadas desde la tabla categorias -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_marca">Marca</label>
                            <select id="id_marca" name="id_marca" required>
                                <option value="">Seleccione una marca...</option>
                                <!-- Opciones cargadas desde la tabla marcas -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="condicion_actual">Condición</label>
                            <select id="condicion_actual" name="condicion_actual" required>
                                <option value="Disponible">Disponible</option>
                                <option value="Mantenimiento">Mantenimiento</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="descripcion">Descripción del equipo</label>
                        <textarea id="descripcion" name="descripcion" rows="3" maxlength="200" required></textarea>
                    </div>

                    <div class="form-actions">
                        <a href="equipos.php" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
<?php
require $base . 'includes/footer.php';
?>