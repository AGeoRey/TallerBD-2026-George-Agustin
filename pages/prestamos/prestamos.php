<?php
$titulo = 'Préstamos';
$pagina_actual = 'prestamos';
$base = '../../';

require_once $base . 'includes/auth.php';
require_once $base . 'includes/conexion.php';

$mensaje = '';

if (($_GET['ok'] ?? '') === 'registrado') {
    $mensaje = 'Préstamo registrado correctamente.';
} elseif (($_GET['ok'] ?? '') === 'devuelto') {
    $mensaje = 'Devolución registrada correctamente.';
}

$buscar = trim($_GET['buscar'] ?? '');
$estado = $_GET['estado'] ?? '';

$sql = 'SELECT p.id_prestamo, p.id_inventario, e.descripcion AS equipo,
               s.numero_institucional, s.nombre AS solicitante,
               u.username AS entregado_por,
               p.fecha_prestamo, p.fecha_prevista_devolucion,
               (p.fecha_prevista_devolucion < NOW()) AS esta_vencido
        FROM prestamos p
        INNER JOIN equipos e ON p.id_inventario = e.id_inventario
        INNER JOIN solicitantes s ON p.id_solicitante = s.id_solicitante
        INNER JOIN usuarios_app u ON p.id_usuario_entrega = u.id_usuario
        WHERE p.fecha_recepcion IS NULL';
$params = [];

if ($buscar !== '') {
    $sql .= ' AND (p.id_inventario LIKE ? OR e.descripcion LIKE ? OR s.nombre LIKE ? OR s.numero_institucional LIKE ?)';
    $like = '%' . $buscar . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if ($estado === 'Vigente') {
    $sql .= ' AND p.fecha_prevista_devolucion >= NOW()';
} elseif ($estado === 'Vencido') {
    $sql .= ' AND p.fecha_prevista_devolucion < NOW()';
}

$sql .= ' ORDER BY p.fecha_prevista_devolucion ASC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$prestamos = $stmt->fetchAll();

require $base . 'includes/header.php';
?>
            <h1>Préstamos Activos</h1>

            <?php if ($mensaje !== ''): ?>
                <div class="alerta alerta-exito"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <div class="panel">
                <div class="toolbar">
                    <form action="prestamos.php" method="GET" class="filters">
                        <input type="text" name="buscar" placeholder="Equipo o solicitante..." class="input-search" value="<?= htmlspecialchars($buscar) ?>">
                        <select name="estado" class="select-filter">
                            <option value="">Todos los estados</option>
                            <option value="Vigente"<?= $estado === 'Vigente' ? ' selected' : '' ?>>Vigente</option>
                            <option value="Vencido"<?= $estado === 'Vencido' ? ' selected' : '' ?>>Vencido</option>
                        </select>
                        <button type="submit" class="btn-secondary">Filtrar</button>
                    </form>
                    <?php if (esAdmin()): ?>
                        <a href="registrar.php" class="btn-primary btn-add">+ Registrar Préstamo</a>
                    <?php endif; ?>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Equipo</th>
                                <th>Solicitante</th>
                                <th>Entregado por</th>
                                <th>Fecha Préstamo</th>
                                <th>Devolución Prevista</th>
                                <th>Estado</th>
                                <?php if (esAdmin()): ?>
                                    <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($prestamos)): ?>
                                <tr><td colspan="<?= esAdmin() ? 8 : 7 ?>">No hay préstamos activos.</td></tr>
                            <?php else: ?>
                                <?php foreach ($prestamos as $fila): ?>
                                    <tr>
                                        <td><?= $fila['id_prestamo'] ?></td>
                                        <td><?= htmlspecialchars($fila['id_inventario'] . ' - ' . $fila['equipo']) ?></td>
                                        <td><?= htmlspecialchars($fila['numero_institucional'] . ' - ' . $fila['solicitante']) ?></td>
                                        <td><?= htmlspecialchars($fila['entregado_por']) ?></td>
                                        <td><?= htmlspecialchars($fila['fecha_prestamo']) ?></td>
                                        <td><?= htmlspecialchars($fila['fecha_prevista_devolucion']) ?></td>
                                        <td><?= $fila['esta_vencido'] ? 'Vencido' : 'Vigente' ?></td>
                                        <?php if (esAdmin()): ?>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="devolucion.php?id=<?= $fila['id_prestamo'] ?>" class="action-link">Devolver</a>
                                                </div>
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