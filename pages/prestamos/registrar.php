<?php
$titulo = 'Registrar Préstamo';
$pagina_actual = 'prestamos';
$base = '../../';

require_once $base . 'includes/auth.php';
requerirAdmin($base);

require_once $base . 'includes/conexion.php';

$error = '';
$id_inventario = trim($_POST['id_inventario'] ?? '');
$id_solicitante = (int) ($_POST['id_solicitante'] ?? 0);
$fecha_prestamo = $_POST['fecha_prestamo'] ?? date('Y-m-d\TH:i');
$fecha_prevista = $_POST['fecha_prevista_devolucion'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($id_inventario === '' || $id_solicitante <= 0 || $fecha_prestamo === '' || $fecha_prevista === '') {
        $error = 'Todos los campos son obligatorios.';
    } else {
        $fecha_prestamo_sql = str_replace('T', ' ', $fecha_prestamo);
        if (strlen($fecha_prestamo_sql) === 16) {
            $fecha_prestamo_sql .= ':00';
        }

        $fecha_prevista_sql = str_replace('T', ' ', $fecha_prevista);
        if (strlen($fecha_prevista_sql) === 16) {
            $fecha_prevista_sql .= ':00';
        }

        if ($fecha_prevista_sql < $fecha_prestamo_sql) {
            $error = 'La fecha prevista de devolución no puede ser anterior a la fecha del préstamo.';
        } else {
            try {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare('SELECT condicion_actual FROM equipos WHERE id_inventario = ? FOR UPDATE');
                $stmt->execute([$id_inventario]);
                $equipo = $stmt->fetch();

                if (!$equipo || $equipo['condicion_actual'] !== 'Disponible') {
                    $pdo->rollBack();
                    $error = 'El equipo seleccionado ya no está disponible para préstamo.';
                } else {
                    $stmt = $pdo->prepare(
                        'INSERT INTO prestamos (id_inventario, id_solicitante, id_usuario_entrega, fecha_prestamo, fecha_prevista_devolucion)
                         VALUES (?, ?, ?, ?, ?)'
                    );
                    $stmt->execute([
                        $id_inventario,
                        $id_solicitante,
                        $_SESSION['id_usuario'],
                        $fecha_prestamo_sql,
                        $fecha_prevista_sql,
                    ]);

                    $stmt = $pdo->prepare("UPDATE equipos SET condicion_actual = 'Prestado' WHERE id_inventario = ?");
                    $stmt->execute([$id_inventario]);

                    $pdo->commit();
                    header('Location: prestamos.php?ok=registrado');
                    exit;
                }
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $error = 'No se pudo registrar el préstamo. Revisa los datos ingresados.';
            }
        }
    }
}

$equipos = $pdo->query("SELECT id_inventario, descripcion FROM equipos WHERE condicion_actual = 'Disponible' ORDER BY id_inventario")->fetchAll();
$solicitantes = $pdo->query('SELECT id_solicitante, numero_institucional, nombre FROM solicitantes ORDER BY nombre')->fetchAll();

require $base . 'includes/header.php';
?>
            <h1>Registro de Préstamo</h1>

            <?php if ($error !== ''): ?>
                <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="panel form-panel">
                <form action="registrar.php" method="POST">

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="id_inventario">Equipo a Prestar</label>
                            <select id="id_inventario" name="id_inventario" required>
                                <option value="">Seleccione un equipo disponible...</option>
                                <?php foreach ($equipos as $eq): ?>
                                    <option value="<?= htmlspecialchars($eq['id_inventario']) ?>"<?= $eq['id_inventario'] === $id_inventario ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($eq['id_inventario'] . ' - ' . $eq['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group full-width">
                            <label for="id_solicitante">Solicitante</label>
                            <select id="id_solicitante" name="id_solicitante" required>
                                <option value="">Seleccione al personal institucional...</option>
                                <?php foreach ($solicitantes as $sol): ?>
                                    <option value="<?= $sol['id_solicitante'] ?>"<?= (int) $sol['id_solicitante'] === $id_solicitante ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($sol['numero_institucional'] . ' - ' . $sol['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_prestamo">Fecha de Préstamo</label>
                            <input type="datetime-local" id="fecha_prestamo" name="fecha_prestamo" value="<?= htmlspecialchars($fecha_prestamo) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="fecha_prevista_devolucion">Devolución Prevista</label>
                            <input type="datetime-local" id="fecha_prevista_devolucion" name="fecha_prevista_devolucion" value="<?= htmlspecialchars($fecha_prevista) ?>" required>
                        </div>

                        <div class="form-group full-width">
                            <label for="usuario_entrega">Registrado por</label>
                            <input type="text" id="usuario_entrega" value="<?= htmlspecialchars($_SESSION['username']) ?>" disabled>
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