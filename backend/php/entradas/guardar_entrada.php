<?php
header('Content-Type: application/json');
require_once '../../db/conexion.php';

try {
    // Leer el cuerpo JSON
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if (!$data) {
        throw new Exception("Datos inválidos (no JSON).");
    }

    // Validar campos
    $observaciones = $data['observaciones'] ?? '';
    $productos = $data['productos'] ?? [];

    if (!is_array($productos) || count($productos) === 0) {
        throw new Exception("No se han enviado productos para la entrada.");
    }

    // Iniciar transacción
    $pdo->beginTransaction();

    // Insertar en tabla entrada
    $stmtEntrada = $pdo->prepare("INSERT INTO entrada (FechaEntrada, Observaciones) VALUES (?, ?)");
    $fechaAhora = date('Y-m-d H:i:s');
    $stmtEntrada->execute([$fechaAhora, $observaciones]);
    $idEntrada = $pdo->lastInsertId();

    // Insertar los detalles
    $stmtDetalle = $pdo->prepare("INSERT INTO detalle_entrada (IdEntrada, IdProducto, Cantidad) VALUES (?, ?, ?)");

    foreach ($productos as $prod) {
        // Validar que cada producto tenga id y cantidad
        if (!isset($prod['id']) || !isset($prod['cantidad'])) {
            throw new Exception("Producto con datos incompletos.");
        }
        $idProducto = $prod['id'];
        $cantidad = $prod['cantidad'];

        // Puedes agregar validaciones adicionales, por ejemplo cantidad > 0
        $stmtDetalle->execute([$idEntrada, $idProducto, $cantidad]);
    }

    // Confirmar transacción
    $pdo->commit();

    echo json_encode(['success' => true, 'idEntrada' => $idEntrada]);
} catch (Exception $e) {
    // En caso de error, revertir si la transacción está activa
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
