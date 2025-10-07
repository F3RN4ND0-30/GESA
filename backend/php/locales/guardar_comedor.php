<?php
// guardar_comedor.php
header('Content-Type: application/json');
require_once '../../db/conexion.php';

$nombre = $_POST['nombre'] ?? '';
$beneficiarios = $_POST['beneficiarios'] ?? '';
$encargado = $_POST['encargado'] ?? '';
$direccion = $_POST['direccion'] ?? '';

if (!$nombre || !$beneficiarios || !$encargado || !$direccion) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO comedores (Nombre, Beneficiarios, Encargado, Direccion) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $beneficiarios, $encargado, $direccion]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
