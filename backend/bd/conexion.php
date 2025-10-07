<?php
// backend/bd/conexion.php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function db(): mysqli {
  static $cn = null;
  if ($cn instanceof mysqli) return $cn;

  try {
    $cn = new mysqli('localhost', 'root', '', 'gesa');
    $cn->set_charset('utf8mb4');
    return $cn;
  } catch (Throwable $e) {
    http_response_code(500);
    exit('Error de base de datos.');
  }
}
