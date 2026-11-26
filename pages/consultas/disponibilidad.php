<?php
$titulo = 'Disponibilidad';
$pagina_actual = 'disponibilidad';
$base = '../../';

require_once $base . 'includes/auth.php';
require_once $base . 'includes/conexion.php';

$tipo_reporte = $_GET['tipo_reporte'] ?? 'general';
$permitidos = ['general', 'disponibles', 'activos', 'vencidos'];

if (!in_array($tipo_reporte, $permitidos, true)) {
    $tipo_reporte = 'general';
}

if ($tipo_reporte === 'general') {
    $resultados = $pdo->query(
        'SELECT e.id_inventario, e.descripcion, c.nombre AS categoria, m.nombre AS marca, e.condicion_actual
         FROM equipos e
         INNER JOIN categorias c ON e.id_categoria = c.id_categoria
         INNER JOIN marcas m ON e.id_marca = m.id_marca
         ORDER BY e.id_inventario'
    )->fetchAll();
} elseif ($tipo_reporte === 'disponibles') {
    $resultados = $pdo->query(
        "SELECT e.id_inventario, e.descripcion, c.nombre AS categoria, m.nombre AS marca
         FROM equipos e
         INNER JOIN categorias c ON e.id_categoria = c.id_categoria
         INNER JOIN marcas m ON e.id_marca = m.id_marca
         WHERE e.condicion_actual = 'Disponible'
         ORDER BY e.id_inventario"
    )->fetchAll();
} elseif ($tipo_reporte === 'activos') {
    $resultados = $pdo->query(
        'SELECT p.id_prestamo, p.id_inventario, e.descripcion AS equipo,
                s.numero_institucional, s.nombre AS solicitante, d.nombre AS departamento,
                p.fecha_prestamo, p.fecha_prevista_devolucion,
                (p.fecha_prevista_devolucion < NOW()) AS esta_vencido
         FROM prestamos p
         INNER JOIN equipos e ON p.id_inventario = e.id_inventario
         INNER JOIN solicitantes s ON p.id_solicitante = s.id_solicitante
         INNER JOIN departamentos d ON s.id_departamento = d.id_departamento
         WHERE p.fecha_recepcion IS NULL
         ORDER BY p.fecha_prevista_devolucion ASC'
    )->fetchAll();
} else {
    $resultados = $pdo->query(
        'SELECT p.id_prestamo, p.id_inventario, e.descripcion AS equipo,
                s.numero_institucional, s.nombre AS solicitante, d.nombre AS departamento,
                p.fecha_prevista_devolucion,
                DATEDIFF(NOW(), p.fecha_prevista_devolucion) AS dias_retraso
         FROM prestamos p
         INNER JOIN equipos e ON p.id_inventario = e.id_inventario
         INNER JOIN solicitantes s ON p.id_solicitante = s.id_solicitante
         INNER JOIN departamentos d ON s.id_departamento = d.id_departamento
         WHERE p.fecha_recepcion IS NULL AND p.fecha_prevista_devolucion < NOW()
         ORDER BY p.fecha_prevista_devolucion ASC'
    )->fetchAll();
}

require $base . 'includes/header.php';
?>
            <h1>Disponibilidad de Equipos</h1>

            <div class="panel">
                <div class="toolbar">
                    <form action="disponibilidad.php" method="GET" class="filters">
                        <select class="select-filter" id="tipo_reporte" name="tipo_reporte">
                            <option value="general"<?= $tipo_reporte === 'general' ? ' selected' : '' ?>>Listado general de equipos</option>
                            <option value="disponibles"<?= $tipo_reporte === 'disponibles' ? ' selected' : '' ?>>Equipos disponibles</option>
                            <option value="activos"<?= $tipo_reporte === 'activos' ? ' selected' : '' ?>>Préstamos activos (quién tiene qué)</option>
                            <option value="vencidos"<?= $tipo_reporte === 'vencidos' ? ' selected' : '' ?>>Préstamos vencidos</option>
                        </select>
                        <button type="submit" class="btn-primary">Visualizar</button>
                    </form>
                </div>

                <div class="table-container">
                    <table class="data-table">
                        <?php if ($tipo_reporte === 'general'): ?>
                            <thead>
                                <tr>
                                    <th>ID Inventario</th>
                                    <th>Descripción</th>
                                    <th>Categoría</th>
                                    <th>Marca</th>
                                    <th>Condición</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($resultados)): ?>
                                    <tr><td colspan="5">No hay equipos registrados.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($resultados as $fila): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($fila['id_inventario']) ?></td>
                                            <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                                            <td><?= htmlspecialchars($fila['categoria']) ?></td>
                                            <td><?= htmlspecialchars($fila['marca']) ?></td>
                                            <td><?= htmlspecialchars($fila['condicion_actual']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        <?php elseif ($tipo_reporte === 'disponibles'): ?>
                            <thead>
                                <tr>
                                    <th>ID Inventario</th>
                                    <th>Descripción</th>
                                    <th>Categoría</th>
                                    <th>Marca</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($resultados)): ?>
                                    <tr><td colspan="4">No hay equipos disponibles en este momento.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($resultados as $fila): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($fila['id_inventario']) ?></td>
                                            <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                                            <td><?= htmlspecialchars($fila['categoria']) ?></td>
                                            <td><?= htmlspecialchars($fila['marca']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        <?php elseif ($tipo_reporte === 'activos'): ?>
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Equipo</th>
                                    <th>Solicitante</th>
                                    <th>Departamento</th>
                                    <th>Fecha Préstamo</th>
                                    <th>Devolución Prevista</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($resultados)): ?>
                                    <tr><td colspan="7">No hay préstamos activos.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($resultados as $fila): ?>
                                        <tr>
                                            <td><?= $fila['id_prestamo'] ?></td>
                                            <td><?= htmlspecialchars($fila['id_inventario'] . ' - ' . $fila['equipo']) ?></td>
                                            <td><?= htmlspecialchars($fila['numero_institucional'] . ' - ' . $fila['solicitante']) ?></td>
                                            <td><?= htmlspecialchars($fila['departamento']) ?></td>
                                            <td><?= htmlspecialchars($fila['fecha_prestamo']) ?></td>
                                            <td><?= htmlspecialchars($fila['fecha_prevista_devolucion']) ?></td>
                                            <td><?= $fila['esta_vencido'] ? 'Vencido' : 'Vigente' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        <?php else: ?>
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Equipo</th>
                                    <th>Solicitante</th>
                                    <th>Departamento</th>
                                    <th>Devolución Prevista</th>
                                    <th>Días de Retraso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($resultados)): ?>
                                    <tr><td colspan="6">No hay préstamos vencidos.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($resultados as $fila): ?>
                                        <tr>
                                            <td><?= $fila['id_prestamo'] ?></td>
                                            <td><?= htmlspecialchars($fila['id_inventario'] . ' - ' . $fila['equipo']) ?></td>
                                            <td><?= htmlspecialchars($fila['numero_institucional'] . ' - ' . $fila['solicitante']) ?></td>
                                            <td><?= htmlspecialchars($fila['departamento']) ?></td>
                                            <td><?= htmlspecialchars($fila['fecha_prevista_devolucion']) ?></td>
                                            <td><?= (int) $fila['dias_retraso'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
<?php
require $base . 'includes/footer.php';
?>