<?php
// listar_comedores.php
header('Content-Type: application/json');
require_once '../../db/conexion.php';

try {
    $stmt = $pdo->query("SELECT IdComedor, Nombre, Beneficiarios, Encargado, Direccion FROM comedores");
    $comedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($comedores);
} catch (PDOException $e) {
    // En caso de error devolver un JSON vacío o un mensaje de error para debug (solo en desarrollo)
    echo json_encode(['error' => $e->getMessage()]);
}
