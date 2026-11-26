<?php
$titulo = 'Finalizar Mantenimiento';
$pagina_actual = 'mantenimiento';
$base = '../../';

require_once $base . 'includes/auth.php';
requerirAdmin($base);

require_once $base . 'includes/conexion.php';

$id_mantenimiento = (int) ($_GET['id'] ?? $_POST['id_mantenimiento'] ?? 0);

if ($id_mantenimiento <= 0) {
    header('Location: mantenimiento.php');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT bm.id_mantenimiento, bm.id_inventario, bm.fecha_reporte, bm.descripcion_falla,
            e.descripcion AS equipo
     FROM bitacora_mantenimiento bm
     INNER JOIN equipos e ON bm.id_inventario = e.id_inventario
     WHERE bm.id_mantenimiento = ? AND bm.fecha_reparacion IS NULL'
);
$stmt->execute([$id_mantenimiento]);
$reporte = $stmt->fetch();

if (!$reporte) {
    header('Location: mantenimiento.php');
    exit;
}

$error = '';
$fecha_reparacion = $_POST['fecha_reparacion'] ?? date('Y-m-d\TH:i');
$nuevo_estado = $_POST['nuevo_estado'] ?? 'Disponible';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($fecha_reparacion === '' || !in_array($nuevo_estado, ['Disponible', 'Baja'], true)) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        $fecha_reparacion_sql = str_replace('T', ' ', $fecha_reparacion);
        if (strlen($fecha_reparacion_sql) === 16) {
            $fecha_reparacion_sql .= ':00';
        }

        if ($fecha_reparacion_sql < $reporte['fecha_reporte']) {
            $error = 'La fecha de reparación no puede ser anterior a la fecha de reporte.';
        } else {
            try {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare('SELECT id_inventario, fecha_reparacion FROM bitacora_mantenimiento WHERE id_mantenimiento = ? FOR UPDATE');
                $stmt->execute([$id_mantenimiento]);
                $actual = $stmt->fetch();

                if (!$actual || $actual['fecha_reparacion'] !== null) {
                    $pdo->rollBack();
                    $error = 'Este mantenimiento ya fue cerrado anteriormente.';
                } else {
                    $stmt = $pdo->prepare('UPDATE bitacora_mantenimiento SET fecha_reparacion = ? WHERE id_mantenimiento = ?');
                    $stmt->execute([$fecha_reparacion_sql, $id_mantenimiento]);

                    $stmt = $pdo->prepare('UPDATE equipos SET condicion_actual = ? WHERE id_inventario = ?');
                    $stmt->execute([$nuevo_estado, $actual['id_inventario']]);

                    $pdo->commit();
                    header('Location: mantenimiento.php?ok=finalizado');
                    exit;
                }
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $error = 'No se pudo finalizar el mantenimiento.';
            }
        }
    }
}

require $base . 'includes/header.php';
?>
            <h1>Finalizar Revisión Técnica</h1>

            <?php if ($error !== ''): ?>
                <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="panel form-panel">
                <form action="finalizar.php" method="POST">
                    <input type="hidden" name="id_mantenimiento" value="<?= $reporte['id_mantenimiento'] ?>">

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="info_equipo">Equipo en Mantenimiento</label>
                            <input type="text" id="info_equipo" value="<?= htmlspecialchars($reporte['id_inventario'] . ' - ' . $reporte['equipo']) ?>" disabled>
                        </div>

                        <div class="form-group full-width">
                            <label for="info_falla">Falla Reportada</label>
                            <textarea id="info_falla" rows="3" disabled><?= htmlspecialchars($reporte['descripcion_falla']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="fecha_reparacion">Fecha de Reparación / Cierre</label>
                            <input type="datetime-local" id="fecha_reparacion" name="fecha_reparacion" value="<?= htmlspecialchars($fecha_reparacion) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="nuevo_estado">Estado Final del Equipo</label>
                            <select id="nuevo_estado" name="nuevo_estado" required>
                                <option value="Disponible"<?= $nuevo_estado === 'Disponible' ? ' selected' : '' ?>>Reparado (Pasar a Disponible)</option>
                                <option value="Baja"<?= $nuevo_estado === 'Baja' ? ' selected' : '' ?>>Irreparable (Dar de Baja)</option>
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