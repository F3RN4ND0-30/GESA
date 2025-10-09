<?php
require_once '../../db/conexion.php';

header('Content-Type: application/json');

if (!isset($_GET['idProducto'])) {
    echo json_encode(['success' => false, 'error' => 'IdProducto no proporcionado']);
    exit;
}

$idProducto = $_GET['idProducto'];

try {
    $sql = "SELECT CantidadPorPersona FROM distribucion_productos WHERE IdProducto = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idProducto]);

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode([
            'success' => true,
            'cantidadPorPersona' => $row['CantidadPorPersona']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No encontrado']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
