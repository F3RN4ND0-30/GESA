<?php
header('Content-Type: application/json');
require_once '../../db/conexion.php';

$idProducto = $_GET['id'] ?? null;

if (!$idProducto) {
    echo json_encode(['error' => 'IdProducto requerido']);
    exit;
}

try {
    $sql = "
        SELECT
            e.FechaEntrada AS fecha,
            de.Cantidad AS cantidad,
            'entrada' AS tipo
        FROM detalle_entrada de
        INNER JOIN entrada e ON de.IdEntrada = e.IdEntrada
        WHERE de.IdProducto = :idProducto

        UNION ALL

        SELECT
            s.FechaSalida AS fecha,
            ds.Cantidad AS cantidad,
            'salida' AS tipo
        FROM detalle_salida ds
        INNER JOIN salidas s ON ds.IdSalida = s.IdSalida
        WHERE ds.IdProducto = :idProducto

        ORDER BY fecha ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['idProducto' => $idProducto]);
    $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($movimientos);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener movimientos: ' . $e->getMessage()]);
}
