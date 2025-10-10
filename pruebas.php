<?php
// ==========================================
// Login + burbujas infinitas + logros estilo PS5
// ==========================================

$conexion = new mysqli("localhost", "root", "", "municipalidad_pisco");
if ($conexion->connect_error) die("Error de conexión: " . $conexion->connect_error);

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $usuario=trim($_POST['usuario']);
    $clave=trim($_POST['clave']);
    $sql="SELECT * FROM usuarios WHERE usuario=? AND clave=?";
    $stmt=$conexion->prepare($sql);
    $stmt->bind_param("ss",$usuario,$clave);
    $stmt->execute();
    $resultado=$stmt->get_result();
    if ($resultado->num_rows>0){
        echo "<script>
            playSlySound();
            alert('Inicio de sesión exitoso');
            window.location='inicio.php';
        </script>";
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
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{display:flex;justify-content:center;align-items:center;height:100vh;overflow:hidden;
background:linear-gradient(-45deg,#0e8a5f,#0a7a50,#12c67d,#0b7f4d);
background-size:400% 400%;animation:gradientBG 15s ease infinite;}
@keyframes gradientBG{0%{background-position:0% 50%;}50%{background-position:100% 50%;}100%{background-position:0% 50%;}}

.bubbles{position:absolute;top:0;left:0;width:100%;height:100%;overflow:hidden;z-index:1;}
.bubble{position:absolute;bottom:-150px;background:rgba(255,255,255,0.25);border-radius:50%;
animation:rise linear infinite;cursor:pointer;transition:transform 0.3s,opacity 0.3s;}
.bubble.pop{animation:none;transform:scale(2);opacity:0;}
@keyframes rise{0%{transform:translateY(0) scale(1);opacity:0.7;}100%{transform:translateY(-1200px) scale(1.2);opacity:0;}}

.container{width:950px;height:520px;display:flex;position:relative;z-index:2;border-radius:25px;
box-shadow:0 0 25px rgba(0,0,0,0.35);overflow:hidden;background:white;opacity:0;transform:translateY(20px);
animation:slyContainer 1.5s forwards,slyGlow 4s ease-in-out infinite;}
.left{flex:1.55;position:relative;display:flex;flex-direction:column;justify-content:center;align-items:center;padding:40px;color:white;overflow:hidden;}
.left::before{content:"";position:absolute;top:1;left:1;width:289%;height:180%;
background:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 600"><path d="M0,0 H460 C260,0 850,250 460,450 C240,480 800,560 460,580 C400,600 700,520 460,600 H0 Z" fill="%230e8a5f"/></svg>') no-repeat;background-size:cover;z-index:0;}
.top-logo{position:absolute;top:15px;left:15px;width:130px;opacity:0;animation:fadeInText 1s forwards;animation-delay:0.1s;z-index:2;}
.left img.main-logo{width:220px;margin-bottom:20px;z-index:2;opacity:0;animation:fadeInText 1s forwards;animation-delay:0.3s;}
.left h2{font-size:26px;color:#fff;z-index:2;opacity:0;transform:translateY(20px);animation:fadeInText 1s forwards;animation-delay:0.5s;}
.left p{color:#e9f9f0;font-size:16px;z-index:2;opacity:0;transform:translateY(20px);animation:fadeInText 1s forwards;animation-delay:0.7s;}

.right{flex:1;background:white;display:flex;justify-content:center;align-items:center;flex-direction:column;padding:40px;color:#0a3d62;position:relative;z-index:4;}
.login-box{width:85%;text-align:center;opacity:0;transform:translateY(20px);animation:fadeInText 1s forwards;animation-delay:0.9s;}
.login-box h3{color:#0a7a50;font-size:32px;margin-bottom:25px;}
.input-group{margin:18px 0;text-align:left;}
.input-group label{display:block;color:#0a7a50;margin-bottom:6px;font-weight:bold;font-size:17px;}
.input-group input{width:100%;padding:16px;border:2px solid #0d8a55;border-radius:25px;font-size:17px;background:#e6f7f0;color:#0a3d62;}
.btn{width:100%;padding:14px;background:#10aa6a;border:none;border-radius:12px;font-size:19px;color:white;font-weight:bold;cursor:pointer;transition:0.3s;}
.footer{margin-top:22px;color:#555;font-size:15px;}

@keyframes fadeInText{to{opacity:1;transform:translateY(0);} }
@keyframes slyContainer{to{opacity:1;transform:translateY(0);} }
@keyframes slyGlow{0%{box-shadow:0 0 0 rgba(255,255,255,0);}25%{box-shadow:0 0 25px rgba(255,255,255,0.5);}50%{box-shadow:0 0 40px rgba(255,255,255,0.7);}75%{box-shadow:0 0 25px rgba(255,255,255,0.5);}100%{box-shadow:0 0 0 rgba(255,255,255,0);} }

.achievement{
    position:fixed;bottom:20px;right:20px;padding:12px 18px;border-radius:12px;
    box-shadow:0 0 15px rgba(0,0,0,0.5);color:white;font-weight:bold;font-size:16px;
    opacity:0;transition:opacity 0.5s;z-index:9999;background:linear-gradient(45deg,#0e8a5f,#12c67d);
    display:flex;align-items:center;
}
.achievement .trophy{
    width:40px;height:40px;margin-right:10px;border-radius:50%;background:gold;display:inline-block;
}
</style>
</head>
<body>

<!-- BURBUJAS -->
<div class="bubbles"></div>

<div class="container">
    <div class="left">
        <img src="img/LOGO_MPP_JUNTOS_HAREMOS_HISTORIA.png" alt="Logo Superior" class="top-logo">
        <img src="img/Escudo_de_Pisco.png" alt="Logo Nuevo" class="main-logo">
        <h2>Municipalidad Provincial de Pisco</h2>
        <p>Gestión y desarrollo para todos</p>
    </div>

    <div class="right">
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
                <input type="submit" class="btn" value="Entrar" onclick="playSlySound()">
            </form>
            <div class="footer">
                <p>Si olvidó su contraseña, debe comunicarse con el Área de Sistemas.</p>
            </div>
        </div>
    </div>
</div>

<script>
// BURBUJAS INFINITAS
const bubblesContainer=document.querySelector('.bubbles');
let popCount=0;

function createBubble(){
    const bubble=document.createElement('div');
    const size=Math.floor(Math.random()*80)+15;
    bubble.className='bubble';
    bubble.style.width=size+'px';
    bubble.style.height=size+'px';
    bubble.style.left=Math.random()*100+'%';
    bubble.style.animationDuration=(6+Math.random()*12)+'s';
    bubble.style.borderRadius='50%';
    bubblesContainer.appendChild(bubble);

    bubble.addEventListener('click',()=>{
        bubble.classList.add('pop');
        setTimeout(()=>bubble.remove(),300);
        popCount++;
        if(popCount%10===0) showAchievement(popCount);
    });

    setTimeout(()=>{ bubble.remove(); createBubble(); }, (6+Math.random()*12)*1000);
}

// Generar iniciales 50 burbujas
for(let i=0;i<50;i++) createBubble();

// LOGRO
function showAchievement(count){
    const ach=document.createElement('div');
    ach.className='achievement';
    if(count<100){
        ach.innerHTML=`¡Has reventado ${count} burbujas!`;
    } else {
        ach.innerHTML=`<div class="trophy"></div>Logro Oculto: Llamas mi atención`;
    }
    document.body.appendChild(ach);
    setTimeout(()=>ach.style.opacity='1',50);
    setTimeout(()=>{ ach.style.opacity='0'; setTimeout(()=>ach.remove(),500); },3000);
}

// SONIDO
function playSlySound(){const audio=new Audio('sonidos/sly_click.mp3');audio.play();}

// INACTIVIDAD 1 MINUTO
let idleTime=0;
const idleLimit=60;
['mousemove','keydown','click','scroll'].forEach(e=>document.addEventListener(e,()=>{idleTime=0;}));
setInterval(()=>{idleTime++; if(idleTime>=idleLimit){showIdleMessage(); idleTime=0;}},1000);

function showIdleMessage(){
    const overlay=document.createElement('div');
    overlay.style.position='fixed';overlay.style.top='0';overlay.style.left='0';
    overlay.style.width='100%';overlay.style.height='100%';overlay.style.background='rgba(0,0,0,1)';
    overlay.style.zIndex='9999';document.body.appendChild(overlay);
    const message=document.createElement('div');message.style.position='absolute';
    message.style.top='40%';message.style.left='50%';message.style.transform='translate(-50%,-50%)';
    message.style.padding='25px 35px';message.style.background='#0e8a5f';message.style.color='white';
    message.style.fontSize='20px';message.style.borderRadius='15px';message.style.textAlign='center';
    message.style.boxShadow='0 0 25px rgba(255,255,255,0.6)';message.innerText='¿Estás ahí? Porque veo que estás ausente';
    overlay.appendChild(message);
    setTimeout(()=>{ startSlyAnimation(); playSlySound(); overlay.remove(); },3000);
}

function startSlyAnimation(){
    const dark=document.createElement('div');dark.style.position='fixed';
    dark.style.top='0';dark.style.left='0';dark.style.width='100%';dark.style.height='100%';
    dark.style.background='black';dark.style.opacity='0';dark.style.zIndex='9998';dark.style.transition='opacity 0.5s';
    document.body.appendChild(dark); setTimeout(()=>{dark.style.opacity='1';},50);
    setTimeout(()=>{dark.style.opacity='0'; setTimeout(()=>dark.remove(),500); location.reload();},3000);
}
</script>
</body>
</html>
