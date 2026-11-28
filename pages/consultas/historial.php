<?php
$titulo = 'Historial';
$pagina_actual = 'historial';
$base = '../../';

require_once $base . 'includes/auth.php';
require_once $base . 'includes/conexion.php';

$criterio = $_GET['criterio_busqueda'] ?? 'equipo';
if ($criterio !== 'equipo' && $criterio !== 'solicitante') {
    $criterio = 'equipo';
}

$valor = trim($_GET['valor_busqueda'] ?? '');
$buscado = $valor !== '';
$prestamos = [];
$estados = [];

if ($buscado) {
    if ($criterio === 'equipo') {
        $stmt = $pdo->prepare(
            'SELECT p.id_prestamo, p.id_inventario, e.descripcion AS equipo,
                    s.numero_institucional, s.nombre AS solicitante,
                    p.fecha_prestamo, p.fecha_recepcion, p.condicion_regreso
             FROM prestamos p
             INNER JOIN equipos e ON p.id_inventario = e.id_inventario
             INNER JOIN solicitantes s ON p.id_solicitante = s.id_solicitante
             WHERE p.id_inventario = ?
             ORDER BY p.fecha_prestamo DESC'
        );
        $stmt->execute([$valor]);
        $prestamos = $stmt->fetchAll();

        $stmt = $pdo->prepare(
            'SELECT fecha_cambio, estado_anterior, estado_nuevo
             FROM historial_estados
             WHERE id_inventario = ?
             ORDER BY fecha_cambio DESC'
        );
        $stmt->execute([$valor]);
        $estados = $stmt->fetchAll();
    } else {
        $stmt = $pdo->prepare(
            'SELECT p.id_prestamo, p.id_inventario, e.descripcion AS equipo,
                    s.numero_institucional, s.nombre AS solicitante,
                    p.fecha_prestamo, p.fecha_recepcion, p.condicion_regreso
             FROM prestamos p
             INNER JOIN equipos e ON p.id_inventario = e.id_inventario
             INNER JOIN solicitantes s ON p.id_solicitante = s.id_solicitante
             WHERE s.numero_institucional = ?
             ORDER BY p.fecha_prestamo DESC'
        );
        $stmt->execute([$valor]);
        $prestamos = $stmt->fetchAll();
    }
}

$lista_equipos = $pdo->query('SELECT id_inventario, descripcion FROM equipos ORDER BY id_inventario')->fetchAll();
$lista_solicitantes = $pdo->query('SELECT numero_institucional, nombre FROM solicitantes ORDER BY nombre')->fetchAll();

require $base . 'includes/header.php';
?>
            <h1>Historial de Movimientos</h1>

            <div class="panel">
                <div class="toolbar">
                    <form action="historial.php" method="GET" class="filters">
                        <select class="select-filter" id="criterio_busqueda" name="criterio_busqueda">
                            <option value="equipo"<?= $criterio === 'equipo' ? ' selected' : '' ?>>Por ID de Equipo</option>
                            <option value="solicitante"<?= $criterio === 'solicitante' ? ' selected' : '' ?>>Por Número Institucional</option>
                        </select>
                        <input type="text" id="valor_busqueda" name="valor_busqueda" list="<?= $criterio === 'equipo' ? 'lista_equipos' : 'lista_solicitantes' ?>" placeholder="Ingrese el identificador..." class="input-search" value="<?= htmlspecialchars($valor) ?>" required>
                        <button type="submit" class="btn-primary">Buscar Historial</button>

                        <datalist id="lista_equipos">
                            <?php foreach ($lista_equipos as $eq): ?>
                                <option value="<?= htmlspecialchars($eq['id_inventario']) ?>"><?= htmlspecialchars($eq['descripcion']) ?></option>
                            <?php endforeach; ?>
                        </datalist>
                        <datalist id="lista_solicitantes">
                            <?php foreach ($lista_solicitantes as $sol): ?>
                                <option value="<?= htmlspecialchars($sol['numero_institucional']) ?>"><?= htmlspecialchars($sol['nombre']) ?></option>
                            <?php endforeach; ?>
                        </datalist>
                    </form>
                </div>

                <h2>Historial de Préstamos</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Equipo</th>
                                <th>Solicitante</th>
                                <th>Fecha Salida</th>
                                <th>Fecha Recepción</th>
                                <th>Condición de Regreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$buscado): ?>
                                <tr><td colspan="6">Ingrese un ID de equipo o número institucional para consultar.</td></tr>
                            <?php elseif (empty($prestamos)): ?>
                                <tr><td colspan="6">No se encontraron préstamos para ese identificador.</td></tr>
                            <?php else: ?>
                                <?php foreach ($prestamos as $fila): ?>
                                    <tr>
                                        <td><?= $fila['id_prestamo'] ?></td>
                                        <td><?= htmlspecialchars($fila['id_inventario'] . ' - ' . $fila['equipo']) ?></td>
                                        <td><?= htmlspecialchars($fila['numero_institucional'] . ' - ' . $fila['solicitante']) ?></td>
                                        <td><?= htmlspecialchars($fila['fecha_prestamo']) ?></td>
                                        <td><?= $fila['fecha_recepcion'] !== null ? htmlspecialchars($fila['fecha_recepcion']) : 'En préstamo' ?></td>
                                        <td><?= $fila['condicion_regreso'] !== null ? htmlspecialchars($fila['condicion_regreso']) : '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if ($buscado && $criterio === 'equipo'): ?>
                <div class="panel">
                    <h2>Historial de Estados</h2>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Estado Anterior</th>
                                    <th>Estado Nuevo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($estados)): ?>
                                    <tr><td colspan="3">Sin cambios de estado registrados para este equipo.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($estados as $fila): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($fila['fecha_cambio']) ?></td>
                                            <td><?= htmlspecialchars($fila['estado_anterior'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($fila['estado_nuevo'] ?? '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
<?php
require $base . 'includes/footer.php';
?>