// ========== INICIALIZACIÓN ==========
document.addEventListener('DOMContentLoaded', () => {
  inicializarTogglePassword();
  inicializarValidacionFormulario();
  inicializarAnimaciones();
  verificarErrores();
  inicializarFondoInteractivo();
  inicializarEnter();
  inicializarRipple();
  inicializarCapsLock();
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
    if (icono) {
      icono.classList.toggle('fa-eye');
      icono.classList.toggle('fa-eye-slash');
    }

    botonToggle.style.transform = 'scale(0.9)';
    setTimeout(() => (botonToggle.style.transform = 'scale(1)'), 150);
  });
}

// ========== VALIDACIÓN DEL FORMULARIO ==========
function inicializarValidacionFormulario() {
  const formulario = document.getElementById('formLogin');
  if (!formulario) return;

  const inputs = formulario.querySelectorAll('input[required]');

  inputs.forEach((input) => {
    input.addEventListener('input', () => limpiarError(input.closest('.field')));
    input.addEventListener('focus', () => limpiarError(input.closest('.field')));
  });

  formulario.addEventListener('submit', (e) => {
    e.preventDefault();

    let esValido = true;
    inputs.forEach((input) => {
      if (!validarCampo(input)) esValido = false;
    });

    if (!esValido) {
      mostrarToast('warning', 'Complete todos los campos correctamente');
      return;
    }

    // Evitar envíos duplicados
    if (formulario.dataset.enviando === 'true') return;

    formulario.dataset.enviando = 'true';
    mostrarEstadoCarga(true);

    // Enviar al servidor
    formulario.submit();
  });
}

function validarCampo(input) {
  const campo = input.closest('.field');
  const control = campo?.querySelector('.control');
  const mensajeError = campo?.querySelector('.field-error');
  const valor = (input.value || '').trim();

  if (!valor) {
    mostrarError(control, mensajeError, 'Este campo es obligatorio');
    return false;
  }
  if (input.name === 'usuario' && valor.length < 3) {
    mostrarError(control, mensajeError, 'El usuario debe tener al menos 3 caracteres');
    return false;
  }
  if (input.name === 'clave' && valor.length < 4) {
    mostrarError(control, mensajeError, 'La contraseña debe tener al menos 4 caracteres');
    return false;
  }
  limpiarError(campo);
  return true;
}

function mostrarError(control, mensajeError, texto) {
  if (control) {
    control.classList.add('error');
    control.style.animation = 'none';
    setTimeout(() => (control.style.animation = 'shake 0.4s ease'), 10);
  }
  if (mensajeError) {
    mensajeError.textContent = texto;
    mensajeError.classList.add('show');
  }
}

function limpiarError(campo) {
  if (!campo) return;
  const control = campo.querySelector('.control');
  const mensajeError = campo.querySelector('.field-error');
  control?.classList.remove('error');
  if (mensajeError) {
    mensajeError.textContent = '';
    mensajeError.classList.remove('show');
  }
}

function mostrarEstadoCarga(activado) {
  const boton = document.querySelector('.btn');
  if (!boton) return;

  const texto = boton.querySelector('span');
  const icono = boton.querySelector('i');

  if (activado) {
    if (texto) texto.textContent = 'Verificando...';
    if (icono) icono.className = 'fa-solid fa-spinner fa-spin';
    boton.disabled = true;
    boton.setAttribute('aria-busy', 'true');
  } else {
    if (texto) texto.textContent = 'Entrar';
    if (icono) icono.className = 'fa-solid fa-arrow-right-to-bracket';
    boton.disabled = false;
    boton.removeAttribute('aria-busy');
  }
}

