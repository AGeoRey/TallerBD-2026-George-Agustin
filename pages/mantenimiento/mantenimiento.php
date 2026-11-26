<?php
$titulo = 'Mantenimiento';
$pagina_actual = 'mantenimiento';
$base = '../../';

require_once $base . 'includes/auth.php';
require_once $base . 'includes/conexion.php';

$mensaje = '';

if (($_GET['ok'] ?? '') === 'registrado') {
    $mensaje = 'Falla reportada y equipo enviado a mantenimiento.';
} elseif (($_GET['ok'] ?? '') === 'finalizado') {
    $mensaje = 'Revisión técnica finalizada correctamente.';
}

$buscar = trim($_GET['buscar'] ?? '');
$estado_mantenimiento = $_GET['estado_mantenimiento'] ?? '';

$sql = 'SELECT bm.id_mantenimiento, bm.id_inventario, e.descripcion AS equipo,
               bm.fecha_reporte, bm.descripcion_falla, bm.fecha_reparacion
        FROM bitacora_mantenimiento bm
        INNER JOIN equipos e ON bm.id_inventario = e.id_inventario
        WHERE 1=1';
$params = [];

if ($buscar !== '') {
    $sql .= ' AND (bm.id_inventario LIKE ? OR e.descripcion LIKE ? OR bm.descripcion_falla LIKE ?)';
    $like = '%' . $buscar . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if ($estado_mantenimiento === 'En revision') {
    $sql .= ' AND bm.fecha_reparacion IS NULL';
} elseif ($estado_mantenimiento === 'Cerrado') {
    $sql .= ' AND bm.fecha_reparacion IS NOT NULL';
}

$sql .= ' ORDER BY (bm.fecha_reparacion IS NOT NULL) ASC, bm.fecha_reporte DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reportes = $stmt->fetchAll();

require $base . 'includes/header.php';
?>
            <h1>Bitácora de Mantenimiento</h1>

            <?php if ($mensaje !== ''): ?>
                <div class="alerta alerta-exito"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <div class="panel">
                <div class="toolbar">
                    <form action="mantenimiento.php" method="GET" class="filters">
                        <input type="text" name="buscar" placeholder="Buscar por ID o falla..." class="input-search" value="<?= htmlspecialchars($buscar) ?>">
                        <select name="estado_mantenimiento" class="select-filter">
                            <option value="">Todos los estados</option>
                            <option value="En revision"<?= $estado_mantenimiento === 'En revision' ? ' selected' : '' ?>>En revisión</option>
                            <option value="Cerrado"<?= $estado_mantenimiento === 'Cerrado' ? ' selected' : '' ?>>Cerrado</option>
                        </select>
                        <button type="submit" class="btn-secondary">Filtrar</button>
                    </form>
                    <?php if (esAdmin()): ?>
                        <a href="registrar.php" class="btn-primary btn-add">+ Reportar Falla</a>
                    <?php endif; ?>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Equipo</th>
                                <th>Fecha de Reporte</th>
                                <th>Descripción de Falla</th>
                                <th>Fecha de Reparación</th>
                                <th>Estado</th>
                                <?php if (esAdmin()): ?>
                                    <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reportes)): ?>
                                <tr><td colspan="<?= esAdmin() ? 7 : 6 ?>">Sin registros de mantenimiento.</td></tr>
                            <?php else: ?>
                                <?php foreach ($reportes as $fila): ?>
                                    <?php $abierto = $fila['fecha_reparacion'] === null; ?>
                                    <tr>
                                        <td><?= $fila['id_mantenimiento'] ?></td>
                                        <td><?= htmlspecialchars($fila['id_inventario'] . ' - ' . $fila['equipo']) ?></td>
                                        <td><?= htmlspecialchars($fila['fecha_reporte']) ?></td>
                                        <td><?= htmlspecialchars($fila['descripcion_falla']) ?></td>
                                        <td><?= $abierto ? '-' : htmlspecialchars($fila['fecha_reparacion']) ?></td>
                                        <td><?= $abierto ? 'En revisión' : 'Cerrado' ?></td>
                                        <?php if (esAdmin()): ?>
                                            <td>
                                                <?php if ($abierto): ?>
                                                    <div class="table-actions">
                                                        <a href="finalizar.php?id=<?= $fila['id_mantenimiento'] ?>" class="action-link">Finalizar</a>
                                                    </div>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
<?php
require $base . 'includes/footer.php';
?>