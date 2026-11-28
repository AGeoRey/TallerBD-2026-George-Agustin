<?php
$titulo = 'Solicitantes';
$pagina_actual = 'solicitantes';
$base = '../../';

require_once $base . 'includes/auth.php';
require_once $base . 'includes/conexion.php';

$mensaje = '';
$error = '';

if (($_GET['ok'] ?? '') === 'registrado') {
    $mensaje = 'Solicitante registrado correctamente.';
} elseif (($_GET['ok'] ?? '') === 'actualizado') {
    $mensaje = 'Datos del solicitante actualizados.';
} elseif (($_GET['ok'] ?? '') === 'eliminado') {
    $mensaje = 'Solicitante eliminado.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    requerirAdmin($base);
    try {
        $stmt = $pdo->prepare('DELETE FROM solicitantes WHERE id_solicitante = ?');
        $stmt->execute([(int) $_POST['eliminar_id']]);
        if ($stmt->rowCount() > 0) {
            header('Location: solicitantes.php?ok=eliminado');
            exit;
        }
        $error = 'El solicitante seleccionado ya no existe.';
    } catch (PDOException $e) {
        $codigo = $e->errorInfo[1] ?? 0;
        if ($codigo == 1451) {
            $error = 'No se puede eliminar porque tiene préstamos registrados.';
        } else {
            $error = 'Ocurrió un error al eliminar el solicitante.';
        }
    }
}

$departamentos = $pdo->query('SELECT id_departamento, nombre FROM departamentos ORDER BY nombre')->fetchAll();

$buscar = trim($_GET['buscar'] ?? '');
$id_departamento = $_GET['id_departamento'] ?? '';

$sql = 'SELECT s.id_solicitante, s.numero_institucional, s.nombre, d.nombre AS departamento
        FROM solicitantes s
        INNER JOIN departamentos d ON s.id_departamento = d.id_departamento
        WHERE 1=1';
$params = [];

if ($buscar !== '') {
    $sql .= ' AND (s.nombre LIKE ? OR s.numero_institucional LIKE ?)';
    $params[] = '%' . $buscar . '%';
    $params[] = '%' . $buscar . '%';
}

if ($id_departamento !== '') {
    $sql .= ' AND s.id_departamento = ?';
    $params[] = (int) $id_departamento;
}

$sql .= ' ORDER BY s.nombre';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$solicitantes = $stmt->fetchAll();

require $base . 'includes/header.php';
?>
            <h1>Directorio de Solicitantes</h1>

            <?php if ($mensaje !== ''): ?>
                <div class="alerta alerta-exito"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="panel">
                <div class="toolbar">
                    <form action="solicitantes.php" method="GET" class="filters">
                        <input type="text" name="buscar" placeholder="Buscar nombre o número..." class="input-search" value="<?= htmlspecialchars($buscar) ?>">
                        <select name="id_departamento" class="select-filter">
                            <option value="">Todos los departamentos</option>
                            <?php foreach ($departamentos as $dep): ?>
                                <option value="<?= $dep['id_departamento'] ?>"<?= (string) $dep['id_departamento'] === (string) $id_departamento ? ' selected' : '' ?>>
                                    <?= htmlspecialchars($dep['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn-secondary">Filtrar</button>
                    </form>
                    <?php if (esAdmin()): ?>
                        <a href="registrar.php" class="btn-primary btn-add">+ Registrar Solicitante</a>
                    <?php endif; ?>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Número Institucional</th>
                                <th>Nombre Completo</th>
                                <th>Departamento</th>
                                <?php if (esAdmin()): ?>
                                    <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($solicitantes)): ?>
                                <tr><td colspan="<?= esAdmin() ? 4 : 3 ?>">Sin registros.</td></tr>
                            <?php else: ?>
                                <?php foreach ($solicitantes as $fila): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($fila['numero_institucional']) ?></td>
                                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                        <td><?= htmlspecialchars($fila['departamento']) ?></td>
                                        <?php if (esAdmin()): ?>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="registrar.php?id=<?= $fila['id_solicitante'] ?>" class="action-link">Editar</a>
                                                    <form action="solicitantes.php" method="POST" onsubmit="return confirm('¿Eliminar este solicitante?');">
                                                        <input type="hidden" name="eliminar_id" value="<?= $fila['id_solicitante'] ?>">
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