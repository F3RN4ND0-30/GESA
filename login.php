<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "municipalidad_pisco");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    $sql = "SELECT * FROM usuarios WHERE usuario = ? AND clave = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $usuario, $clave);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "<script>alert('Inicio de sesión exitoso'); window.location='inicio.php';</script>";
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos'); window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login - Municipalidad de Pisco</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }

body {
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    overflow:hidden;
    background: linear-gradient(-45deg, #0e8a5f, #0a7a50, #12c67d, #0b7f4d);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
}

@keyframes gradientBG {
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}

/* Fondo de burbujas */
.bubbles {
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    overflow:hidden;
    z-index:1;
}
.bubble {
    position:absolute;
    bottom:-150px;
    background: rgba(255,255,255,0.25);
    border-radius:50%;
    animation: rise 10s infinite ease-in;
}
@keyframes rise { 0%{transform:translateY(0) scale(1);opacity:0.7;} 100%{transform:translateY(-1200px) scale(1.2);opacity:0;} }
<?php for($i=1;$i<=25;$i++){
    $left=rand(0,100); $size=rand(15,70); $delay=rand(0,2); $duration=rand(8,13);
    echo ".bubble:nth-child($i){ left: {$left}%; width: {$size}px; height: {$size}px; animation-delay: {$delay}s; animation-duration: {$duration}s; }\n";
} ?>

.container {
    width:950px; 
    height:500px; 
    display:flex; 
    position:relative; 
    z-index:2;
    border-radius:25px; 
    box-shadow:0 0 25px rgba(0,0,0,0.35); 
    overflow:hidden;
    background:white;
}

/* SECCIÓN IZQUIERDA */
.left {
    flex:1; 
    background:white;
    display:flex; 
    justify-content:center; 
    align-items:center; 
    flex-direction:column;
    padding:40px 50px 40px 60px;
    text-align:center; 
    color:#0a3d62; 
    position:relative;
    overflow: visible;
}
.top-logo { position:absolute; top:15px; left:15px; width:120px; opacity:0.95; animation: floatLogo 5s ease-in-out infinite; }
@keyframes floatLogo { 0%,100%{transform:translateY(0);}50%{transform:translateY(-8px);} }
.left img.main-logo { width:200px; margin-bottom:20px; }
.left h2 { font-size:26px; color:#0a3d62; }
.left p { color:#333; font-size:16px; }

/* Imagen de fondo sutil */
.left::before {
    content:"";
    position:absolute;
    top:0; left:0;
    width:100%; height:100%;
    background: url('img/SISTEMA_FONDO.jpg') center/cover no-repeat;
    opacity:0.05;
    z-index:0;
}

/* SECCIÓN DERECHA CON CURVA PINCEL 3D */
.right {
    flex:1.2;
    background:#10aa6a;
    display:flex; 
    justify-content:center; 
    align-items:center; 
    flex-direction:column;
    padding:40px; 
    color:white; 
    position:relative;
    overflow:hidden;
}

/* Ondas tipo pincel 3D con gradiente animado */
.wave {
    position:absolute;
    width:250%;
    height:250%;
    border-radius:50%;
    z-index:0;
    opacity:0.12;
    transform: translate(-50%, -50%);
    background: linear-gradient(120deg, #ffffff, #e0f5ea, #ffffff);
    transition: transform 0.2s ease-out, background 0.2s ease-out;
}

.wave:nth-child(2) { opacity:0.08; }
.wave:nth-child(3) { opacity:0.06; }
.wave:nth-child(4) { opacity:0.05; }

/* LOGIN BOX */
.login-box { 
    width:85%; 
    text-align:center; 
    position:relative; 
    z-index:1; 
    transition: transform 0.2s ease-out;
}
.login-box h3 { color:#fff; font-size:28px; margin-bottom:20px; }
.input-group { margin:15px 0; text-align:left; }
.input-group label { display:block; color:#fff; margin-bottom:5px; font-weight:bold; }
.input-group input {
    width:92%; 
    padding:14px; 
    border:2px solid #0d8a55; 
    border-radius:25px;
    font-size:16px; 
    background:#e6f7f0; 
    color:#0a3d62;
    margin-left:8%;
    transition: transform 0.2s ease-out;
}
.input-group input:focus { border-color:#fff; outline:none; }

.btn {
    width:100%; 
    padding:12px; 
    background:#fff; 
    border:none; 
    border-radius:12px;
    font-size:18px; 
    color:#10aa6a; 
    font-weight:bold; 
    cursor:pointer; 
    transition:0.3s;
}
.btn:hover { background:#e0f5ea; }

.footer { margin-top:20px; color:#fff; }

/* Sombra flotante */
.login-box, .input-group input {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>
</head>
<body>

<div class="bubbles">
    <?php for($i=1;$i<=25;$i++) echo '<div class="bubble"></div>'; ?>
</div>

<div class="container">
    <!-- IZQUIERDA -->
    <div class="left">
        <img src="img/LOGO_MPP_JUNTOS_HAREMOS_HISTORIA.png" alt="Logo Superior" class="top-logo">
        <img src="img/SIMBOLO_IMC.jpg" alt="Logo Nuevo" class="main-logo">
        <h2>Municipalidad Provincial de Pisco</h2>
        <p>Gestión y desarrollo para todos</p>
    </div>

    <!-- DERECHA CURVADA PINCEL 3D -->
    <div class="right">
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="login-box">
            <h3>Iniciar Sesión</h3>
            <form method="POST" action="">
                <div class="input-group">
                    <label>Usuario</label>
                    <input type="text" name="usuario" placeholder="Ingrese su usuario" required>
                </div>
                <div class="input-group">
                    <label>Contraseña</label>
                    <input type="password" name="clave" placeholder="Ingrese su contraseña" required>
                </div>
                <input type="submit" class="btn" value="Entrar">
            </form>
            <div class="footer">
                <p>Si olvidó su contraseña, debe comunicarse con el Área de Sistemas.</p>
            </div>
        </div>
    </div>
</div>

<script>
// Ondas y gradiente animado
const waves = document.querySelectorAll('.wave');
const loginBox = document.querySelector('.login-box');
const inputs = document.querySelectorAll('.input-group input');

document.addEventListener('mousemove', (e)=>{
    const x = e.clientX / window.innerWidth * 100;
    const y = e.clientY / window.innerHeight * 100;

    waves.forEach((wave,i)=>{
        const offset = i*15;
        wave.style.transform = `translate(${x - 50 + offset}px, ${y - 50 + offset}px) scale(${1 + i*0.03})`;
        const r = Math.min(255, Math.round(255 * (x/100)));
        const g = Math.min(255, Math.round(255 * (y/100)));
        const b = 255 - Math.min(255, Math.round(255 * (x/100)));
        wave.style.background = `linear-gradient(120deg, rgba(${r},${g},${b},${i===0?0.12:i===1?0.08:i===2?0.06:0.05}), rgba(255,255,255,0.05))`;
    });

    loginBox.style.transform = `translate(${(x-50)/50}px, ${(y-50)/50}px)`;
    inputs.forEach((input,i)=>{
        input.style.transform = `translate(${(x-50)/100}px, ${(y-50)/100}px)`;
    });
});

let wavePhase = 0;
function animateCurve(){
    wavePhase += 0.02;
    const amplitude = 15; 
    waves.forEach((wave,i)=>{
        const offset = i*10;
        wave.style.borderRadius = `${50 + Math.sin(wavePhase+offset)*amplitude}% / ${50 + Math.cos(wavePhase+offset)*amplitude}%`;
    });
    requestAnimationFrame(animateCurve);
}
animateCurve();
</script>

</body>
</html>
