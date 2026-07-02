# Sistema Integral de Administración de Fraccionamientos y Control Residencial

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)

Plataforma inteligente y robusta para la administración administrativa, financiera y de accesos (caseta) en desarrollos residenciales y fraccionamientos. Desarrollado con **Laravel 12** y **PHP 8.4**.

---

## 🚀 Características Clave

* **Módulo RBAC Completo**: Roles de Administrador, Vigilante y Residente con accesos controlados por middleware de Laravel.
* **Control de Accesos (Caseta)**: Registro ágil de entradas y salidas de visitantes por lote o mediante códigos QR.
* **Módulo Financiero Automatizado**: Generación mensual de cuotas de mantenimiento, cálculo automático de recargos moratorios e integración con Stripe y Mercado Pago.
* **Expedientes Digitales**: Almacenamiento seguro de contratos, escrituras y documentos de propietarios.
* **Mapa SVG Interactivo**: Visualización gráfica del fraccionamiento en tiempo real con estados de venta de lotes.

---

## 🛠️ Requisitos de Entorno

* **PHP** >= 8.3 (Recomendado 8.4)
* **Composer** >= 2.8
* **MySQL** >= 8.0
* **Docker & Docker Compose** (Opcional)

---

## 🐳 Despliegue con Docker

El proyecto incluye un entorno Docker multicontenedor preconfigurado.

1. **Clonar e Inicializar variables**:
   ```bash
   cp .env.example .env
   ```
   *Nota: Asegúrate de configurar las variables de base de datos en tu `.env` para apuntar al contenedor de base de datos local:*
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=fraccionamiento
   DB_USERNAME=root
   DB_PASSWORD=root
   ```

2. **Levantar contenedores**:
   ```bash
   docker compose up -d --build
   ```

3. **Ejecutar migraciones y seeders dentro del contenedor**:
   ```bash
   docker compose exec app php artisan migrate:fresh --seed
   ```

4. **Acceso al Sitio**:
   * Aplicación Web: [http://localhost:8080](http://localhost:8080)
   * Base de datos: `127.0.0.1:33066` (Usuario: `root`, Contraseña: `root`)

---

## 💻 Instalación Local (Sin Docker)

Si prefieres ejecutar el proyecto de forma nativa:

1. **Instalar dependencias**:
   ```bash
   composer install
   ```

2. **Crear base de datos local**:
   Crea una base de datos llamada `fraccionamiento` en tu servidor MySQL.

3. **Configurar el archivo `.env`**:
   Ajusta las credenciales de tu base de datos local:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=fraccionamiento
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

4. **Correr migraciones y seeders**:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Iniciar Servidor**:
   ```bash
   php artisan serve
   ```
   Accede a [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 📂 Estructura de Directorios (Clean Architecture)

El proyecto extiende el estándar MVC incorporando capas adicionales para lógica de negocio desacoplada:

```text
├── app/
│   ├── Http/
│   │   ├── Controllers/  # Controladores HTTP (Manejan peticiones y respuestas)
│   │   ├── Middleware/   # Filtros de peticiones (Autenticación y RBAC)
│   │   └── Requests/     # Validaciones de formularios (FormRequests)
│   ├── Models/           # Modelos Eloquent y relaciones de datos
│   ├── Services/         # Servicios (Lógica de negocio pura, ej: cobro Stripe)
│   └── Repositories/     # Capa de datos para desacoplar consultas Eloquent
├── database/
│   ├── factories/        # Fábricas de modelos para testing
│   ├── migrations/       # Esquemas de la base de datos (14 tablas normalizadas)
│   └── seeders/          # Semilleros con datos de desarrollo iniciales
├── docker/               # Configuraciones adicionales de Nginx y Dockerfile
└── docker-compose.yml    # Orquestación de contenedores de desarrollo
```

---

## 👥 Usuarios de Prueba Generados

Puedes iniciar sesión con los siguientes usuarios demo una vez ejecutado el seeder (`php artisan db:seed`):

* **Administrador**:
  * Email: `admin@fracc.com`
  * Contraseña: `password`
* **Vigilante**:
  * Email: `vigilante@fracc.com`
  * Contraseña: `password`
* **Residente**:
  * Email: `juan.perez@example.com`
  * Contraseña: `password`

---

## 🔒 Buenas Prácticas de Seguridad Implementadas

* **Protección contra Inyección SQL**: Mediante el uso exclusivo de sentencias preparadas de Eloquent.
* **Protección CSRF**: Activado automáticamente en todas las peticiones Blade post/put.
* **Sólido RBAC**: Verificaciones granulares de roles y permisos a nivel de base de datos en relaciones N:M.
* **Hashing seguro**: Todas las contraseñas están encriptadas utilizando `BCrypt` a través de los helpers nativos de Laravel.
