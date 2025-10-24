<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Cementerio</title>
    <link rel="icon" href="{{ asset('img/logo.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #16a085;
            --light-color: #ecf0f1;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            display: flex;
            max-width: 950px;
            width: 100%;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            background-color: white;
            transition: transform 0.3s ease;
        }
        .login-container:hover {
            transform: scale(1.01);
        }

        .login-left {
            flex: 1;
            background: var(--primary-color);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 150%;
            height: 200%;
            background: rgba(22, 160, 133, 0.15);
            transform: rotate(30deg);
        }

        .login-right {
            flex: 1;
            background-color: var(--light-color);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-bottom: 10px;
            border-radius: 50%;
            border: 3px solid var(--accent-color);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            animation: zoomIn 0.8s ease;
        }
        @keyframes zoomIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
        }

        .welcome-text {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-align: center;
        }
        .subtitle {
            font-size: 1.1rem;
            opacity: 0.85;
            margin-bottom: 30px;
            text-align: center;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 40px 0;
        }
        .features-list li {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .features-list i {
            margin-right: 15px;
            background-color: rgba(255, 255, 255, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .form-title {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 10px;
            text-align: center;
        }
        .form-subtitle {
            color: #6c757d;
            margin-bottom: 35px;
            font-size: 1rem;
            text-align: center;
        }
        .form-control {
            padding: 14px 15px;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(22,160,133,0.25);
        }
        .input-group {
            position: relative;
        }
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 3;
        }

        .btn-login {
            background-color: var(--accent-color);
            border: none;
            color: white;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(22,160,133,0.3);
        }
        .btn-login:hover {
            background-color: #138a72;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(22,160,133,0.4);
        }
        .btn-login:active {
            transform: translateY(0);
        }

        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .register-link {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }
        .register-link:hover {
            text-decoration: underline;
        }

        /* Password toggle */
        .toggle-password {
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .login-left, .login-right {
                padding: 40px 30px;
            }
            .login-left::before {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="logo">
                <img src="{{ asset('img/logo1.png') }}" alt="Logo Cementerio" class="logo-img mb-3">
                <h2 class="logo-text">Sistema Cementerio "Potosí"</h2>
            </div>

            <h2 class="welcome-text">Bienvenido de nuevo</h2>
            <p class="subtitle">Accede a tu cuenta para gestionar el sistema</p>

            <ul class="features-list">
                <li>
                    <i class="fas fa-shield-alt"></i>
                    <span>Sistema seguro y confiable</span>
                </li>
            </ul>

            <div class="mt-4 text-center" style="position: relative; z-index: 2;">
                @if(\App\Models\User::count() === 0)
                    <p>¿Primera vez en el sistema?</p>
                    <a href="{{ route('register.form') }}" class="btn btn-outline-light">
                        <i class="fas fa-user-plus me-2"></i> Crear Cuenta
                    </a>
                @endif
            </div>
        </div>
        <div class="login-right">
            <h3 class="form-title">Iniciar Sesión</h3>
            <p class="form-subtitle">Ingresa tus credenciales para acceder</p>

            @if ($errors->any())
                <div class="alert alert-danger alert-custom">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nombre de usuario</label>
                    <div class="input-group">
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required autofocus>
                        <span class="input-icon">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <span class="input-icon toggle-password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100 py-3 mb-4">
                    <i class="fas fa-sign-in-alt me-2"></i> Ingresar al Sistema
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('password');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>
