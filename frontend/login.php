<?php
require_once __DIR__ . '/../backend/php/autenticacion.php';

/* Si ya está logueado, mándalo al escritorio */
redirigir_si_logueado('../frontend/sisvis/escritorio.php');

/* Evita caché del login (importante para atrás/adelante del navegador) */
no_cache();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = hacer_login();
    if ($error === '') {
        header('Location: ../frontend/sisvis/escritorio.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>GESA · Iniciar sesión</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="./img/gesa.svg" />

    <!-- Estilos propios -->
    <link rel="stylesheet" href="../backend/css/login/login.css" />
    <link rel="stylesheet" href="../backend/css/login/loginresponsive.css" />

    <!-- FontAwesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Animate.css para animaciones de SweetAlert -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body <?= $error ? 'data-error="' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '"' : '' ?>>

    <!-- Fondo animado / blobs / burbujas -->
    <div class="bg">
        <div class="bg-gradient"></div>
        <div class="bg-noise"></div>
        <div class="blob b1"></div>
        <div class="blob b2"></div>
        <div class="blob b3"></div>
        <div class="bubbles">
            <?php for ($i = 0; $i < 20; $i++) echo '<span class="bubble"></span>'; ?>
        </div>
    </div>

    <main class="auth">
        <!-- Panel izquierdo con branding e ilustración -->
        <section class="auth-left">
            <header class="brand">
                <h1 class="brand-title">GESA</h1>
                <p class="brand-sub">Gestión de Entradas y Salidas de Almacén</p>
            </header>

            <figure class="illustration">
                <div class="main-icon">
                    <div class="main-icon-bg"></div>
                    <i class="fa-solid fa-cube"></i>
                    <div class="icon-particles">
                        <span class="particle"></span>
                        <span class="particle"></span>
                        <span class="particle"></span>
                        <span class="particle"></span>
                    </div>
                </div>
            </figure>

            <footer class="brand-foot">
                <small>Municipalidad Provincial de Pisco</small>
            </footer>

            <!-- Wave decorativo -->
            <svg class="wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="rgba(16, 170, 106, 0.08)" fill-opacity="1"
                    d="M0,96L48,112C96,128,192,160,288,165.3C384,171,480,149,576,133.3C672,117,768,107,864,122.7C960,139,1056,181,1152,186.7C1248,192,1344,160,1392,144L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
            <svg class="wave wave-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="rgba(16, 170, 106, 0.05)" fill-opacity="1"
                    d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,213.3C672,224,768,224,864,213.3C960,203,1056,181,1152,170.7C1248,160,1344,160,1392,160L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
        </section>

        <!-- Panel derecho con formulario de login -->
        <section class="auth-right">
            <div class="glass">
                <h2>Iniciar sesión</h2>

                <form method="POST" class="form" autocomplete="off" id="formLogin" novalidate>
                    <!-- Token CSRF -->
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars(token_csrf(), ENT_QUOTES, 'UTF-8') ?>">

                    <!-- Campo Usuario -->
                    <label class="field">
                        <span>Usuario</span>
                        <div class="control">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" name="usuario" placeholder="Ingrese su usuario" required minlength="3"
                                autocomplete="username" aria-label="Usuario" autofocus>
                        </div>
                        <small class="field-error"></small>
                    </label>

                    <!-- Campo Contraseña -->
                    <label class="field">
                        <span>Contraseña</span>
                        <div class="control">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" name="clave" placeholder="Ingrese su contraseña" required minlength="4"
                                autocomplete="current-password" aria-label="Contraseña">
                            <button type="button" class="toggle-pass" aria-label="Mostrar u ocultar contraseña">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <small class="field-error"></small>
                    </label>

                    <!-- Botón de envío -->
                    <button class="btn" type="submit">
                        <span>Entrar</span>
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    </button>
                </form>

                <!-- Ayuda -->
                <p class="help">
                    ¿Olvidó su contraseña?<br>
                    <small>Comuníquese con el Área de Sistemas.</small>
                </p>
            </div>
        </section>
    </main>

    <!-- JavaScript personalizado -->
    <script src="../backend/js/login/login.js"></script>
</body>

</html>