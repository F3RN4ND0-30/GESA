<?php
require_once '../../db/conexion.php';

$sql = "SELECT p.IdProducto, p.Descripcion, p.Marca, u.descripcion AS Unidad, p.Estado
        FROM productos p
        JOIN unidad_medida u ON p.IdUnidadMedida = u.IdUnidadMedida
";
$stmt = $pdo->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($productos);
