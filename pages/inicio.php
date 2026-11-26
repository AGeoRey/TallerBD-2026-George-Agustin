<?php
$titulo = 'Inicio';
$pagina_actual = 'inicio';
$base = '../';

require_once $base . 'includes/auth.php';
require_once $base . 'includes/conexion.php';

$resumen = $pdo->query(
    "SELECT COUNT(*) AS total,
            SUM(condicion_actual = 'Disponible') AS disponibles,
            SUM(condicion_actual = 'Prestado') AS prestados,
            SUM(condicion_actual = 'Mantenimiento') AS mantenimiento
     FROM equipos"
)->fetch();

$prestamos_activos = $pdo->query(
    'SELECT p.id_inventario, e.descripcion AS equipo,
            s.numero_institucional, s.nombre AS solicitante,
            p.fecha_prestamo, p.fecha_prevista_devolucion,
            (p.fecha_prevista_devolucion < NOW()) AS esta_vencido
     FROM prestamos p
     INNER JOIN equipos e ON p.id_inventario = e.id_inventario
     INNER JOIN solicitantes s ON p.id_solicitante = s.id_solicitante
     WHERE p.fecha_recepcion IS NULL
     ORDER BY p.fecha_prevista_devolucion ASC'
)->fetchAll();

require $base . 'includes/header.php';
?>
            <h1>Resumen General</h1>

            <!-- TARJETAS DE INDICADORES -->
            <div class="dashboard-cards">
                <div class="card">
                    <h3>Total de Equipos</h3>
                    <p class="card-number"><?= (int) ($resumen['total'] ?? 0) ?></p>
                </div>
                <div class="card">
                    <h3>Disponibles</h3>
                    <p class="card-number"><?= (int) ($resumen['disponibles'] ?? 0) ?></p>
                </div>
                <div class="card">
                    <h3>Prestados</h3>
                    <p class="card-number"><?= (int) ($resumen['prestados'] ?? 0) ?></p>
                </div>
                <div class="card">
                    <h3>En Mantenimiento</h3>
                    <p class="card-number"><?= (int) ($resumen['mantenimiento'] ?? 0) ?></p>
                </div>
            </div>

            <!-- TABLA DE DATOS -->
            <div class="panel">
                <h2>Préstamos Activos</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Equipo</th>
                                <th>Solicitante</th>
                                <th>Fecha Préstamo</th>
                                <th>Devolución Prevista</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($prestamos_activos)): ?>
                                <tr><td colspan="5">No hay préstamos activos.</td></tr>
                            <?php else: ?>
                                <?php foreach ($prestamos_activos as $fila): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($fila['id_inventario'] . ' - ' . $fila['equipo']) ?></td>
                                        <td><?= htmlspecialchars($fila['numero_institucional'] . ' - ' . $fila['solicitante']) ?></td>
                                        <td><?= htmlspecialchars($fila['fecha_prestamo']) ?></td>
                                        <td><?= htmlspecialchars($fila['fecha_prevista_devolucion']) ?></td>
                                        <td><?= $fila['esta_vencido'] ? 'Vencido' : 'Vigente' ?></td>
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