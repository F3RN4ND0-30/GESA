<?php
require_once __DIR__ . '/../../backend/php/autenticacion.php';

/* Verifica sesión aquí para todas las páginas que incluyan el navbar */
verificar_sesion();

$yo = usuario_actual();

/* Resaltado activo por filename */
$filename = basename($_SERVER['SCRIPT_NAME']);
function active($pages) {
  global $filename;
  if (!is_array($pages)) $pages = [$pages];
  return in_array($filename, $pages) ? 'active' : '';
}
?>
<link rel="stylesheet" href="../../backend/css/navbar/navbar.css" />

<nav class="navbar">
  <div class="nav-left">
    <a href="../sisvis/escritorio.php" class="logo">
      <i class="fas fa-cube"></i>
      <div class="logo-text"><span class="logo-title">GESA</span></div>
    </a>

    <ul class="nav-menu">
      <li class="nav-item">
        <a href="../sisvis/escritorio.php" class="nav-link <?= active(['escritorio.php']) ?>">Inicio</a>
      </li>

      <li class="nav-item has-dropdown">
        <button class="nav-link nav-button" type="button" aria-haspopup="true" aria-expanded="false">
          Operaciones <i class="fas fa-chevron-down dd-caret"></i>
        </button>
        <div class="dropdown" role="menu">
          <a href="../entradas/registro_productos.php" class="dropdown-item <?= active(['registro_productos.php']) ?>">
            <i class="fas fa-arrow-down"></i> Entradas
          </a>
          <a href="#" class="dropdown-item <?= active(['pecosa.php']) ?>">
            <i class="fas fa-arrow-up"></i> PECOSA
          </a>
        </div>
      </li>

      <li class="nav-item">
        <a href="#" class="nav-link <?= active(['almacen.php']) ?>">Almacén</a>
      </li>

      <li class="nav-item">
        <a href="#" class="nav-link <?= active(['reportes.php']) ?>">Reportes</a>
      </li>

      <li class="nav-item has-dropdown">
        <button class="nav-link nav-button" type="button" aria-haspopup="true" aria-expanded="false">
          Registro <i class="fas fa-chevron-down dd-caret"></i>
        </button>
        <div class="dropdown" role="menu">
          <a href="../entradas/registro_productos.php" class="dropdown-item <?= active(['registro_productos.php']) ?>">
            <i class="fas fa-box"></i> Producto
          </a>
          <a href="../lugares/locales.php" class="dropdown-item <?= active(['locales.php']) ?>">
            <i class="fas fa-location-dot"></i> Locales
          </a>
        </div>
      </li>
    </ul>
  </div>

  <div class="nav-right">
    <div class="search-bar">
      <i class="fas fa-search"></i>
      <input type="text" placeholder="Buscar PECOSA, insumo o movimiento...">
    </div>

    <div class="user-profile" id="userDropdown">
      <div class="user-avatar"><i class="fas fa-user"></i></div>
      <div class="user-meta">
        <span class="user-name"><?= htmlspecialchars($yo['nombre'] ?: $yo['usuario'], ENT_QUOTES, 'UTF-8') ?></span>
        <small class="user-role"><?= htmlspecialchars($yo['rol'] ?? '', ENT_QUOTES, 'UTF-8') ?></small>
      </div>
      <i class="fas fa-chevron-down caret"></i>

      <div class="user-menu">
        <a href="#" class="user-menu-item"><i class="fas fa-id-badge"></i> Mi perfil</a>
        <form action="../logout.php" method="post" class="user-menu-item logout-form">
          <button type="submit"><i class="fas fa-right-from-bracket"></i> Cerrar sesión</button>
        </form>
      </div>
    </div>
  </div>
</nav>

<script>
(() => {
  // User dropdown
  const userDd = document.getElementById('userDropdown');
  if (userDd) {
    userDd.addEventListener('click', (e) => {
      userDd.classList.toggle('open');
      e.stopPropagation();
    });
  }

  // Nav dropdowns
  document.querySelectorAll('.nav-item.has-dropdown').forEach(item => {
    const btn = item.querySelector('.nav-button');
    const menu = item.querySelector('.dropdown');

    btn.addEventListener('click', (e) => {
      const open = item.classList.toggle('open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      e.stopPropagation();
    });

    // Accesibilidad con teclado
    btn.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') item.classList.remove('open');
    });
  });

  // Cerrar todos al click fuera
  document.addEventListener('click', () => {
    document.querySelectorAll('.open').forEach(el => el.classList.remove('open'));
  });

  // Cerrar con ESC global
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.open').forEach(el => el.classList.remove('open'));
    }
  });
})();
</script>
