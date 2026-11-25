<?php
$pagina_actual = 'equipos';
$base = '../../';

require_once $base . 'includes/auth.php';
requerirAdmin($base);

require_once $base . 'includes/conexion.php';

$id_original = trim($_GET['id'] ?? $_POST['id_original'] ?? '');
$editando = $id_original !== '';
$error = '';

$equipo = [
    'id_inventario' => '',
    'descripcion' => '',
    'id_categoria' => '',
    'id_marca' => '',
    'condicion_actual' => 'Disponible',
];
$estado_actual_db = 'Disponible';

$categorias = $pdo->query('SELECT id_categoria, nombre FROM categorias ORDER BY nombre')->fetchAll();
$marcas = $pdo->query('SELECT id_marca, nombre FROM marcas ORDER BY nombre')->fetchAll();

if ($editando) {
    $stmt = $pdo->prepare('SELECT id_inventario, descripcion, id_categoria, id_marca, condicion_actual FROM equipos WHERE id_inventario = ?');
    $stmt->execute([$id_original]);
    $fila = $stmt->fetch();

    if (!$fila) {
        header('Location: equipos.php');
        exit;
    }

    $equipo = $fila;
    $estado_actual_db = $fila['condicion_actual'];
}

$puede_cambiar_estado = $editando && ($estado_actual_db === 'Disponible' || $estado_actual_db === 'Baja');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipo['id_inventario'] = $editando ? $id_original : trim($_POST['id_inventario'] ?? '');
    $equipo['descripcion'] = trim($_POST['descripcion'] ?? '');
    $equipo['id_categoria'] = (int) ($_POST['id_categoria'] ?? 0);
    $equipo['id_marca'] = (int) ($_POST['id_marca'] ?? 0);

    if ($puede_cambiar_estado) {
        $condicion_enviada = $_POST['condicion_actual'] ?? $estado_actual_db;
        $equipo['condicion_actual'] = ($condicion_enviada === 'Baja') ? 'Baja' : 'Disponible';
    } else {
        $equipo['condicion_actual'] = $editando ? $estado_actual_db : 'Disponible';
    }

    if ($equipo['id_inventario'] === '' || $equipo['descripcion'] === '' || $equipo['id_categoria'] <= 0 || $equipo['id_marca'] <= 0) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        try {
            if ($editando) {
                $stmt = $pdo->prepare('UPDATE equipos SET descripcion = ?, id_categoria = ?, id_marca = ?, condicion_actual = ? WHERE id_inventario = ?');
                $stmt->execute([
                    $equipo['descripcion'],
                    $equipo['id_categoria'],
                    $equipo['id_marca'],
                    $equipo['condicion_actual'],
                    $id_original,
                ]);
                header('Location: equipos.php?ok=actualizado');
                exit;
            } else {
                $stmt = $pdo->prepare('INSERT INTO equipos (id_inventario, descripcion, id_categoria, id_marca, condicion_actual) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([
                    $equipo['id_inventario'],
                    $equipo['descripcion'],
                    $equipo['id_categoria'],
                    $equipo['id_marca'],
                    'Disponible',
                ]);
                header('Location: equipos.php?ok=registrado');
                exit;
            }
        } catch (PDOException $e) {
            $codigo = $e->errorInfo[1] ?? 0;
            if ($codigo == 1062) {
                $error = 'Ese ID de inventario ya está registrado.';
            } else {
                $error = 'Ocurrió un error al guardar el equipo.';
            }
        }
    }
}

$titulo = $editando ? 'Editar Equipo' : 'Registrar Equipo';

require $base . 'includes/header.php';
?>
            <h1><?= $editando ? 'Editar Equipo' : 'Datos del Equipo' ?></h1>

            <?php if ($error !== ''): ?>
                <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="panel form-panel">
                <form action="registrar.php" method="POST">
                    <?php if ($editando): ?>
                        <input type="hidden" name="id_original" value="<?= htmlspecialchars($id_original) ?>">
                    <?php endif; ?>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="id_inventario">Identificación (ID Inventario)</label>
                            <input type="text" id="id_inventario" name="id_inventario" maxlength="50" value="<?= htmlspecialchars($equipo['id_inventario']) ?>"<?= $editando ? ' disabled' : ' required' ?>>
                        </div>

                        <div class="form-group">
                            <label for="id_categoria">Categoría</label>
                            <select id="id_categoria" name="id_categoria" required>
                                <option value="">Seleccione una categoría...</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id_categoria'] ?>"<?= (int) $cat['id_categoria'] === (int) $equipo['id_categoria'] ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($cat['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_marca">Marca</label>
                            <select id="id_marca" name="id_marca" required>
                                <option value="">Seleccione una marca...</option>
                                <?php foreach ($marcas as $mar): ?>
                                    <option value="<?= $mar['id_marca'] ?>"<?= (int) $mar['id_marca'] === (int) $equipo['id_marca'] ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($mar['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="condicion_actual">Condición</label>
                            <?php if ($puede_cambiar_estado): ?>
                                <select id="condicion_actual" name="condicion_actual" required>
                                    <option value="Disponible"<?= $equipo['condicion_actual'] === 'Disponible' ? ' selected' : '' ?>>Disponible</option>
                                    <option value="Baja"<?= $equipo['condicion_actual'] === 'Baja' ? ' selected' : '' ?>>Baja</option>
                                </select>
                            <?php else: ?>
                                <input type="text" id="condicion_actual" value="<?= htmlspecialchars($equipo['condicion_actual']) ?>" disabled>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="descripcion">Descripción del equipo</label>
                        <textarea id="descripcion" name="descripcion" rows="3" maxlength="200" required><?= htmlspecialchars($equipo['descripcion']) ?></textarea>
                    </div>

                    <div class="form-actions">
                        <a href="equipos.php" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
<?php
require $base . 'includes/footer.php';
?>