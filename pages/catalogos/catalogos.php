<?php
$titulo = 'Catálogos';
$pagina_actual = 'catalogos';
$base = '../../';

require_once $base . 'includes/conexion.php';

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['nuevo_departamento'])) {
            $nombre = trim($_POST['nuevo_departamento']);
            if ($nombre === '') {
                $error = 'El nombre no puede quedar vacío.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO departamentos (nombre) VALUES (?)');
                $stmt->execute([$nombre]);
                $mensaje = 'Departamento agregado.';
            }
        } elseif (isset($_POST['eliminar_departamento'])) {
            $stmt = $pdo->prepare('DELETE FROM departamentos WHERE id_departamento = ?');
            $stmt->execute([$_POST['eliminar_departamento']]);
            $mensaje = 'Departamento eliminado.';
        } elseif (isset($_POST['nueva_categoria'])) {
            $nombre = trim($_POST['nueva_categoria']);
            if ($nombre === '') {
                $error = 'El nombre no puede quedar vacío.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO categorias (nombre) VALUES (?)');
                $stmt->execute([$nombre]);
                $mensaje = 'Categoría agregada.';
            }
        } elseif (isset($_POST['eliminar_categoria'])) {
            $stmt = $pdo->prepare('DELETE FROM categorias WHERE id_categoria = ?');
            $stmt->execute([$_POST['eliminar_categoria']]);
            $mensaje = 'Categoría eliminada.';
        } elseif (isset($_POST['nueva_marca'])) {
            $nombre = trim($_POST['nueva_marca']);
            if ($nombre === '') {
                $error = 'El nombre no puede quedar vacío.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO marcas (nombre) VALUES (?)');
                $stmt->execute([$nombre]);
                $mensaje = 'Marca agregada.';
            }
        } elseif (isset($_POST['eliminar_marca'])) {
            $stmt = $pdo->prepare('DELETE FROM marcas WHERE id_marca = ?');
            $stmt->execute([$_POST['eliminar_marca']]);
            $mensaje = 'Marca eliminada.';
        }
    } catch (PDOException $e) {
        $codigo = $e->errorInfo[1] ?? 0;
        if ($codigo == 1062) {
            $error = 'Ese nombre ya existe en el catálogo.';
        } elseif ($codigo == 1451) {
            $error = 'No se puede eliminar porque ya está en uso.';
        } else {
            $error = 'Ocurrió un error al procesar la operación.';
        }
    }
}

$departamentos = $pdo->query('SELECT id_departamento, nombre FROM departamentos ORDER BY nombre')->fetchAll();
$categorias = $pdo->query('SELECT id_categoria, nombre FROM categorias ORDER BY nombre')->fetchAll();
$marcas = $pdo->query('SELECT id_marca, nombre FROM marcas ORDER BY nombre')->fetchAll();

require $base . 'includes/header.php';
?>
            <h1>Administración de Catálogos</h1>

            <?php if ($mensaje !== ''): ?>
                <div class="alerta alerta-exito"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="catalogos-grid">

                <!-- Catálogo de Departamentos -->
                <div class="panel">
                    <h2>Departamentos</h2>
                    <form action="catalogos.php" method="POST" class="toolbar">
                        <input type="text" name="nuevo_departamento" placeholder="Nombre del área..." class="input-search" maxlength="100" required>
                        <button type="submit" class="btn-primary btn-add">Agregar</button>
                    </form>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($departamentos)): ?>
                                    <tr><td colspan="2">Sin registros.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($departamentos as $fila): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                            <td>
                                                <div class="table-actions">
                                                    <form action="catalogos.php" method="POST" onsubmit="return confirm('¿Eliminar este departamento?');">
                                                        <input type="hidden" name="eliminar_departamento" value="<?= $fila['id_departamento'] ?>">
                                                        <button type="submit" class="action-link danger">Eliminar</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Catálogo de Categorías -->
                <div class="panel">
                    <h2>Categorías</h2>
                    <form action="catalogos.php" method="POST" class="toolbar">
                        <input type="text" name="nueva_categoria" placeholder="Tipo de equipo..." class="input-search" maxlength="100" required>
                        <button type="submit" class="btn-primary btn-add">Agregar</button>
                    </form>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categorias)): ?>
                                    <tr><td colspan="2">Sin registros.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($categorias as $fila): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                            <td>
                                                <div class="table-actions">
                                                    <form action="catalogos.php" method="POST" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                                        <input type="hidden" name="eliminar_categoria" value="<?= $fila['id_categoria'] ?>">
                                                        <button type="submit" class="action-link danger">Eliminar</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Catálogo de Marcas -->
                <div class="panel">
                    <h2>Marcas</h2>
                    <form action="catalogos.php" method="POST" class="toolbar">
                        <input type="text" name="nueva_marca" placeholder="Fabricante..." class="input-search" maxlength="100" required>
                        <button type="submit" class="btn-primary btn-add">Agregar</button>
                    </form>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($marcas)): ?>
                                    <tr><td colspan="2">Sin registros.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($marcas as $fila): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                            <td>
                                                <div class="table-actions">
                                                    <form action="catalogos.php" method="POST" onsubmit="return confirm('¿Eliminar esta marca?');">
                                                        <input type="hidden" name="eliminar_marca" value="<?= $fila['id_marca'] ?>">
                                                        <button type="submit" class="action-link danger">Eliminar</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
<?php
require $base . 'includes/footer.php';
?>