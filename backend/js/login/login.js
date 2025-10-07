// ========== INICIALIZACIÓN ==========
document.addEventListener('DOMContentLoaded', () => {
  inicializarTogglePassword();
  inicializarValidacionFormulario();
  inicializarAnimaciones();
  verificarErrores();
  inicializarFondoInteractivo();
});

// ========== MOSTRAR/OCULTAR CONTRASEÑA ==========
function inicializarTogglePassword() {
  const botonToggle = document.querySelector('.toggle-pass');
  const inputPassword = document.querySelector('input[name="clave"]');
  
  if (!botonToggle || !inputPassword) return;
  
  botonToggle.addEventListener('click', () => {
    const esPassword = inputPassword.type === 'password';
    inputPassword.type = esPassword ? 'text' : 'password';
    
    const icono = botonToggle.querySelector('i');
    icono.classList.toggle('fa-eye');
    icono.classList.toggle('fa-eye-slash');
    
    // Animación suave del botón
    botonToggle.style.transform = 'scale(0.9)';
    setTimeout(() => {
      botonToggle.style.transform = 'scale(1)';
    }, 150);
  });
}

// ========== VALIDACIÓN DEL FORMULARIO ==========
function inicializarValidacionFormulario() {
  const formulario = document.getElementById('formLogin');
  if (!formulario) return;
  
  const inputs = formulario.querySelectorAll('input[required]');
  
  // Validación solo al enviar el formulario (no en blur)
  inputs.forEach(input => {
    // Limpiar error al escribir
    input.addEventListener('input', () => {
      const campo = input.closest('.field');
      limpiarError(campo);
    });
    
    // Limpiar error al enfocar
    input.addEventListener('focus', () => {
      const campo = input.closest('.field');
      limpiarError(campo);
    });
  });
  
  // Validación al enviar
  formulario.addEventListener('submit', (e) => {
    e.preventDefault();
    
    let esValido = true;
    
    inputs.forEach(input => {
      if (!validarCampo(input)) {
        esValido = false;
      }
    });
    
    if (!esValido) {
      mostrarToast('warning', 'Complete todos los campos correctamente');
    } else {
      // Prevenir múltiples envíos
      if (formulario.dataset.enviando === 'true') {
        return;
      }
      
      formulario.dataset.enviando = 'true';
      mostrarEstadoCarga();
      
      // Enviar el formulario
      formulario.submit();
    }
  });
}

// Validar campo individual
function validarCampo(input) {
  const campo = input.closest('.field');
  const control = campo.querySelector('.control');
  const mensajeError = campo.querySelector('.field-error');
  const valor = input.value.trim();
  
  // Validar si está vacío
  if (!valor) {
    mostrarError(control, mensajeError, 'Este campo es obligatorio');
    return false;
  }
  
  // Validar usuario (mínimo 3 caracteres)
  if (input.name === 'usuario' && valor.length < 3) {
    mostrarError(control, mensajeError, 'El usuario debe tener al menos 3 caracteres');
    return false;
  }
  
  // Validar contraseña (mínimo 4 caracteres)
  if (input.name === 'clave' && valor.length < 4) {
    mostrarError(control, mensajeError, 'La contraseña debe tener al menos 4 caracteres');
    return false;
  }
  
  limpiarError(campo);
  return true;
}

function mostrarError(control, mensajeError, texto) {
  control.classList.add('error');
  
  if (mensajeError) {
    mensajeError.textContent = texto;
    mensajeError.classList.add('show');
  }
  
  // Animación de shake
  control.style.animation = 'none';
  setTimeout(() => {
    control.style.animation = 'shake 0.4s ease';
  }, 10);
}

function limpiarError(campo) {
  const control = campo.querySelector('.control');
  const mensajeError = campo.querySelector('.field-error');
  
  control.classList.remove('error');
  
  if (mensajeError) {
    mensajeError.textContent = '';
    mensajeError.classList.remove('show');
  }
}

// Estado de carga
function mostrarEstadoCarga() {
  const boton = document.querySelector('.btn');
  const textoBoton = boton.querySelector('span');
  const iconoBoton = boton.querySelector('i');
  
  textoBoton.textContent = 'Verificando...';
  iconoBoton.className = 'fa-solid fa-spinner fa-spin';
  boton.disabled = true;
}

