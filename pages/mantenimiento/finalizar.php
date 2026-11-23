<?php
$titulo = 'Finalizar Mantenimiento';
$pagina_actual = 'mantenimiento';
$base = '../../';
require $base . 'includes/header.php';
?>
            <h1>Finalizar Revisión Técnica</h1>
            
            <div class="panel form-panel">
                <form action="mantenimiento.php" method="POST">
                    
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="info_equipo">Equipo en Mantenimiento</label>
                            <input type="text" id="info_equipo" value="[ID] - [Descripción del Equipo]" disabled>
                        </div>

                        <div class="form-group full-width">
                            <label for="info_falla">Falla Reportada</label>
                            <textarea id="info_falla" rows="3" disabled>[Descripción de la falla registrada]</textarea>
                        </div>

                        <div class="form-group">
                            <label for="fecha_reparacion">Fecha de Reparación / Cierre</label>
                            <input type="datetime-local" id="fecha_reparacion" name="fecha_reparacion" required>
                        </div>

                        <div class="form-group">
                            <label for="nuevo_estado">Estado Final del Equipo</label>
                            <select id="nuevo_estado" name="nuevo_estado" required>
                                <option value="Disponible">Reparado (Pasar a Disponible)</option>
                                <option value="Baja">Irreparable (Dar de Baja)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="mantenimiento.php" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Confirmar Cierre</button>
                    </div>
                </form>
            </div>
<?php
require $base . 'includes/footer.php';
?>