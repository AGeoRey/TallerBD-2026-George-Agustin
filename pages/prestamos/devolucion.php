<?php
$titulo = 'Devolución de Equipo';
$pagina_actual = 'prestamos';
$base = '../../';
require $base . 'includes/header.php';
?>
            <h1>Recepción de Equipo</h1>
            
            <div class="panel form-panel">
                <form action="prestamos.php" method="POST">
                    
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="info_equipo">Equipo Prestado</label>
                            <input type="text" id="info_equipo" value="[ID] - [Descripción del Equipo]" disabled>
                        </div>

                        <div class="form-group full-width">
                            <label for="info_solicitante">Solicitante</label>
                            <input type="text" id="info_solicitante" value="[Número Institucional] - [Nombre del Solicitante]" disabled>
                        </div>

                        <div class="form-group">
                            <label for="info_usuario_entrega">Entregado por</label>
                            <input type="text" id="info_usuario_entrega" value="[Usuario que entregó]" disabled>
                        </div>

                        <div class="form-group">
                            <label for="info_usuario_recepcion">Recibido por</label>
                            <input type="text" id="info_usuario_recepcion" value="Usuario Administrador" disabled>
                        </div>

                        <div class="form-group">
                            <label for="fecha_recepcion">Fecha de Recepción</label>
                            <input type="datetime-local" id="fecha_recepcion" name="fecha_recepcion" required>
                        </div>

                        <div class="form-group">
                            <label for="nuevo_estado">Condición del equipo al recibir</label>
                            <select id="nuevo_estado" name="nuevo_estado" required>
                                <option value="Disponible">Buen estado (Pasar a Disponible)</option>
                                <option value="Mantenimiento">Falla reportada (Pasar a Mantenimiento)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="condicion_regreso">Condiciones de Regreso / Observaciones</label>
                        <textarea id="condicion_regreso" name="condicion_regreso" rows="4" placeholder="Describa si el equipo presenta algún daño, fallas o si se devuelve incompleto..." required></textarea>
                    </div>

                    <div class="form-actions">
                        <a href="prestamos.php" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Confirmar Devolución</button>
                    </div>
                </form>
            </div>
<?php
require $base . 'includes/footer.php';
?>