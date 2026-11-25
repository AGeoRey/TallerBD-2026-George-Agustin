<?php
$pagina_actual = 'solicitantes';
$base = '../../';

require_once $base . 'includes/auth.php';
requerirAdmin($base);

require_once $base . 'includes/conexion.php';

$id = (int) ($_GET['id'] ?? $_POST['id_solicitante'] ?? 0);
$error = '';
$solicitante = [
    'numero_institucional' => '',
    'nombre' => '',
    'id_departamento' => '',
];

$departamentos = $pdo->query('SELECT id_departamento, nombre FROM departamentos ORDER BY nombre')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $solicitante['nombre'] = trim($_POST['nombre'] ?? '');
    $solicitante['numero_institucional'] = trim($_POST['numero_institucional'] ?? '');
    $solicitante['id_departamento'] = (int) ($_POST['id_departamento'] ?? 0);

    if ($solicitante['nombre'] === '' || $solicitante['numero_institucional'] === '' || $solicitante['id_departamento'] <= 0) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        try {
            if ($id > 0) {
                $stmt = $pdo->prepare('UPDATE solicitantes SET numero_institucional = ?, nombre = ?, id_departamento = ? WHERE id_solicitante = ?');
                $stmt->execute([
                    $solicitante['numero_institucional'],
                    $solicitante['nombre'],
                    $solicitante['id_departamento'],
                    $id,
                ]);
                header('Location: solicitantes.php?ok=actualizado');
                exit;
            } else {
                $stmt = $pdo->prepare('INSERT INTO solicitantes (numero_institucional, nombre, id_departamento) VALUES (?, ?, ?)');
                $stmt->execute([
                    $solicitante['numero_institucional'],
                    $solicitante['nombre'],
                    $solicitante['id_departamento'],
                ]);
                header('Location: solicitantes.php?ok=registrado');
                exit;
            }
        } catch (PDOException $e) {
            $codigo = $e->errorInfo[1] ?? 0;
            if ($codigo == 1062) {
                $error = 'Ese número institucional ya está registrado.';
            } else {
                $error = 'Ocurrió un error al guardar el solicitante.';
            }
        }
    }
} elseif ($id > 0) {
    $stmt = $pdo->prepare('SELECT numero_institucional, nombre, id_departamento FROM solicitantes WHERE id_solicitante = ?');
    $stmt->execute([$id]);
    $fila = $stmt->fetch();

    if (!$fila) {
        header('Location: solicitantes.php');
        exit;
    }
    $solicitante = $fila;
}

$titulo = $id > 0 ? 'Editar Solicitante' : 'Registrar Solicitante';

require $base . 'includes/header.php';
?>
            <h1><?= $id > 0 ? 'Editar Solicitante' : 'Datos del Solicitante' ?></h1>

            <?php if ($error !== ''): ?>
                <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="panel form-panel">
                <form action="registrar.php" method="POST">
                    <?php if ($id > 0): ?>
                        <input type="hidden" name="id_solicitante" value="<?= $id ?>">
                    <?php endif; ?>

                    <div class="form-group full-width">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" maxlength="150" value="<?= htmlspecialchars($solicitante['nombre']) ?>" required>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="numero_institucional">Número Institucional</label>
                            <input type="text" id="numero_institucional" name="numero_institucional" maxlength="50" value="<?= htmlspecialchars($solicitante['numero_institucional']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="id_departamento">Departamento</label>
                            <select id="id_departamento" name="id_departamento" required>
                                <option value="">Seleccione un departamento...</option>
                                <?php foreach ($departamentos as $dep): ?>
                                    <option value="<?= $dep['id_departamento'] ?>"<?= (int) $dep['id_departamento'] === (int) $solicitante['id_departamento'] ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($dep['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="solicitantes.php" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
<?php
require $base . 'includes/footer.php';
?>