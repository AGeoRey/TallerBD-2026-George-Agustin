<?php
$titulo = 'Devolución de Equipo';
$pagina_actual = 'prestamos';
$base = '../../';

require_once $base . 'includes/auth.php';
requerirAdmin($base);

require_once $base . 'includes/conexion.php';

$id_prestamo = (int) ($_GET['id'] ?? $_POST['id_prestamo'] ?? 0);

if ($id_prestamo <= 0) {
    header('Location: prestamos.php');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT p.id_prestamo, p.id_inventario, p.fecha_prestamo,
            e.descripcion AS equipo,
            s.numero_institucional, s.nombre AS solicitante,
            u.username AS entregado_por
     FROM prestamos p
     INNER JOIN equipos e ON p.id_inventario = e.id_inventario
     INNER JOIN solicitantes s ON p.id_solicitante = s.id_solicitante
     INNER JOIN usuarios_app u ON p.id_usuario_entrega = u.id_usuario
     WHERE p.id_prestamo = ? AND p.fecha_recepcion IS NULL'
);
$stmt->execute([$id_prestamo]);
$prestamo = $stmt->fetch();

if (!$prestamo) {
    header('Location: prestamos.php');
    exit;
}

$error = '';
$fecha_recepcion = $_POST['fecha_recepcion'] ?? date('Y-m-d\TH:i');
$nuevo_estado = $_POST['nuevo_estado'] ?? 'Disponible';
$condicion_regreso = trim($_POST['condicion_regreso'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($fecha_recepcion === '' || $condicion_regreso === '' || !in_array($nuevo_estado, ['Disponible', 'Mantenimiento'], true)) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        $fecha_recepcion_sql = str_replace('T', ' ', $fecha_recepcion);
        if (strlen($fecha_recepcion_sql) === 16) {
            $fecha_recepcion_sql .= ':00';
        }

        if ($fecha_recepcion_sql < $prestamo['fecha_prestamo']) {
            $error = 'La fecha de recepción no puede ser anterior a la fecha del préstamo.';
        } else {
            try {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare('SELECT id_inventario, fecha_recepcion FROM prestamos WHERE id_prestamo = ? FOR UPDATE');
                $stmt->execute([$id_prestamo]);
                $actual = $stmt->fetch();

                if (!$actual || $actual['fecha_recepcion'] !== null) {
                    $pdo->rollBack();
                    $error = 'Este préstamo ya fue devuelto anteriormente.';
                } else {
                    $stmt = $pdo->prepare(
                        'UPDATE prestamos
                         SET id_usuario_recepcion = ?, fecha_recepcion = ?, condicion_regreso = ?
                         WHERE id_prestamo = ?'
                    );
                    $stmt->execute([
                        $_SESSION['id_usuario'],
                        $fecha_recepcion_sql,
                        $condicion_regreso,
                        $id_prestamo,
                    ]);

                    $stmt = $pdo->prepare('UPDATE equipos SET condicion_actual = ? WHERE id_inventario = ?');
                    $stmt->execute([$nuevo_estado, $actual['id_inventario']]);

                    if ($nuevo_estado === 'Mantenimiento') {
                        $stmt = $pdo->prepare(
                            'INSERT INTO bitacora_mantenimiento (id_inventario, fecha_reporte, descripcion_falla)
                             VALUES (?, ?, ?)'
                        );
                        $stmt->execute([
                            $actual['id_inventario'],
                            $fecha_recepcion_sql,
                            $condicion_regreso,
                        ]);
                    }

                    $pdo->commit();
                    header('Location: prestamos.php?ok=devuelto');
                    exit;
                }
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $error = 'No se pudo registrar la devolución.';
            }
        }
    }
}

require $base . 'includes/header.php';
?>
            <h1>Recepción de Equipo</h1>

            <?php if ($error !== ''): ?>
                <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="panel form-panel">
                <form action="devolucion.php" method="POST">
                    <input type="hidden" name="id_prestamo" value="<?= $prestamo['id_prestamo'] ?>">

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="info_equipo">Equipo Prestado</label>
                            <input type="text" id="info_equipo" value="<?= htmlspecialchars($prestamo['id_inventario'] . ' - ' . $prestamo['equipo']) ?>" disabled>
                        </div>

                        <div class="form-group full-width">
                            <label for="info_solicitante">Solicitante</label>
                            <input type="text" id="info_solicitante" value="<?= htmlspecialchars($prestamo['numero_institucional'] . ' - ' . $prestamo['solicitante']) ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="info_usuario_entrega">Entregado por</label>
                            <input type="text" id="info_usuario_entrega" value="<?= htmlspecialchars($prestamo['entregado_por']) ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="info_usuario_recepcion">Recibido por</label>
                            <input type="text" id="info_usuario_recepcion" value="<?= htmlspecialchars($_SESSION['username']) ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="fecha_recepcion">Fecha de Recepción</label>
                            <input type="datetime-local" id="fecha_recepcion" name="fecha_recepcion" value="<?= htmlspecialchars($fecha_recepcion) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="nuevo_estado">Condición del equipo al recibir</label>
                            <select id="nuevo_estado" name="nuevo_estado" required>
                                <option value="Disponible"<?= $nuevo_estado === 'Disponible' ? ' selected' : '' ?>>Buen estado (Pasar a Disponible)</option>
                                <option value="Mantenimiento"<?= $nuevo_estado === 'Mantenimiento' ? ' selected' : '' ?>>Falla reportada (Pasar a Mantenimiento)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="condicion_regreso">Condiciones de Regreso / Observaciones</label>
                        <textarea id="condicion_regreso" name="condicion_regreso" rows="4" placeholder="Describa si el equipo presenta algún daño, fallas o si se devuelve incompleto..." required><?= htmlspecialchars($condicion_regreso) ?></textarea>
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