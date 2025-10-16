# Cementerio - Ingeniería de Software

## 🗄️ Configuración del Proyecto

Este proyecto utiliza **Laravel** con base de datos en **PostgreSQL**.

---

## ⚙️ Requisitos previos

Asegúrate de tener instalados:

- PHP 8.2 o superior  
- Composer  
- Node.js y NPM  
- PostgreSQL  
- Git

---

## 🧩 Configuración inicial

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

5. **Configurar la base de datos PostgreSQL** en el archivo `.env`:

   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=cementerio_db
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

6. **Crear la base de datos en PostgreSQL**

   ```sql
   CREATE DATABASE cementerio_db;
   ```

7. **Ejecutar migraciones y seeders**

   ```bash
   php artisan migrate
   ```

8. **Insertar los tipos de persona iniciales**

   ```sql
   INSERT INTO tipo_persona (nombre_tipo, descripcion) VALUES
   ('Administrador', 'Persona encargada de la gestión general del sistema'),
   ('Empleado', 'Trabajador que realiza tareas operativas en el cementerio'),
   ('Doliente', 'Familiar o responsable del difunto'),
   ('Responsable de Incineración', 'Persona a cargo del proceso de incineración'),
   ('Visitante', 'Persona que visita el cementerio o consulta información');
   ```

9. **Generar la clave de la aplicación**

   ```bash
   php artisan key:generate
   ```

10. **Ejecutar el servidor local**

    ```bash
    php artisan serve
    ```

---

## 🧠 Notas importantes

- El proyecto usa el branch principal **`master`**, asegúrate de mantenerte en él.  
- Al registrar difuntos, los entierros se programan automáticamente y se gestionan los contratos de alquiler.  
- Usa `npm run dev` para compilar los recursos front-end.

---

Proyecto de Ingeniería de Software 🧩
