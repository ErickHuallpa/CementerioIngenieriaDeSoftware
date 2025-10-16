<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Cementerio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @livewireStyles
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #16a085;
            --light-color: #ecf0f1;
        }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { background: linear-gradient(180deg, var(--primary-color), var(--secondary-color)); color: white; min-height: 100vh; box-shadow: 3px 0 10px rgba(0,0,0,0.1); }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 12px 20px; border-radius: 5px; transition: 0.3s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: rgba(255,255,255,0.1); color: white; }
        .main-content { padding: 20px; }
        .header { background-color: white; border-radius: 10px; padding: 15px 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card-dashboard { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.3s; margin-bottom: 20px; }
        .card-dashboard:hover { transform: translateY(-5px); }
        .card-icon { font-size: 2.5rem; color: var(--accent-color); }
        .stats-number { font-size: 2rem; font-weight: bold; color: var(--primary-color); }
        .stats-label { color: #6c757d; font-size: 0.9rem; }
        .btn-primary-custom { background-color: var(--accent-color); border-color: var(--accent-color); color: white; }
        .btn-primary-custom:hover { background-color: #138a72; border-color: #138a72; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background-color: var(--accent-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
        .table th { background-color: var(--primary-color); color: white; border: none; }
        .badge-status { padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; }
        .badge-available { background-color: #d4edda; color: #155724; }
        .badge-occupied { background-color: #f8d7da; color: #721c24; }
        .badge-reserved { background-color: #fff3cd; color: #856404; }
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
                        <li><a href="{{ route('dashboard') }}" class="nav-link active"><i class="fas fa-tachometer-alt"></i> Panel Principal</a></li>
                        <li><a href="#" class="nav-link"><i class="fas fa-map-marked-alt"></i> Mapa de Nichos</a></li>
                        <li><a href="{{ route('difunto.index') }}" class="nav-link"><i class="fas fa-book"></i> Registro de Difuntos</a></li>
                        <li><a href="{{ route('clientes.index') }}" class="nav-link"><i class="fas fa-users"></i> Clientes</a></li>
                        <li><a href="#" class="nav-link"><i class="fas fa-file-invoice-dollar"></i> Pagos</a></li>
                        <li><a href="#" class="nav-link"><i class="fas fa-chart-bar"></i> Reportes</a></li>
                    </ul>

                    <div class="mt-auto pt-3 border-top border-light">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-3">{{ substr($usuario->name, 0, 1) }}</div>
                            <div>
                                <div class="fw-bold">{{ $usuario->name }}</div>
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
                    <a href="{{ route('pabellon.create') }}" class="btn btn-primary-custom">
                        <i class="fas fa-plus-circle me-1"></i> Registrar Pabellón
                    </a>
                    <a href="{{ route('nicho.create') }}" class="btn btn-success">
                        <i class="fas fa-layer-group me-1"></i> Registrar Nicho
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
                        </button>
                    </form>
                </div>

                <!-- Dashboard Stats -->
                <div class="row">
                    <div class="col-md-3"><div class="card card-dashboard"><div class="card-body"><div class="d-flex justify-content-between"><div><div class="stats-number">{{ $totalNichos }}</div><div class="stats-label">Nichos Totales</div></div><div class="card-icon"><i class="fas fa-monument"></i></div></div></div></div></div>
                    <div class="col-md-3"><div class="card card-dashboard"><div class="card-body"><div class="d-flex justify-content-between"><div><div class="stats-number">{{ $nichosOcupados }}</div><div class="stats-label">Ocupados</div></div><div class="card-icon"><i class="fas fa-cross"></i></div></div></div></div></div>
                    <div class="col-md-3"><div class="card card-dashboard"><div class="card-body"><div class="d-flex justify-content-between"><div><div class="stats-number">{{ $nichosDisponibles }}</div><div class="stats-label">Disponibles</div></div><div class="card-icon"><i class="fas fa-square"></i></div></div></div></div></div>
                    <div class="col-md-3"><div class="card card-dashboard"><div class="card-body"><div class="d-flex justify-content-between"><div><div class="stats-number">{{ $nichosReservados }}</div><div class="stats-label">Reservados</div></div><div class="card-icon"><i class="fas fa-bookmark"></i></div></div></div></div></div>
                </div>

                <!-- Tabla de Nichos -->
                <div class="card card-dashboard mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Últimos Nichos Registrados</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Pabellón</th>
                                        <th>Fila</th>
                                        <th>Columna</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($listaNichos as $nicho)
                                        <tr>
                                            <td>#{{ $nicho->id_nicho }}</td>
                                            <td>{{ $nicho->pabellon->nombre ?? '—' }}</td>
                                            <td>{{ $nicho->fila }}</td>
                                            <td>{{ $nicho->columna }}</td>
                                            <td>
                                                @if ($nicho->estado == 'Disponible')
                                                    <span class="badge badge-status badge-available">Disponible</span>
                                                @elseif ($nicho->estado == 'Ocupado')
                                                    <span class="badge badge-status badge-occupied">Ocupado</span>
                                                @elseif ($nicho->estado == 'Reservado')
                                                    <span class="badge badge-status badge-reserved">Reservado</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $nicho->estado }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-3 text-muted">No hay nichos registrados aún.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
