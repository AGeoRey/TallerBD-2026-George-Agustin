<?php
$titulo = 'Registrar Préstamo';
$pagina_actual = 'prestamos';
$base = '../../';
require $base . 'includes/header.php';
?>
            <h1>Registro de Préstamo</h1>
            
            <div class="panel form-panel">
                <form action="prestamos.php" method="POST">
                    
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="id_inventario">Equipo a Prestar</label>
                            <select id="id_inventario" name="id_inventario" required>
                                <option value="">Seleccione un equipo disponible...</option>
                                <!-- Opciones cargadas dinámicamente -->
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label for="id_solicitante">Solicitante</label>
                            <select id="id_solicitante" name="id_solicitante" required>
                                <option value="">Seleccione al personal institucional...</option>
                                <!-- Opciones cargadas dinámicamente -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_prestamo">Fecha de Préstamo</label>
                            <input type="datetime-local" id="fecha_prestamo" name="fecha_prestamo" required>
                        </div>

                        <div class="form-group">
                            <label for="fecha_prevista_devolucion">Devolución Prevista</label>
                            <input type="datetime-local" id="fecha_prevista_devolucion" name="fecha_prevista_devolucion" required>
                        </div>

                        <div class="form-group full-width">
                            <label for="usuario_entrega">Registrado por</label>
                            <input type="text" id="usuario_entrega" value="Usuario Administrador" disabled>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="prestamos.php" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Confirmar Entrega</button>
                    </div>
                </form>
            </div>
<?php
require $base . 'includes/footer.php';
?>