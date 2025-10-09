<?php
require_once '../../db/conexion.php';

try {
    $sql = "SELECT IdComedor, Nombre, Beneficiarios FROM comedores";
    $stmt = $pdo->query($sql);
    $comedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($comedores);
} catch (Exception $e) {
    echo json_encode([]);
}
