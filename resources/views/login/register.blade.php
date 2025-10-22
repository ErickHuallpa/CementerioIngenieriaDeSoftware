<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Cementerio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-container {
            background-color: white;
            border-radius: 15px;
            padding: 40px;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }
        .btn-register {
            background-color: #16a085;
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-register:hover {
            background-color: #138a72;
        }
    </style>
</head>
<body>
<div class="register-container">
    <h3 class="text-center mb-4">Registrar Administrador del Sistema</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}" class="needs-validation" novalidate>
        @csrf

        <h5 class="text-primary mb-3">Datos personales</h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombre</label>
                <input id="nombre" type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                <div class="invalid-feedback">Por favor ingrese el nombre.</div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Apellido</label>
                <input id="apellido" type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required>
                <div class="invalid-feedback">Por favor ingrese el apellido.</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">C.I.</label>
                <input id="ci" type="text" name="ci" class="form-control" value="{{ old('ci') }}" required maxlength="20">
                <div class="invalid-feedback">Por favor ingrese la C.I. (máx. 20 caracteres).</div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Teléfono</label>
                <input id="telefono" type="tel" inputmode="numeric" pattern="[0-9]*" maxlength="15" name="telefono" class="form-control" value="{{ old('telefono') }}">
                <div class="invalid-feedback">Ingrese solo números (máx. 15 dígitos) o deje vacío.</div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Tipo de Persona</label>
                <select id="id_tipo_persona" name="id_tipo_persona" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach(\App\Models\TipoPersona::all() as $tipo)
                        <option value="{{ $tipo->id_tipo_persona }}" {{ old('id_tipo_persona') == $tipo->id_tipo_persona ? 'selected' : '' }}>
                            {{ $tipo->nombre_tipo }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Seleccione un tipo de persona.</div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input id="direccion" type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
            <div class="invalid-feedback">Ingrese la dirección o deje vacío.</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo personal</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            <div class="invalid-feedback">Por favor ingrese un correo electrónico válido.</div>
        </div>

        <hr class="my-4">

        <h5 class="text-primary mb-3">Datos de cuenta</h5>

        <div class="mb-3">
            <label class="form-label">Nombre de usuario</label>
            <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            <div class="invalid-feedback">El nombre de usuario es obligatorio.</div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Contraseña</label>
                <input id="password" type="password" name="password" class="form-control" required minlength="8">
                <div class="invalid-feedback">La contraseña debe tener al menos 8 caracteres.</div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required minlength="8">
                <div class="invalid-feedback">Las contraseñas no coinciden.</div>
            </div>
        </div>

        <button id="submitBtn" type="submit" class="btn btn-register w-100">
            <i class="fas fa-user-plus me-2"></i> Crear Administrador
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        'use strict'

        // Fetch the form we want to apply custom validation to
        var form = document.querySelector('.needs-validation');

        form.addEventListener('submit', function (event) {
            // Reset custom validity for password confirmation
            var pwd = document.getElementById('password');
            var pwdConf = document.getElementById('password_confirmation');
            pwdConf.setCustomValidity('');

            // Check password match
            if (pwd.value !== pwdConf.value) {
                pwdConf.setCustomValidity('Las contraseñas no coinciden');
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);

        // Phone input: allow only digits
        var tel = document.getElementById('telefono');
        if (tel) {
            tel.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        // Allow only letters and spaces for nombre and apellido (Unicode-aware)
        function allowOnlyLettersAndSpaces(el) {
            if (!el) return;
            el.addEventListener('input', function () {
                try {
                    // Unicode property escape (modern browsers)
                    this.value = this.value.replace(/[^^\p{L}\s]/gu, '');
                } catch (e) {
                    // Fallback for browsers without \p{L} support: cover common accented ranges
                    this.value = this.value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿÑñÁÉÍÓÚáéíóúÜü\s]/g, '');
                }
            });
        }

        allowOnlyLettersAndSpaces(document.getElementById('nombre'));
        allowOnlyLettersAndSpaces(document.getElementById('apellido'));
    })();
</script>
</body>
</html>
