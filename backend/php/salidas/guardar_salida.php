<?php
require_once '../../db/conexion.php';

header('Content-Type: application/json');

// Leer JSON enviado por JS
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['idComedor'], $input['productos'])) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit;
}

$idComedor = $input['idComedor'];
$observaciones = isset($input['observaciones']) ? $input['observaciones'] : '';
$productos = $input['productos'];

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // Generar un número PECOSA automático (ej: PEC-2025-0012)
    $año = date('Y');
    $stmtMax = $pdo->query("SELECT MAX(IdSalida) AS max_id FROM salidas");
    $maxId = $stmtMax->fetch(PDO::FETCH_ASSOC)['max_id'] ?? 0;
    $numeroPECOSA = "PEC-$año-" . str_pad($maxId + 1, 4, '0', STR_PAD_LEFT);

    // Insertar en salidas
    $sqlSalida = "INSERT INTO salidas (NumeroPECOSA, FechaSalida, Observaciones) VALUES (?, NOW(), ?)";
    $stmtSalida = $pdo->prepare($sqlSalida);
    $stmtSalida->execute([$numeroPECOSA, $observaciones]);
    $idSalida = $pdo->lastInsertId();

    // Insertar en detalle_salida
    $sqlDetalle = "INSERT INTO detalle_salida (IdSalida, IdProducto, Cantidad) VALUES (?, ?, ?)";
    $stmtDetalle = $pdo->prepare($sqlDetalle);

    foreach ($productos as $producto) {
        $stmtDetalle->execute([
            $idSalida,
            $producto['id'],
            $producto['cantidad']
        ]);
    }

    // Confirmar
    $pdo->commit();

    echo json_encode(['success' => true, 'idSalida' => $idSalida, 'numeroPECOSA' => $numeroPECOSA]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
