<?php
header('Content-Type: application/json');
require_once '../../db/conexion.php';

// Consulta que calcule el stock total: entradas – salidas
$sql = "
    SELECT
        p.IdProducto,
        p.Descripcion,
        p.Marca,
        u.Descripcion AS Unidad,
        COALESCE(
            (SELECT SUM(de.Cantidad) FROM detalle_entrada de WHERE de.IdProducto = p.IdProducto),
            0
        ) -
        COALESCE(
            (SELECT SUM(ds.Cantidad) FROM detalle_salida ds WHERE ds.IdProducto = p.IdProducto),
            0
        ) AS StockTotal
    FROM productos p
    JOIN unidad_medida u ON p.IdUnidadMedida = u.IdUnidadMedida
";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
