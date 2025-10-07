<?php
/* =================== CONFIG =================== */
/* Activa/desactiva el bloqueo temporal */
define('GS_BLOQUEO_ACTIVO', true);   // ← pon false cuando estés probando
define('GS_MAX_INTENTOS', 5);
define('GS_MINUTOS_BLOQ', 3);

/* DEV: permite resetear bloqueos con ?gs_unlock=1 */
define('GS_DEV', true);

/* ===== Sesión con nombre propio y prefijo gs_ ===== */
session_name('gs_session');
$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $https,
    'httponly' => true,
    'samesite' => 'Lax'
]);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

define('GS_UID', 'gs_uid');
define('GS_USER', 'gs_usuario');
define('GS_NAME', 'gs_nombre');
define('GS_ROL', 'gs_rol');

/* ================= CONEXIÓN =================== */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $db = new mysqli('localhost', 'root', '', 'gesa');
    $db->set_charset('utf8mb4');
} catch (Throwable $e) {
    http_response_code(500);
    exit('Error de base de datos.');
}

/* ================ UTILIDADES ================== */
function gs_csrf_token(): string
{
    if (empty($_SESSION['gs_csrf'])) $_SESSION['gs_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['gs_csrf'];
}
function gs_csrf_check($t): bool
{
    return isset($_SESSION['gs_csrf']) && hash_equals($_SESSION['gs_csrf'], (string)$t);
}

function gs_is_login(): bool
{
    return !empty($_SESSION[GS_UID]);
}
function gs_require_login(): void
{
    if (!gs_is_login()) {
        header('Location: /frontend/login.php');
        exit;
    }
}
function gs_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

/* Mapear IdRol → nombre; dejamos tu FK tal cual */
function gs_map_rol(int $idRol): string
{
    return $idRol === 1 ? 'admin' : ($idRol === 2 ? 'operador' : 'consulta');
}

/* DEV: reset rápido de bloqueos si estás probando */
if (GS_DEV && isset($_GET['gs_unlock'])) {
    $db->query("UPDATE usuarios SET intentos_fallidos=0, bloqueado_hasta=NULL");
    echo "🔓 Bloqueos reseteados (DEV).";
    exit;
}

/* =============== LOGIN HANDLER ================= */
/* Llama esto en login.php si POST; retorna '' si ok o mensaje de error */
function gs_do_login(): string
{
    global $db;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return '';

    if (!gs_csrf_check($_POST['csrf'] ?? '')) return 'Solicitud inválida, recargue la página.';

    $usuario = trim($_POST['usuario'] ?? '');
    $claveIn = (string)($_POST['clave'] ?? '');
    if ($usuario === '' || $claveIn === '') return 'Complete usuario y contraseña.';

    // Traer usuario
    $stmt = $db->prepare("
    SELECT u.idusuario AS id, u.usuario, u.clave, u.IdRol,
           COALESCE(u.estado,1) AS estado,
           COALESCE(u.intentos_fallidos,0) AS intentos_fallidos,
           u.bloqueado_hasta, u.nombre
    FROM usuarios u
    WHERE u.usuario = ? LIMIT 1
  ");
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $res = $stmt->get_result();
    $u = $res->fetch_assoc();
    $stmt->close();

    if (!$u) {
        usleep(200000);
        return 'Usuario o contraseña incorrectos.';
    }

    // Bloqueo temporal (si está activo)
    if (GS_BLOQUEO_ACTIVO && !empty($u['bloqueado_hasta']) && strtotime($u['bloqueado_hasta']) > time()) {
        return 'Cuenta bloqueada temporalmente. Intente más tarde.';
    }
    if ((int)$u['estado'] !== 1) return 'Usuario inactivo.';

    // Verificar contraseña (bcrypt en columna "clave")
    if (!password_verify($claveIn, $u['clave'])) {
        $int = min(255, (int)$u['intentos_fallidos'] + 1);

        if (GS_BLOQUEO_ACTIVO && $int >= GS_MAX_INTENTOS) {
            $hasta = date('Y-m-d H:i:s', time() + GS_MINUTOS_BLOQ * 60);
            $stmt = $db->prepare("UPDATE usuarios SET intentos_fallidos=?, bloqueado_hasta=? WHERE idusuario=?");
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

    // OK: limpia intentos y abre sesión
    $stmt = $db->prepare("UPDATE usuarios SET intentos_fallidos=0, bloqueado_hasta=NULL, ultimo_login=NOW() WHERE idusuario=?");
    $stmt->bind_param('i', $u['id']);
    $stmt->execute();
    $stmt->close();

    session_regenerate_id(true);
    $_SESSION[GS_UID]  = (int)$u['id'];
    $_SESSION[GS_USER] = $u['usuario'];
    $_SESSION[GS_NAME] = $u['nombre'];
    $_SESSION[GS_ROL]  = gs_map_rol((int)$u['IdRol']);
    return '';
}
