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

    <form method="POST" action="{{ route('register.post') }}" id="registroForm">
        @csrf

        <h5 class="text-primary mb-3">Datos personales</h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}"
                       required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                       title="Solo letras y espacios">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}"
                       required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                       title="Solo letras y espacios">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">C.I.</label>
                <input type="text" name="ci" class="form-control" value="{{ old('ci') }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}"
                       pattern="[0-9]+" title="Solo números" maxlength="10">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Tipo de Persona</label>
                <select name="id_tipo_persona" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach(\App\Models\TipoPersona::all() as $tipo)
                        <option value="{{ $tipo->id_tipo_persona }}" {{ old('id_tipo_persona') == $tipo->id_tipo_persona ? 'selected' : '' }}>
                            {{ $tipo->nombre_tipo }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Correo personal</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <hr class="my-4">

        <h5 class="text-primary mb-3">Datos de cuenta</h5>

        <div class="mb-3">
            <label class="form-label">Nombre de usuario</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <button type="submit" class="btn btn-register w-100">
            <i class="fas fa-user-plus me-2"></i> Crear Administrador
        </button>
    </form>
</div>

<script>
    // Evita caracteres inválidos mientras se escribe
    document.querySelector('input[name="nombre"]').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');
    });

    document.querySelector('input[name="apellido"]').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');
    });

    document.querySelector('input[name="telefono"]').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
</body>
</html>
