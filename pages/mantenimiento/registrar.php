<?php
$titulo = 'Reportar Falla';
$pagina_actual = 'mantenimiento';
$base = '../../';

require_once $base . 'includes/auth.php';
requerirAdmin($base);

require_once $base . 'includes/conexion.php';

$error = '';
$id_inventario = trim($_POST['id_inventario'] ?? $_GET['id'] ?? '');
$fecha_reporte = $_POST['fecha_reporte'] ?? date('Y-m-d\TH:i');
$descripcion_falla = trim($_POST['descripcion_falla'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($id_inventario === '' || $fecha_reporte === '' || $descripcion_falla === '') {
        $error = 'Todos los campos son obligatorios.';
    } else {
        $fecha_reporte_sql = str_replace('T', ' ', $fecha_reporte);
        if (strlen($fecha_reporte_sql) === 16) {
            $fecha_reporte_sql .= ':00';
        }

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare('SELECT condicion_actual FROM equipos WHERE id_inventario = ? FOR UPDATE');
            $stmt->execute([$id_inventario]);
            $equipo = $stmt->fetch();

            if (!$equipo || $equipo['condicion_actual'] !== 'Disponible') {
                $pdo->rollBack();
                $error = 'Solo se pueden enviar a mantenimiento equipos que estén disponibles.';
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO bitacora_mantenimiento (id_inventario, fecha_reporte, descripcion_falla)
                     VALUES (?, ?, ?)'
                );
                $stmt->execute([
                    $id_inventario,
                    $fecha_reporte_sql,
                    $descripcion_falla,
                ]);

                $stmt = $pdo->prepare("UPDATE equipos SET condicion_actual = 'Mantenimiento' WHERE id_inventario = ?");
                $stmt->execute([$id_inventario]);

                $pdo->commit();
                header('Location: mantenimiento.php?ok=registrado');
                exit;
            }
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $error = 'No se pudo registrar el mantenimiento.';
        }
    }
}

$equipos = $pdo->query("SELECT id_inventario, descripcion FROM equipos WHERE condicion_actual = 'Disponible' ORDER BY id_inventario")->fetchAll();

require $base . 'includes/header.php';
?>
            <h1>Reportar Falla de Equipo</h1>

            <?php if ($error !== ''): ?>
                <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="panel form-panel">
                <form action="registrar.php" method="POST">

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="id_inventario">Equipo</label>
                            <select id="id_inventario" name="id_inventario" required>
                                <option value="">Seleccione un equipo disponible...</option>
                                <?php foreach ($equipos as $eq): ?>
                                    <option value="<?= htmlspecialchars($eq['id_inventario']) ?>"<?= $eq['id_inventario'] === $id_inventario ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($eq['id_inventario'] . ' - ' . $eq['descripcion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_reporte">Fecha de Reporte</label>
                            <input type="datetime-local" id="fecha_reporte" name="fecha_reporte" value="<?= htmlspecialchars($fecha_reporte) ?>" required>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="descripcion_falla">Descripción de la Falla</label>
                        <textarea id="descripcion_falla" name="descripcion_falla" rows="4" placeholder="Detalle el problema técnico detectado en el dispositivo..." required><?= htmlspecialchars($descripcion_falla) ?></textarea>
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