<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Cementerio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @livewireStyles
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #16a085;
            --light-color: #ecf0f1;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            min-height: 100vh;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            padding: 20px;
        }

        .header {
            background-color: white;
            border-radius: 10px;
            padding: 15px 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .card-dashboard {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            margin-bottom: 20px;
        }

        .card-dashboard:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            font-size: 2.5rem;
            color: var(--accent-color);
        }

        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .btn-primary-custom {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }

        .btn-primary-custom:hover {
            background-color: #138a72;
            border-color: #138a72;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .recent-activity {
            max-height: 300px;
            overflow-y: auto;
        }

        .activity-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .table th {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .badge-available {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-occupied {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-reserved {
            background-color: #fff3cd;
            color: #856404;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="d-flex flex-column p-3">
                    <div class="text-center mb-4 mt-2">
                        <h4><i class="fas fa-monument me-2"></i>Sistema Cementerio</h4>
                        <hr class="bg-light">
                    </div>

                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="fas fa-tachometer-alt"></i> Panel Principal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-map-marked-alt"></i> Mapa de Nichos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-book"></i> Registro de Difuntos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-users"></i> Clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-file-invoice-dollar"></i> Pagos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-chart-bar"></i> Reportes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-cog"></i> Configuración
                            </a>
                        </li>
                    </ul>

                    <div class="mt-auto pt-3 border-top border-light">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-3">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold">{{ Auth::user()->name }}</div>
                                <small class="text-light">Administrador</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Header -->
                <div class="header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">Panel Principal</h2>
                        <p class="text-muted mb-0">Bienvenido al sistema de gestión del cementerio</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-bell text-muted fs-5"></i>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Dashboard Stats -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card card-dashboard">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="stats-number">245</div>
                                        <div class="stats-label">Nichos Totales</div>
                                    </div>
                                    <div class="card-icon">
                                        <i class="fas fa-monument"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-dashboard">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="stats-number">187</div>
                                        <div class="stats-label">Nichos Ocupados</div>
                                    </div>
                                    <div class="card-icon">
                                        <i class="fas fa-cross"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-dashboard">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="stats-number">42</div>
                                        <div class="stats-label">Nichos Disponibles</div>
                                    </div>
                                    <div class="card-icon">
                                        <i class="fas fa-square"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-dashboard">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="stats-number">16</div>
                                        <div class="stats-label">Nichos Reservados</div>
                                    </div>
                                    <div class="card-icon">
                                        <i class="fas fa-bookmark"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Row -->
                <div class="row mt-4">
                    <!-- Recent Activity -->
                    <div class="col-lg-6">
                        <div class="card card-dashboard">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">Actividad Reciente</h5>
                            </div>
                            <div class="card-body recent-activity">
                                <div class="activity-item">
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-bold">Nuevo registro de difunto</div>
                                        <div class="activity-time">Hace 2 horas</div>
                                    </div>
                                    <div class="text-muted">María González registrada en el nicho B-12</div>
                                </div>
                                <div class="activity-item">
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-bold">Pago realizado</div>
                                        <div class="activity-time">Hace 5 horas</div>
                                    </div>
                                    <div class="text-muted">Familia Pérez - Mantenimiento anual</div>
                                </div>
                                <div class="activity-item">
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-bold">Reserva de nicho</div>
                                        <div class="activity-time">Ayer</div>
                                    </div>
                                    <div class="text-muted">Nicho C-07 reservado por Familia Rodríguez</div>
                                </div>
                                <div class="activity-item">
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-bold">Actualización de datos</div>
                                        <div class="activity-time">Ayer</div>
                                    </div>
                                    <div class="text-muted">Información de contacto actualizada para Familia López</div>
                                </div>
                                <div class="activity-item">
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-bold">Nuevo usuario registrado</div>
                                        <div class="activity-time">25 Jun 2023</div>
                                    </div>
                                    <div class="text-muted">Carlos Mendoza agregado al sistema</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nichos Status -->
                    <div class="col-lg-6">
                        <div class="card card-dashboard">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Estado de Nichos</h5>
                                <button class="btn btn-sm btn-primary-custom">
                                    <i class="fas fa-plus me-1"></i> Agregar Niche
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Niche ID</th>
                                                <th>Ubicación</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#N-0012</td>
                                                <td>Sección A, Fila 3</td>
                                                <td><span class="badge badge-status badge-occupied">Ocupado</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>#N-0025</td>
                                                <td>Sección B, Fila 1</td>
                                                <td><span class="badge badge-status badge-available">Disponible</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>#N-0038</td>
                                                <td>Sección C, Fila 2</td>
                                                <td><span class="badge badge-status badge-reserved">Reservado</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>#N-0042</td>
                                                <td>Sección A, Fila 4</td>
                                                <td><span class="badge badge-status badge-occupied">Ocupado</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>#N-0056</td>
                                                <td>Sección B, Fila 3</td>
                                                <td><span class="badge badge-status badge-available">Disponible</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-dashboard">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">Acciones Rápidas</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 text-center mb-3">
                                        <button class="btn btn-primary-custom p-3 rounded-circle">
                                            <i class="fas fa-user-plus fa-2x"></i>
                                        </button>
                                        <div class="mt-2">Registrar Difunto</div>
                                    </div>
                                    <div class="col-md-3 text-center mb-3">
                                        <button class="btn btn-primary-custom p-3 rounded-circle">
                                            <i class="fas fa-map-marked-alt fa-2x"></i>
                                        </button>
                                        <div class="mt-2">Ver Mapa</div>
                                    </div>
                                    <div class="col-md-3 text-center mb-3">
                                        <button class="btn btn-primary-custom p-3 rounded-circle">
                                            <i class="fas fa-file-invoice-dollar fa-2x"></i>
                                        </button>
                                        <div class="mt-2">Registrar Pago</div>
                                    </div>
                                    <div class="col-md-3 text-center mb-3">
                                        <button class="btn btn-primary-custom p-3 rounded-circle">
                                            <i class="fas fa-chart-pie fa-2x"></i>
                                        </button>
                                        <div class="mt-2">Generar Reporte</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
