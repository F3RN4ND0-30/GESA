<?php
// Detecta la página actual para resaltar el tab
$filename = basename($_SERVER['SCRIPT_NAME']); // ej: escritorio.php
function active($pages)
{ // $pages puede ser string o array
    global $filename;
    if (!is_array($pages)) $pages = [$pages];
    return in_array($filename, $pages) ? 'active' : '';
}
?>
<link rel="stylesheet" href="../../backend/css/navbar/navbar.css" />

<nav class="navbar">
    <div class="nav-left">
        <div class="logo">
            <i class="fas fa-cube"></i>
            <div class="logo-text">
                <span class="logo-title">GESA</span>
            </div>
        </div>

        <div class="nav-menu">
            <!-- Ajusta los href a tus rutas reales si cambian -->
            <a href="../sisvis/escritorio.php" class="nav-link <?= active(['escritorio.php']) ?>">Dashboard</a>
            <a href="#" class="nav-link <?= active(['entradas.php']) ?>">Entradas</a>
            <a href="#" class="nav-link <?= active(['pecosa.php']) ?>">PECOSA</a>
            <a href="#" class="nav-link <?= active(['almacen.php']) ?>">Almacén</a>
            <a href="#" class="nav-link <?= active(['reportes.php']) ?>">Reportes</a>
        </div>
    </div>

    <div class="nav-right">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Buscar PECOSA, insumo o movimiento...">
        </div>

        <!-- Si algún módulo necesita botón, lo pones dentro de ese módulo, no aquí -->
        <div class="user-profile">
            <div class="user-avatar"><i class="fas fa-user"></i></div>
            <span>Johan</span>
        </div>
    </div>
</nav>