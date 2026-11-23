<?php
$titulo = 'Reportar Falla';
$pagina_actual = 'mantenimiento';
$base = '../../';
require $base . 'includes/header.php';
?>
            <h1>Reportar Falla de Equipo</h1>
            
            <div class="panel form-panel">
                <form action="mantenimiento.php" method="POST">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="id_inventario">Equipo</label>
                            <select id="id_inventario" name="id_inventario" required>
                                <option value="">Seleccione un equipo disponible...</option>
                                <!-- Opciones cargadas dinámicamente; preseleccionado si llega por URL -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_reporte">Fecha de Reporte</label>
                            <input type="datetime-local" id="fecha_reporte" name="fecha_reporte" required>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="descripcion_falla">Descripción de la Falla</label>
                        <textarea id="descripcion_falla" name="descripcion_falla" rows="4" placeholder="Detalle el problema técnico detectado en el dispositivo..." required></textarea>
                    </div>

                    <div class="form-actions">
                        <a href="mantenimiento.php" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Registrar Mantenimiento</button>
                    </div>
                </form>
            </div>
<?php
require $base . 'includes/footer.php';
?>