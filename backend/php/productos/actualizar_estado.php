<?php
require_once '../../db/conexion.php'; // Asegúrate de que la ruta es correcta

// Verificar si se recibieron los datos necesarios
if (isset($_POST['id']) && isset($_POST['estado'])) {
    $idProducto = $_POST['id'];
    $nuevoEstado = $_POST['estado']; // Esperado: 0 o 1

    try {
        // Preparar y ejecutar la actualización
        $stmt = $pdo->prepare("UPDATE productos SET Estado = ? WHERE IdProducto = ?");
        $stmt->execute([$nuevoEstado, $idProducto]);

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
        'error' => 'Faltan parámetros: id y estado son requeridos.'
    ]);
}