// ========== MANEJO DE ERRORES ==========
function verificarErrores() {
  const mensajeError = document.body.getAttribute('data-error');
  
  if (mensajeError) {
    // Determinar el tipo de error
    let icono = 'error';
    let titulo = 'Error de autenticación';
    
    if (mensajeError.includes('bloqueada') || mensajeError.includes('bloqueado')) {
      icono = 'warning';
      titulo = 'Cuenta bloqueada';
    } else if (mensajeError.includes('inactivo')) {
      icono = 'info';
      titulo = 'Usuario inactivo';
    } else if (mensajeError.includes('incorrectos')) {
      icono = 'error';
      titulo = 'Credenciales incorrectas';
    }
    
    Swal.fire({
      icon: icono,
      title: titulo,
      text: mensajeError,
      confirmButtonText: 'Entendido',
      confirmButtonColor: '#10aa6a',
      background: 'rgba(255, 255, 255, 0.98)',
      backdrop: 'rgba(12, 122, 83, 0.5)',
      showClass: {
        popup: 'animate__animated animate__fadeInDown animate__faster'
      },
      hideClass: {
        popup: 'animate__animated animate__fadeOutUp animate__faster'
      }
    });
    
    // Limpiar el atributo para que no se muestre de nuevo
    document.body.removeAttribute('data-error');
  }
}

// ========== TOAST PERSONALIZADO ==========
function mostrarToast(tipo, mensaje) {
  const iconos = {
    success: 'success',
    error: 'error',
    warning: 'warning',
    info: 'info'
  };
  
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    background: 'rgba(255, 255, 255, 0.98)',
    backdrop: false,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer);
      toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
  });
  
  Toast.fire({
    icon: iconos[tipo] || 'info',
    title: mensaje
  });
}

// ========== ANIMACIONES DE ENTRADA ==========
function inicializarAnimaciones() {
  // Animar elementos al cargar
  const elementos = document.querySelectorAll('.brand, .illustration, .glass h2, .form, .help');
  
  elementos.forEach((elemento, indice) => {
    elemento.style.opacity = '0';
    elemento.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
      elemento.style.transition = 'all 0.6s ease-out';
      elemento.style.opacity = '1';
      elemento.style.transform = 'translateY(0)';
    }, indice * 100);
  });
  
  // Efecto parallax suave en el icono de almacén
  const iconoAlmacen = document.querySelector('.warehouse-icon i');
  if (iconoAlmacen) {
    document.addEventListener('mousemove', (e) => {
      const x = (e.clientX / window.innerWidth - 0.5) * 15;
      const y = (e.clientY / window.innerHeight - 0.5) * 15;
      iconoAlmacen.style.transform = `translate(${x}px, ${y}px)`;
    });
  }
}

// ========== FONDO INTERACTIVO ==========
function inicializarFondoInteractivo() {
  const blobs = document.querySelectorAll('.blob');
  
  // Hacer que los blobs sigan el cursor sutilmente
  document.addEventListener('mousemove', (e) => {
    const x = e.clientX / window.innerWidth;
    const y = e.clientY / window.innerHeight;
    
    blobs.forEach((blob, indice) => {
      const velocidad = (indice + 1) * 15;
      const moveX = (x - 0.5) * velocidad;
      const moveY = (y - 0.5) * velocidad;
      
      blob.style.transform = `translate(${moveX}px, ${moveY}px)`;
    });
  });
}

// ========== AUTOCOMPLETADO Y ENTER ==========
document.querySelectorAll('input').forEach(input => {
  input.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      const formulario = input.closest('form');
      if (formulario) {
        e.preventDefault();
        
        // Si es el último input, enviar el formulario
        const inputs = Array.from(formulario.querySelectorAll('input:not([type="hidden"])'));
        const indiceActual = inputs.indexOf(input);
        
        if (indiceActual === inputs.length - 1) {
          formulario.querySelector('.btn')?.click();
        } else {
          // Mover al siguiente input
          inputs[indiceActual + 1]?.focus();
        }
      }
    }
  });
});

// ========== EFECTOS VISUALES ADICIONALES ==========
// Efecto ripple en el botón
document.querySelector('.btn')?.addEventListener('click', function(e) {
  const ripple = document.createElement('span');
  const rect = this.getBoundingClientRect();
  const size = Math.max(rect.width, rect.height);
  const x = e.clientX - rect.left - size / 2;
  const y = e.clientY - rect.top - size / 2;
  
  ripple.style.cssText = `
    position: absolute;
    width: ${size}px;
    height: ${size}px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    left: ${x}px;
    top: ${y}px;
    pointer-events: none;
    transform: scale(0);
    animation: rippleEffect 0.6s ease-out;
  `;
  
  this.appendChild(ripple);
  
  setTimeout(() => ripple.remove(), 600);
});

// Agregar animación ripple
const estiloRipple = document.createElement('style');
estiloRipple.textContent = `
  @keyframes rippleEffect {
    to {
      transform: scale(2);
      opacity: 0;
    }
  }
`;
document.head.appendChild(estiloRipple);

// ========== DETECTAR CAPS LOCK ==========
document.querySelector('input[name="clave"]')?.addEventListener('keyup', (e) => {
  if (e.getModifierState && e.getModifierState('CapsLock')) {
    mostrarToast('warning', '⚠️ Mayúsculas activadas');
  }
});
