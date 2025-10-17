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
        .btn-primary-custom { background-color: var(--accent-color); border-color: var(--accent-color); color: white; }
        .btn-primary-custom:hover { background-color: #138a72; border-color: #138a72; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background-color: var(--accent-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
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
                        <li><a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> Panel Principal</a></li>
                        <li><a href="{{ route('users.index') }}" class="nav-link"><i class="fas fa-user-tie"></i> Personal</a></li>
                        <li><a href="#" class="nav-link"><i class="fas fa-map-marked-alt"></i> Mapa de Nichos</a></li>
                        <li><a href="{{ route('difunto.index') }}" class="nav-link"><i class="fas fa-book"></i> Registro de Difuntos</a></li>
                        <li><a href="{{ route('clientes.index') }}" class="nav-link"><i class="fas fa-users"></i> Clientes</a></li>
                        <li><a href="#" class="nav-link"><i class="fas fa-file-invoice-dollar"></i> Pagos</a></li>
                        <li><a href="#" class="nav-link"><i class="fas fa-chart-bar"></i> Reportes</a></li>
                    </ul>

                    <div class="mt-auto pt-3 border-top border-light">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-3">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</div>
                            <div>
                                <div class="fw-bold">{{ auth()->user()->name ?? 'Usuario' }}</div>
                                <small class="text-light">Administrador</small>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100">
                                <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-lg-10 main-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>
</html>
