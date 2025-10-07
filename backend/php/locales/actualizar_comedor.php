<?php
header('Content-Type: application/json');
require_once '../../db/conexion.php';

try {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $beneficiarios = $_POST['beneficiarios'];
    $encargado = $_POST['encargado'];
    $direccion = $_POST['direccion'];

    $stmt = $pdo->prepare("UPDATE comedores SET Nombre = ?, Beneficiarios = ?, Encargado = ?, Direccion = ? WHERE IdComedor = ?");
    $stmt->execute([$nombre, $beneficiarios, $encargado, $direccion, $id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
