# 🪦 Sistema de Gestión de Cementerio - Ingeniería de Software

Este proyecto está desarrollado con **Laravel** y utiliza **PostgreSQL** como base de datos.  

El sistema permite gestionar: nichos, osarios, difuntos, bodega, incineraciones, contratos de alquiler, programación de entierros y flujo de caja.

---

## ⚙️ Requisitos Previos

Asegúrate de tener instalados:

- PHP 8.2 o superior  
- Composer  
- Node.js y NPM  
- PostgreSQL  
- Git  

---

## 🧩 Configuración Inicial

1. **Clonar el repositorio**

```bash
git clone -b master https://github.com/ErickHuallpa/CementerioIngenieriaDeSoftware.git
cd CementerioIngenieriaDeSoftware
```

2. **Instalar dependencias de Laravel**

```bash
composer install
```

3. **Instalar dependencias de Node**

```bash
npm install
```

4. **Copiar archivo de entorno**

```bash
cp .env.example .env
```

5. **Configurar la base de datos PostgreSQL** en `.env`:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cementerio_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

6. **Crear la base de datos**

```sql
CREATE DATABASE cementerio_db;
```

7. **Ejecutar migraciones y seeders**

```bash
php artisan migrate
```

8. **Insertar tipos de persona iniciales**

```sql
INSERT INTO tipo_persona (nombre_tipo, descripcion) VALUES
('Administrador', 'Persona encargada de la gestión general del sistema'),
('Empleado', 'Trabajador que realiza tareas operativas en el cementerio'),
('Doliente', 'Familiar o responsable del difunto'),
('Responsable de Incineración', 'Persona a cargo del proceso de incineración'),
('Visitante', 'Persona que visita el cementerio o consulta información'),
('Responsable de Entierro', 'Persona encargada de supervisar o ejecutar entierros'),
('Responsable de Bodega', 'Persona responsable del manejo y control de la bodega');
```

9. **Generar pabellones y nichos automáticamente**

```sql
-- Pabellones
INSERT INTO pabellon (id_pabellon, nombre, descripcion, tipo, institucion, created_at, updated_at)
VALUES
(1, 'Pabellon San Miguel', 'Destinado a difuntos', 'comun', NULL, NOW(), NOW()),
(2, 'Pabellon San Juan', 'Destinado a difuntos', 'comun', NULL, NOW(), NOW()),
(3, 'Pabellon Santa Rosa', 'Destinado a difuntos', 'comun', NULL, NOW(), NOW()),
(4, 'Pabellon San Pedro', 'Destinado a difuntos', 'comun', NULL, NOW(), NOW()),
(5, 'Pabellon Jesús Nazareno', 'Destinado a difuntos', 'comun', NULL, NOW(), NOW()),
(6, 'Pabellon San Marcos', 'Destinado a difuntos', 'comun', NULL, NOW(), NOW()),
(7, 'Pabellon San José', 'Destinado a difuntos', 'comun', NULL, NOW(), NOW()),
(8, 'Pabellon Santa Ana', 'Destinado a difuntos', 'comun', NULL, NOW(), NOW()),
(9, 'Pabellon Virgen del Carmen', 'Destinado a difuntos', 'comun', NULL, NOW(), NOW()),
(10, 'Pabellon San Rafael', 'Destinado a difuntos', 'comun', NULL, NOW(), NOW());

-- Nichos (ejemplo automático para los 10 pabellones, puedes replicar el patrón)
INSERT INTO public.nicho
(id_pabellon, fila, columna, posicion, costo_alquiler, estado, fecha_ocupacion, fecha_vencimiento, created_at, updated_at)
VALUES
-- Pabellón 1
(1, 1, 'A', 'superior', 521, 'disponible', NULL, NULL, NOW(), NOW()),
(1, 1, 'B', 'superior', 521, 'disponible', NULL, NULL, NOW(), NOW()),
(1, 2, 'A', 'medio', 621, 'disponible', NULL, NULL, NOW(), NOW()),
(1, 2, 'B', 'medio', 621, 'disponible', NULL, NULL, NOW(), NOW()),
(1, 3, 'A', 'inferior', 721, 'disponible', NULL, NULL, NOW(), NOW()),
(1, 3, 'B', 'inferior', 721, 'disponible', NULL, NULL, NOW(), NOW());
-- Repetir según sea necesario para todos los pabellones y posiciones
```

10. **Generar la clave de la aplicación**

```bash
php artisan key:generate
```

11. **Configurar correo en `.env`** (para notificaciones, alertas, reportes):

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_correo@gmail.com
MAIL_PASSWORD=tu_contraseña_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_correo@gmail.com
MAIL_FROM_NAME="Cementerio"
```

12. **Ejecutar el servidor local**

```bash
php artisan serve
```

---

## 📊 Reportes Disponibles

- Ocupación de Nichos  
- Ocupación de Osarios  
- Listado de Difuntos  
- Difuntos en Bodega  
- Incineraciones (con costos y total)  
- Programación de Entierros (solo completados)  
- Flujo de Caja (con gráfico de líneas por fecha)  

---

## 📝 Notas

- Asegúrate de ejecutar los SQL de pabellones y nichos **antes de empezar a registrar difuntos**.  
- Los gráficos de reportes requieren que el navegador soporte **Chart.js** (ya incluido en el proyecto).  
- La tabla de incineraciones y flujo de caja muestra totales automáticamente.  

---
