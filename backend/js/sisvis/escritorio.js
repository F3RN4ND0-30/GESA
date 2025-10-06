// Inicialización del gráfico de distribución
document.addEventListener("DOMContentLoaded", function () {
  initDistributionChart();
  initCalendar();
  initAnimations();
});

// Gráfico de Avance de Distribución
function initDistributionChart() {
  const canvas = document.getElementById("distributionChart");
  const ctx = canvas.getContext("2d");

  // Configuración del tamaño del canvas
  canvas.width = canvas.offsetWidth;
  canvas.height = 200;

  // Datos de ejemplo
  const data = [
    { month: "Ene", value: 45 },
    { month: "Feb", value: 52 },
    { month: "Mar", value: 48 },
    { month: "Abr", value: 61 },
    { month: "May", value: 58 },
    { month: "Jun", value: 63 },
    { month: "Jul", value: 69 },
    { month: "Ago", value: 66 },
    { month: "Sep", value: 72 },
    { month: "Oct", value: 68 },
  ];

  // Configuración del gráfico
  const padding = 40;
  const chartWidth = canvas.width - padding * 2;
  const chartHeight = canvas.height - padding * 2;
  const maxValue = 100;
  const minValue = 0;

  // Limpiar canvas
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // Dibujar líneas de fondo (grid)
  ctx.strokeStyle = "#e2e8f0";
  ctx.lineWidth = 1;

  for (let i = 0; i <= 4; i++) {
    const y = padding + (chartHeight / 4) * i;
    ctx.beginPath();
    ctx.moveTo(padding, y);
    ctx.lineTo(canvas.width - padding, y);
    ctx.stroke();
  }

  // Calcular puntos
  const points = data.map((item, index) => {
    const x = padding + (chartWidth / (data.length - 1)) * index;
    const y =
      padding +
      chartHeight -
      ((item.value - minValue) / (maxValue - minValue)) * chartHeight;
    return { x, y, value: item.value };
  });

  // Dibujar área bajo la línea
  const gradient = ctx.createLinearGradient(
    0,
    padding,
    0,
    canvas.height - padding
  );
  gradient.addColorStop(0, "rgba(16, 170, 106, 0.3)");
  gradient.addColorStop(1, "rgba(16, 170, 106, 0.05)");

  ctx.fillStyle = gradient;
  ctx.beginPath();
  ctx.moveTo(points[0].x, canvas.height - padding);

  points.forEach((point, index) => {
    if (index === 0) {
      ctx.lineTo(point.x, point.y);
    } else {
      // Curva suave (Bézier)
      const prevPoint = points[index - 1];
      const cpx = (prevPoint.x + point.x) / 2;
      ctx.bezierCurveTo(cpx, prevPoint.y, cpx, point.y, point.x, point.y);
    }
  });

  ctx.lineTo(points[points.length - 1].x, canvas.height - padding);
  ctx.closePath();
  ctx.fill();

  // Dibujar línea principal
  ctx.strokeStyle = "#10aa6a";
  ctx.lineWidth = 3;
  ctx.beginPath();

  points.forEach((point, index) => {
    if (index === 0) {
      ctx.moveTo(point.x, point.y);
    } else {
      const prevPoint = points[index - 1];
      const cpx = (prevPoint.x + point.x) / 2;
      ctx.bezierCurveTo(cpx, prevPoint.y, cpx, point.y, point.x, point.y);
    }
  });

  ctx.stroke();

  // Dibujar puntos
  points.forEach((point) => {
    // Círculo exterior
    ctx.fillStyle = "#10aa6a";
    ctx.beginPath();
    ctx.arc(point.x, point.y, 6, 0, Math.PI * 2);
    ctx.fill();

    // Círculo interior
    ctx.fillStyle = "#ffffff";
    ctx.beginPath();
    ctx.arc(point.x, point.y, 3, 0, Math.PI * 2);
    ctx.fill();
  });

  // Dibujar etiquetas del eje X
  ctx.fillStyle = "#64748b";
  ctx.font = "12px Inter";
  ctx.textAlign = "center";

  data.forEach((item, index) => {
    const x = padding + (chartWidth / (data.length - 1)) * index;
    ctx.fillText(item.month, x, canvas.height - 15);
  });
}

