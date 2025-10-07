<?php
require_once '../../db/conexion.php';

$sql = "SELECT IdUnidadMedida, Descripcion FROM unidad_medida";
$stmt = $pdo->query($sql);
$unidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($unidades);
