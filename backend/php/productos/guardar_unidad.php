<?php
require_once '../../db/conexion.php';

$descripcion = $_POST['descripcion'];

$sql = "INSERT INTO unidad_medida (descripcion) VALUES (:descripcion)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':descripcion', $descripcion);
$stmt->execute();

echo json_encode(["success" => true]);
