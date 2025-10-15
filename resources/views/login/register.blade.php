<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Cementerio</title>
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

        .register-container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        .register-left {
            flex: 1;
            background: linear-gradient(135deg, var(--accent-color) 0%, #1abc9c 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .register-right {
            flex: 1;
            background-color: white;
            padding: 40px;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            font-size: 2.5rem;
            margin-right: 15px;
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .welcome-text {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 30px;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .features-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .features-list i {
            margin-right: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 5px;
        }

        .form-subtitle {
            color: #6c757d;
            margin-bottom: 30px;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(22, 160, 133, 0.25);
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
        }

        .btn-register {
            background-color: var(--accent-color);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-register:hover {
            background-color: #138a72;
            transform: translateY(-2px);
        }

        .password-strength {
            height: 5px;
            border-radius: 5px;
            margin-top: 5px;
            background-color: #e9ecef;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s;
        }

        .strength-weak {
            background-color: #dc3545;
        }

        .strength-medium {
            background-color: #ffc107;
        }

        .strength-strong {
            background-color: #28a745;
        }

        .alert-custom {
            border-radius: 8px;
            border: none;
            padding: 12px 15px;
        }

        .login-link {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
            }

            .register-left {
                padding: 30px;
            }

            .register-right {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Left Panel with Information -->
        <div class="register-left">
            <div class="logo">
                <i class="fas fa-monument logo-icon"></i>
                <div class="logo-text">Sistema Cementerio</div>
            </div>

            <h2 class="welcome-text">Crea tu cuenta</h2>
            <p class="subtitle">Únete a nuestro sistema de gestión y administra los registros del cementerio de manera eficiente.</p>

            <ul class="features-list">
                <li>
                    <i class="fas fa-check"></i>
                    <span>Gestión completa de nichos y difuntos</span>
                </li>

                <li>
                    <i class="fas fa-check"></i>
                    <span>Interfaz intuitiva y fácil de usar</span>
                </li>
            </ul>

            <div class="mt-4">
                <p>¿Ya tienes una cuenta?</p>
                <a href="{{ route('login') }}" class="btn btn-outline-light">
                    <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                </a>
            </div>
        </div>

        <!-- Right Panel with Registration Form -->
        <div class="register-right">
            <h3 class="form-title">Registro de Usuario</h3>
            <p class="form-subtitle">Completa la información para crear tu cuenta</p>

            @if ($errors->any())
                <div class="alert alert-danger alert-custom mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" id="registerForm">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold">Nombre de usuario</label>
                    <div class="input-group">
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required autofocus>
                        <span class="input-icon">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                    <div class="input-group">
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                        <span class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <span class="input-icon toggle-password" style="cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="password-strength mt-2">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                    <small class="text-muted" id="passwordStrengthText">La contraseña debe tener al menos 8 caracteres</small>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirmar contraseña</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        <span class="input-icon">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <small class="text-muted" id="passwordMatchText"></small>
                </div>

                <button type="submit" class="btn btn-register w-100 py-3 mt-3">
                    <i class="fas fa-user-plus me-2"></i> Crear Cuenta
                </button>
            </form>

            <div class="text-center mt-4">
                <small>¿Ya tienes cuenta? <a href="{{ route('login') }}" class="login-link">Inicia sesión aquí</a></small>
            </div>

            <div class="mt-4 pt-3 border-top text-center">
                <small class="text-muted">Al registrarte, aceptas nuestros <a href="#" class="login-link">Términos de Servicio</a> y <a href="#" class="login-link">Política de Privacidad</a>.</small>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggle
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('password');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            // Password strength indicator
            const passwordStrengthBar = document.getElementById('passwordStrengthBar');
            const passwordStrengthText = document.getElementById('passwordStrengthText');

            document.getElementById('password').addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let text = '';
                let barClass = '';

                if (password.length >= 8) strength += 25;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
                if (password.match(/\d/)) strength += 25;
                if (password.match(/[^a-zA-Z\d]/)) strength += 25;

                passwordStrengthBar.style.width = strength + '%';

                if (strength < 50) {
                    barClass = 'strength-weak';
                    text = 'Contraseña débil';
                } else if (strength < 75) {
                    barClass = 'strength-medium';
                    text = 'Contraseña media';
                } else {
                    barClass = 'strength-strong';
                    text = 'Contraseña fuerte';
                }

                passwordStrengthBar.className = 'password-strength-bar ' + barClass;
                passwordStrengthText.textContent = text;
            });

            // Password confirmation check
            const passwordConfirmation = document.getElementById('password_confirmation');
            const passwordMatchText = document.getElementById('passwordMatchText');

            passwordConfirmation.addEventListener('input', function() {
                const password = document.getElementById('password').value;
                const confirmPassword = this.value;

                if (confirmPassword === '') {
                    passwordMatchText.textContent = '';
                } else if (password === confirmPassword) {
                    passwordMatchText.textContent = 'Las contraseñas coinciden';
                    passwordMatchText.className = 'text-success';
                } else {
                    passwordMatchText.textContent = 'Las contraseñas no coinciden';
                    passwordMatchText.className = 'text-danger';
                }
            });

            // Form validation
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;

                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Las contraseñas no coinciden. Por favor, verifica e intenta nuevamente.');
                    return false;
                }

                if (password.length < 8) {
                    e.preventDefault();
                    alert('La contraseña debe tener al menos 8 caracteres.');
                    return false;
                }
            });
        });
    </script>
</body>
</html>