// ========== MANEJO DE ERRORES (SweetAlert) ==========
function verificarErrores() {
  const mensajeError = document.body.getAttribute('data-error');
  if (!mensajeError) return;

  let icono = 'error';
  let titulo = 'Error de autenticación';
  if (/bloquead/i.test(mensajeError)) {
    icono = 'warning';
    titulo = 'Cuenta bloqueada';
  } else if (/inactiv/i.test(mensajeError)) {
    icono = 'info';
    titulo = 'Usuario inactivo';
  } else if (/incorrect/i.test(mensajeError)) {
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
    showClass: { popup: 'animate__animated animate__fadeInDown animate__faster' },
    hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' }
  });

  // Restablecer botón si llegó aquí por error al volver a la misma página
  mostrarEstadoCarga(false);

  document.body.removeAttribute('data-error');
}

// ========== TOAST ==========
function mostrarToast(tipo, mensaje) {
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
  Toast.fire({ icon: tipo || 'info', title: mensaje });
}

// ========== ANIMACIONES DE ENTRADA ==========
function inicializarAnimaciones() {
  const elementos = document.querySelectorAll('.brand, .illustration, .glass h2, .form, .help');
  elementos.forEach((el, i) => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    setTimeout(() => {
      el.style.transition = 'all 0.6s ease-out';
      el.style.opacity = '1';
      el.style.transform = 'translateY(0)';
    }, i * 100);
  });
}

// ========== FONDO INTERACTIVO ==========
function inicializarFondoInteractivo() {
  const blobs = document.querySelectorAll('.blob');
  if (!blobs.length) return;
  document.addEventListener('mousemove', (e) => {
    const x = e.clientX / window.innerWidth - 0.5;
    const y = e.clientY / window.innerHeight - 0.5;
    blobs.forEach((blob, i) => {
      const v = (i + 1) * 15;
      blob.style.transform = `translate(${x * v}px, ${y * v}px)`;
    });
  });
}

// ========== ENTER INTELIGENTE ==========
function inicializarEnter() {
  const formulario = document.getElementById('formLogin');
  if (!formulario) return;

  const campos = Array.from(formulario.querySelectorAll('input:not([type="hidden"])'));

  campos.forEach((input) => {
    input.addEventListener('keydown', (e) => {
      if (e.key !== 'Enter') return;
      const idx = campos.indexOf(input);
      const ultimo = idx === campos.length - 1;

      if (ultimo) {
        e.preventDefault();
        // Dispara el submit nativo (cae en nuestro handler de submit)
        if (typeof formulario.requestSubmit === 'function') {
          formulario.requestSubmit();
        } else {
          formulario.dispatchEvent(new Event('submit', { cancelable: true }));
        }
      } else {
        e.preventDefault();
        campos[idx + 1]?.focus();
      }
    });
  });
}

// ========== EFECTO RIPPLE EN BOTÓN ==========
function inicializarRipple() {
  const btn = document.querySelector('.btn');
  if (!btn) return;

  btn.addEventListener('click', function (e) {
    const rect = this.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = e.clientX - rect.left - size / 2;
    const y = e.clientY - rect.top - size / 2;

    const ripple = document.createElement('span');
    ripple.style.cssText = `
      position:absolute;width:${size}px;height:${size}px;left:${x}px;top:${y}px;
      border-radius:50%;background:rgba(255,255,255,0.5);pointer-events:none;
      transform:scale(0);animation:rippleEffect .6s ease-out;
    `;
    this.appendChild(ripple);
    setTimeout(() => ripple.remove(), 600);
  });

  // keyframes (una sola vez)
  if (!document.getElementById('ripple-kf')) {
    const estilo = document.createElement('style');
    estilo.id = 'ripple-kf';
    estilo.textContent = `@keyframes rippleEffect { to { transform: scale(2); opacity: 0; } }`;
    document.head.appendChild(estilo);
  }
}

// ========== DETECTAR CAPS LOCK ==========
function inicializarCapsLock() {
  const pass = document.querySelector('input[name="clave"]');
  if (!pass) return;
  pass.addEventListener('keyup', (e) => {
    if (e.getModifierState && e.getModifierState('CapsLock')) {
      mostrarToast('warning', '⚠️ Mayúsculas activadas');
    }
  });
}
