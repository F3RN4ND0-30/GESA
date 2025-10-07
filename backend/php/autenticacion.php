<?php
/* ========= CONFIG ========= */
define('GS_BLOQUEO_ACTIVO', true);
define('GS_MAX_INTENTOS', 5);
define('GS_MINUTOS_BLOQ', 3);
define('GS_DEV', true);

/* ========= SESIÓN ========= */
session_name('gs_session');
$es_https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
session_set_cookie_params([
  'lifetime' => 0,
  'path'     => '/',
  'domain'   => '',
  'secure'   => $es_https,
  'httponly' => true,
  'samesite' => 'Lax',
]);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

/* Claves de sesión */
define('GS_UID',  'gs_uid');
define('GS_USER', 'gs_usuario');
define('GS_NAME', 'gs_nombre');
define('GS_ROL',  'gs_rol');

/* ========= BD ========= */
require_once __DIR__ . '/../bd/conexion.php';
$db = db();

/* ========= UTILIDADES ========= */
function token_csrf(): string {
  // Genera/retorna token CSRF por sesión
  if (empty($_SESSION['gs_csrf'])) {
    $_SESSION['gs_csrf'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['gs_csrf'];
}

function verificar_csrf(string $t): bool {
  // Valida token CSRF
  return isset($_SESSION['gs_csrf']) && hash_equals($_SESSION['gs_csrf'], $t);
}

function esta_logueado(): bool {
  // ¿Hay usuario en sesión?
  return !empty($_SESSION[GS_UID]);
}

function requerir_login(): void {
  // Redirige al login si no hay sesión
  if (!esta_logueado()) {
    header('Location: /frontend/login.php');
    exit;
  }
}

function redirigir_si_logueado(string $ruta = '../frontend/sisvis/escritorio.php'): void {
  // Evita mostrar login si ya hay sesión
  if (esta_logueado()) {
    header('Location: ' . $ruta);
    exit;
  }
}

function cerrar_sesion(): void {
  // Destruye la sesión y cookies
  $_SESSION = [];
  if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
  }
  session_destroy();
}

function no_cache(): void {
  // Evita que el navegador sirva páginas privadas desde caché
  header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
  header('Pragma: no-cache');
  header('Expires: 0');
}

function verificar_sesion(bool $aplicar_no_cache = true): void {
  // Llama en cada página privada
  requerir_login();
  if ($aplicar_no_cache) no_cache();
}

function mapear_rol(int $idRol): string {
  // Mapea IdRol a texto
  return $idRol === 1 ? 'admin' : ($idRol === 2 ? 'operador' : 'consulta');
}

function usuario_actual(): array {
  // Devuelve datos del usuario en sesión
  return [
    'id'      => $_SESSION[GS_UID]  ?? null,
    'usuario' => $_SESSION[GS_USER] ?? null,
    'nombre'  => $_SESSION[GS_NAME] ?? null,
    'rol'     => $_SESSION[GS_ROL]  ?? null,
  ];
}

/* ========= DEV: reset de bloqueos ========= */
if (GS_DEV && isset($_GET['gs_unlock'])) {
  $db->query("UPDATE usuarios SET intentos_fallidos=0, bloqueado_hasta=NULL");
  echo "🔓 Bloqueos reseteados (DEV).";
  exit;
}

/* ========= LOGIN =========
   Llamar en login.php con POST. Retorna '' si OK; o mensaje de error. */
function hacer_login(): string {
  global $db;

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') return '';

  if (!verificar_csrf($_POST['csrf'] ?? '')) {
    return 'Solicitud inválida. Recargue la página.';
  }

  $usuario = trim((string)($_POST['usuario'] ?? ''));
  $claveIn = (string)($_POST['clave'] ?? '');
  if ($usuario === '' || $claveIn === '') {
    return 'Complete usuario y contraseña.';
  }

  // Buscar usuario
  $stmt = $db->prepare("
    SELECT u.idusuario AS id, u.usuario, u.clave, u.IdRol,
           COALESCE(u.estado,1) AS estado,
           COALESCE(u.intentos_fallidos,0) AS intentos_fallidos,
           u.bloqueado_hasta, u.nombre
    FROM usuarios u
    WHERE u.usuario = ?
    LIMIT 1
  ");
  $stmt->bind_param('s', $usuario);
  $stmt->execute();
  $res = $stmt->get_result();
  $u   = $res->fetch_assoc();
  $stmt->close();

  if (!$u) {
    usleep(200000);
    return 'Usuario o contraseña incorrectos.';
  }

  // Bloqueo temporal
  if (GS_BLOQUEO_ACTIVO && !empty($u['bloqueado_hasta']) && strtotime($u['bloqueado_hasta']) > time()) {
    return 'Cuenta bloqueada temporalmente. Intente más tarde.';
  }

  if ((int)$u['estado'] !== 1) {
    return 'Usuario inactivo.';
  }

  // Verificar contraseña (bcrypt en "clave")
  if (!password_verify($claveIn, $u['clave'])) {
    $int = min(255, (int)$u['intentos_fallidos'] + 1);

    if (GS_BLOQUEO_ACTIVO && $int >= GS_MAX_INTENTOS) {
      $hasta = date('Y-m-d H:i:s', time() + GS_MINUTOS_BLOQ * 60);
      $stmt  = $db->prepare("UPDATE usuarios SET intentos_fallidos=?, bloqueado_hasta=? WHERE idusuario=?");
      $stmt->bind_param('isi', $int, $hasta, $u['id']);
      $stmt->execute();
      $stmt->close();
      return 'Cuenta bloqueada por intentos fallidos. Intente en ' . GS_MINUTOS_BLOQ . ' min.';
    } else {
      $stmt = $db->prepare("UPDATE usuarios SET intentos_fallidos=? WHERE idusuario=?");
      $stmt->bind_param('ii', $int, $u['id']);
      $stmt->execute();
      $stmt->close();
      usleep(200000);
      return 'Usuario o contraseña incorrectos.';
    }
  }

  // OK: limpiar intentos, último login y abrir sesión
  $stmt = $db->prepare("UPDATE usuarios SET intentos_fallidos=0, bloqueado_hasta=NULL, ultimo_login=NOW() WHERE idusuario=?");
  $stmt->bind_param('i', $u['id']);
  $stmt->execute();
  $stmt->close();

  session_regenerate_id(true);
  $_SESSION[GS_UID]  = (int)$u['id'];
  $_SESSION[GS_USER] = $u['usuario'];
  $_SESSION[GS_NAME] = $u['nombre'];
  $_SESSION[GS_ROL]  = mapear_rol((int)$u['IdRol']);

  return '';
}
