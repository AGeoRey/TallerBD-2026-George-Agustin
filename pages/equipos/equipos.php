<?php
$titulo = 'Equipos';
$pagina_actual = 'equipos';
$base = '../../';

require_once $base . 'includes/auth.php';
require_once $base . 'includes/conexion.php';

$mensaje = '';
$error = '';

if (($_GET['ok'] ?? '') === 'registrado') {
    $mensaje = 'Equipo registrado correctamente.';
} elseif (($_GET['ok'] ?? '') === 'actualizado') {
    $mensaje = 'Datos del equipo actualizados.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    requerirAdmin($base);
    try {
        $stmt = $pdo->prepare('DELETE FROM equipos WHERE id_inventario = ?');
        $stmt->execute([$_POST['eliminar_id']]);
        $mensaje = 'Equipo eliminado.';
    } catch (PDOException $e) {
        $codigo = $e->errorInfo[1] ?? 0;
        if ($codigo == 1451) {
            $error = 'No se puede eliminar porque tiene historial registrado. Puedes darlo de baja desde Editar.';
        } else {
            $error = 'Ocurrió un error al eliminar el equipo.';
        }
    }
}

$buscar = trim($_GET['buscar'] ?? '');
$condicion = $_GET['condicion'] ?? '';

$sql = 'SELECT e.id_inventario, e.descripcion, e.condicion_actual,
               c.nombre AS categoria, m.nombre AS marca
        FROM equipos e
        INNER JOIN categorias c ON e.id_categoria = c.id_categoria
        INNER JOIN marcas m ON e.id_marca = m.id_marca
        WHERE 1=1';
$params = [];

if ($buscar !== '') {
    $sql .= ' AND (e.id_inventario LIKE ? OR e.descripcion LIKE ? OR c.nombre LIKE ? OR m.nombre LIKE ?)';
    $like = '%' . $buscar . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if ($condicion !== '') {
    $sql .= ' AND e.condicion_actual = ?';
    $params[] = $condicion;
}

$sql .= ' ORDER BY e.id_inventario';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$equipos = $stmt->fetchAll();

require $base . 'includes/header.php';
?>
            <h1>Inventario de Equipos</h1>

            <?php if ($mensaje !== ''): ?>
                <div class="alerta alerta-exito"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="panel">
                <div class="toolbar">
                    <form action="equipos.php" method="GET" class="filters">
                        <input type="text" name="buscar" placeholder="Buscar equipo..." class="input-search" value="<?= htmlspecialchars($buscar) ?>">
                        <select name="condicion" class="select-filter">
                            <option value="">Todas las condiciones</option>
                            <option value="Disponible"<?= $condicion === 'Disponible' ? ' selected' : '' ?>>Disponible</option>
                            <option value="Prestado"<?= $condicion === 'Prestado' ? ' selected' : '' ?>>Prestado</option>
                            <option value="Mantenimiento"<?= $condicion === 'Mantenimiento' ? ' selected' : '' ?>>Mantenimiento</option>
                            <option value="Baja"<?= $condicion === 'Baja' ? ' selected' : '' ?>>Baja</option>
                        </select>
                        <button type="submit" class="btn-secondary">Filtrar</button>
                    </form>
                    <?php if (esAdmin()): ?>
                        <a href="registrar.php" class="btn-primary btn-add">+ Registrar Equipo</a>
                    <?php endif; ?>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID Inventario</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th>Marca</th>
                                <th>Condición</th>
                                <?php if (esAdmin()): ?>
                                    <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($equipos)): ?>
                                <tr><td colspan="<?= esAdmin() ? 6 : 5 ?>">Sin registros.</td></tr>
                            <?php else: ?>
                                <?php foreach ($equipos as $fila): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($fila['id_inventario']) ?></td>
                                        <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                                        <td><?= htmlspecialchars($fila['categoria']) ?></td>
                                        <td><?= htmlspecialchars($fila['marca']) ?></td>
                                        <td><?= htmlspecialchars($fila['condicion_actual']) ?></td>
                                        <?php if (esAdmin()): ?>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="registrar.php?id=<?= urlencode($fila['id_inventario']) ?>" class="action-link">Editar</a>
                                                    <?php if ($fila['condicion_actual'] === 'Disponible'): ?>
                                                        <a href="../mantenimiento/registrar.php?id=<?= urlencode($fila['id_inventario']) ?>" class="action-link">Reportar falla</a>
                                                    <?php endif; ?>
                                                    <form action="equipos.php" method="POST" onsubmit="return confirm('¿Eliminar este equipo?');">
                                                        <input type="hidden" name="eliminar_id" value="<?= htmlspecialchars($fila['id_inventario']) ?>">
                                                        <button type="submit" class="action-link danger">Eliminar</button>
                                                    </form>
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