// Funcionalidad del Calendario
function initCalendar() {
  const calendarDays = document.querySelectorAll(
    ".calendar-day:not(.inactive)"
  );

  calendarDays.forEach((day) => {
    day.addEventListener("click", function () {
      // Remover clase 'today' de otros días
      calendarDays.forEach((d) => {
        if (d !== day && !d.classList.contains("today")) {
          d.style.background = "";
          d.style.color = "";
        }
      });

      // Aplicar estilo al día seleccionado si no es 'today'
      if (!this.classList.contains("today")) {
        this.style.background = "var(--primary-light)";
        this.style.color = "var(--primary-color)";
        this.style.fontWeight = "600";
      }
    });
  });

  // Navegación del calendario
  const prevBtn = document.querySelectorAll(".calendar-nav .btn-icon")[0];
  const nextBtn = document.querySelectorAll(".calendar-nav .btn-icon")[1];

  if (prevBtn && nextBtn) {
    prevBtn.addEventListener("click", () => {
      console.log("Mes anterior");
      // Aquí implementarías la lógica para cambiar al mes anterior
    });

    nextBtn.addEventListener("click", () => {
      console.log("Mes siguiente");
      // Aquí implementarías la lógica para cambiar al mes siguiente
    });
  }
}

// Animaciones de entrada
function initAnimations() {
  // Observer para animar elementos cuando entran en el viewport
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = "1";
          entry.target.style.transform = "translateY(0)";
        }
      });
    },
    {
      threshold: 0.1,
    }
  );

  // Aplicar animación a las tarjetas
  const cards = document.querySelectorAll(
    ".stat-card, .main-card, .activity-item"
  );
  cards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(20px)";
    card.style.transition = `all 0.5s ease ${index * 0.1}s`;
    observer.observe(card);
  });
}

// Simulación de actualización en tiempo real
function updateStats() {
  const statNumbers = document.querySelectorAll(".stat-number");

  statNumbers.forEach((stat) => {
    const currentValue = parseInt(stat.textContent);
    const change = Math.floor(Math.random() * 3) - 1; // -1, 0, o 1
    const newValue = Math.max(0, currentValue + change);

    if (change !== 0) {
      animateValue(stat, currentValue, newValue, 500);
    }
  });
}

// Animación de números
function animateValue(element, start, end, duration) {
  const range = end - start;
  const increment = range / (duration / 16);
  let current = start;

  const timer = setInterval(() => {
    current += increment;

    if (
      (increment > 0 && current >= end) ||
      (increment < 0 && current <= end)
    ) {
      element.textContent = end;
      clearInterval(timer);
    } else {
      element.textContent = Math.floor(current);
    }
  }, 16);
}

// Actualizar estadísticas cada 30 segundos (opcional)
// setInterval(updateStats, 30000);

// Manejo del buscador
const searchInput = document.querySelector(".search-bar input");
if (searchInput) {
  searchInput.addEventListener("input", function (e) {
    const searchTerm = e.target.value.toLowerCase();
    console.log("Buscando:", searchTerm);

    // Aquí implementarías la lógica de búsqueda
    // Por ejemplo, filtrar las actividades recientes
    const activities = document.querySelectorAll(".activity-item");
    activities.forEach((activity) => {
      const text = activity.textContent.toLowerCase();
      if (text.includes(searchTerm)) {
        activity.style.display = "flex";
      } else {
        activity.style.display = "none";
      }
    });
  });
}

// Botón Nueva PECOSA
const btnNewPecosa = document.querySelector(".btn-new-pecosa");
if (btnNewPecosa) {
  btnNewPecosa.addEventListener("click", function () {
    console.log("Abriendo formulario de nueva PECOSA");
    // Aquí implementarías la lógica para abrir un modal o redirigir
    alert("Abriendo formulario para nueva PECOSA...");
  });
}

// Navegación de la agenda
const agendaNav = document.querySelectorAll(".agenda-header .btn-icon");
if (agendaNav.length >= 2) {
  agendaNav[0].addEventListener("click", () => {
    console.log("Agenda anterior");
    // Implementar lógica para mostrar eventos anteriores
  });

  agendaNav[1].addEventListener("click", () => {
    console.log("Agenda siguiente");
    // Implementar lógica para mostrar eventos siguientes
  });
}

// Menú de opciones en agenda items
const btnMenus = document.querySelectorAll(".btn-menu");
btnMenus.forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.stopPropagation();
    console.log("Abriendo menú de opciones");
    // Aquí implementarías un menú contextual
  });
});

// Responsive: Redimensionar gráfico
let resizeTimeout;
window.addEventListener("resize", function () {
  clearTimeout(resizeTimeout);
  resizeTimeout = setTimeout(() => {
    initDistributionChart();
  }, 250);
});

// Manejo de enlaces "Ver todo"
const viewAllLinks = document.querySelectorAll(".view-all");
viewAllLinks.forEach((link) => {
  link.addEventListener("click", function (e) {
    e.preventDefault();
    console.log("Ver todas las actividades");
    // Implementar navegación o modal con todas las actividades
  });
});

// Feedback visual al hacer hover en stat cards
const statCards = document.querySelectorAll(".stat-card");
statCards.forEach((card) => {
  card.addEventListener("mouseenter", function () {
    this.style.transform = "translateY(-4px)";
  });

  card.addEventListener("mouseleave", function () {
    this.style.transform = "translateY(0)";
  });
});

console.log("Dashboard GESA inicializado correctamente");
