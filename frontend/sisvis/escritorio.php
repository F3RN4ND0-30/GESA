<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GESA - Dashboard</title>
    <link rel="stylesheet" href="../../backend/css/sisvis/escritorio.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-left">
            <div class="logo">
                <i class="fas fa-cube"></i>
                <div class="logo-text">
                    <span class="logo-title">GESA</span>
                    <!-- Subtítulo removido para que escale a otras áreas -->
                    <!-- <span class="logo-subtitle">Vaso de Leche - PECOSA</span> -->
                </div>
            </div>

            <div class="nav-menu">
                <a href="#" class="nav-link active">Dashboard</a>
                <a href="../entradas/registro_productos.php" class="nav-link">Entradas</a>
                <a href="#" class="nav-link">PECOSA</a>
                <a href="#" class="nav-link">Almacén</a>
                <a href="#" class="nav-link">Reportes</a>
            </div>
        </div>

        <div class="nav-right">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar PECOSA, insumo o movimiento...">
            </div>

            <!-- Botón oculto en el dashboard: se mostrará en el módulo PECOSA -->
            <!-- <button class="btn-new-pecosa"><i class="fas fa-plus"></i> Nueva PECOSA</button> -->

            <div class="user-profile">
                <div class="user-avatar"><i class="fas fa-user"></i></div>
                <span>Johan</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="main-content">
            <!-- Header -->
            <div class="page-header">
                <div>
                    <h1>Resumen de hoy</h1>
                    <p class="date-text"><i class="far fa-calendar"></i> 06/10/2025</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon entradas"><i class="fas fa-arrow-down"></i></div>
                        <span class="stat-badge updated">Actualizado</span>
                    </div>
                    <h3>Entradas hoy</h3>
                    <p class="stat-number">12</p>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon salidas"><i class="fas fa-arrow-up"></i></div>
                        <span class="stat-badge updated">Actualizado</span>
                    </div>
                    <h3>PECOSA hoy</h3>
                    <p class="stat-number">9</p>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon pendientes"><i class="fas fa-clock"></i></div>
                        <span class="stat-badge updated">Actualizado</span>
                    </div>
                    <h3>PECOSA pendientes</h3>
                    <p class="stat-number">4</p>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon stock"><i class="fas fa-box"></i></div>
                        <span class="stat-badge updated">Actualizado</span>
                    </div>
                    <h3>Stock bajo</h3>
                    <p class="stat-number">7</p>
                </div>
            </div>

            <!-- Main Cards -->
            <div class="cards-grid">
                <!-- PECOSA del mes (con auto-tone activado) -->
                <div class="main-card pecosa-card auto-tone">
                    <div class="card-content">
                        <div class="pecosa-number">130</div>
                        <button class="btn-arrow" title="Ir a reportes"><i class="fas fa-arrow-right"></i></button>
                    </div>
                    <div class="card-footer">
                        <h3>PECOSA del mes</h3>
                        <p>Total documentos generados</p>
                    </div>
                </div>

                <!-- Avance de distribución -->
                <div class="main-card distribution-card">
                    <div class="distribution-header">
                        <div>
                            <h3>Avance de distribución</h3>
                            <p class="percentage">68%</p>
                        </div>
                    </div>
                    <canvas id="distributionChart"></canvas>
                </div>
            </div>

            <!-- Actividades recientes -->
            <div class="recent-activity">
                <div class="section-header">
                    <h2>Actividad Reciente</h2>
                    <a href="#" class="view-all">Ver todo <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon entradas"><i class="fas fa-arrow-down"></i></div>
                        <div class="activity-info">
                            <h4>Nueva entrada registrada</h4>
                            <p>PECOSA #PE-2025-0847 - Leche entera en polvo</p>
                        </div>
                        <span class="activity-time">Hace 5 min</span>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon salidas"><i class="fas fa-arrow-up"></i></div>
                        <div class="activity-info">
                            <h4>PECOSA procesada</h4>
                            <p>Distribución zona norte - 450kg</p>
                        </div>
                        <span class="activity-time">Hace 23 min</span>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon stock"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="activity-info">
                            <h4>Alerta de stock bajo</h4>
                            <p>Avena instantánea - Solo quedan 15kg</p>
                        </div>
                        <span class="activity-time">Hace 1 hora</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Calendario -->
            <div class="calendar-card">
                <div class="calendar-header">
                    <h3>Calendario</h3>
                    <div class="calendar-nav">
                        <button class="btn-icon"><i class="fas fa-chevron-left"></i></button>
                        <button class="btn-icon"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="calendar-month">Octubre 2025</div>
                <div class="calendar-grid">
                    <div class="calendar-day-header">Lun</div>
                    <div class="calendar-day-header">Mar</div>
                    <div class="calendar-day-header">Mié</div>
                    <div class="calendar-day-header">Jue</div>
                    <div class="calendar-day-header">Vie</div>
                    <div class="calendar-day-header">Sáb</div>
                    <div class="calendar-day-header">Dom</div>

                    <div class="calendar-day inactive">29</div>
                    <div class="calendar-day inactive">30</div>
                    <div class="calendar-day">1</div>
                    <div class="calendar-day">2</div>
                    <div class="calendar-day">3</div>
                    <div class="calendar-day">4</div>
                    <div class="calendar-day">5</div>
                    <div class="calendar-day today">6</div>
                    <div class="calendar-day">7</div>
                    <div class="calendar-day">8</div>
                    <div class="calendar-day">9</div>
                    <div class="calendar-day">10</div>
                    <div class="calendar-day">11</div>
                    <div class="calendar-day">12</div>
                    <div class="calendar-day">13</div>
                    <div class="calendar-day">14</div>
                    <div class="calendar-day">15</div>
                    <div class="calendar-day">16</div>
                    <div class="calendar-day">17</div>
                    <div class="calendar-day">18</div>
                    <div class="calendar-day">19</div>
                    <div class="calendar-day">20</div>
                    <div class="calendar-day">21</div>
                    <div class="calendar-day">22</div>
                    <div class="calendar-day">23</div>
                    <div class="calendar-day">24</div>
                    <div class="calendar-day">25</div>
                    <div class="calendar-day">26</div>
                    <div class="calendar-day">27</div>
                    <div class="calendar-day">28</div>
                    <div class="calendar-day">29</div>
                    <div class="calendar-day">30</div>
                    <div class="calendar-day">31</div>
                </div>
            </div>

            <!-- Tu Agenda -->
            <div class="agenda-card">
                <div class="agenda-header">
                    <h3>Tu Agenda</h3>
                    <div class="calendar-nav">
                        <button class="btn-icon"><i class="fas fa-chevron-left"></i></button>
                        <button class="btn-icon"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="agenda-list">
                    <div class="agenda-item">
                        <span class="agenda-tag entradas">Distribución</span>
                        <div class="agenda-info">
                            <h4>Entrega Zona Centro</h4>
                            <p><i class="far fa-clock"></i> Oct 20, 2025 - 09:00 AM</p>
                        </div>
                        <button class="btn-menu"><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                    <div class="agenda-item">
                        <span class="agenda-tag salidas">Inventario</span>
                        <div class="agenda-info">
                            <h4>Revisión de Stock</h4>
                            <p><i class="far fa-clock"></i> Oct 20, 2025 - 09:00 AM</p>
                        </div>
                        <button class="btn-menu"><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                    <div class="agenda-item">
                        <span class="agenda-tag pendientes">Reunión</span>
                        <div class="agenda-info">
                            <h4>Coordinación mensual</h4>
                            <p><i class="far fa-clock"></i> Oct 20, 2025 - 09:00 AM</p>
                        </div>
                        <button class="btn-menu"><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <script src="../../backend/js/sisvis/escritorio.js"></script>
</body>

</html>