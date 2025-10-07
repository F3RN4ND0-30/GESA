<?php
require_once __DIR__ . '/../backend/php/autenticacion.php';

// Cerrar sesión y destruir cookies
cerrar_sesion();

// Evitar que el navegador guarde esta página en caché
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// Redirigir al login
header('Location: login.php');
exit;
