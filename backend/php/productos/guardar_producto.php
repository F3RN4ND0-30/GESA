<?php
require_once '../../db/conexion.php'; // Ajusta la ruta según tu estructura

header('Content-Type: application/json');

if (isset($_POST['articulo'], $_POST['marca'], $_POST['unidad'])) {
    $descripcion = $_POST['articulo'];
    $marca = $_POST['marca'];
    $idUnidadMedida = $_POST['unidad'];

    try {
        $stmt = $pdo->prepare("INSERT INTO productos (Descripcion, Marca, IdUnidadMedida, Estado) VALUES (?, ?, ?, ?)");
        $estado = 1; // Valor por defecto para Estado
        $stmt->execute([$descripcion, $marca, $idUnidadMedida, $estado]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Faltan datos en la solicitud POST'
    ]);
}
