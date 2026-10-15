# Portal Digital — Hospital de Clínicas de Ceciamb

Portal web completo para la gestión de citas médicas, directorio de especialistas y servicios del Hospital de Clínicas de Ceciamb.

## Descripción

Este proyecto es un MVP (Producto Mínimo Viable) que incluye:

- **Portal público:** Sitio web donde los pacientes pueden conocer la clínica, explorar especialidades, médicos y servicios, y solicitar citas.
- **Panel administrativo:** Entorno privado donde el personal gestiona médicos, especialidades, servicios, horarios y solicitudes de citas.
- **Portal del paciente:** Área privada donde los pacientes ven su historial de citas y gestionan sus datos personales.

## Stack Tecnológico

| Componente | Tecnología | Versión |
|---|---|---|
| Backend | Laravel | 12.x |
| Panel admin | Filament | 5.x |
| Frontend | React | 19.x |
| Bridge | Inertia.js | 3.x |
| Estilos | Tailwind CSS | 4.x |
| Base de datos | MySQL | 8.0+ |
| Compilador | Vite | 7.x |
| PHP | | 8.3+ |

## Requisitos Previos

Para ejecutar el proyecto localmente necesitas:

- **PHP** 8.3 o superior con extensiones: `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `zip`, `curl`.
- **Composer** 2.x o superior.
- **Node.js** 20.x o superior.
- **npm** 10.x o superior.
- **MySQL** 8.0 o superior.
- **Git** para control de versiones.

## Instalación Local

### 1. Clonar el repositorio

git clone <url-del-repositorio>
cd hospital-ceciamb

### 2. Instalar dependencias PHP

composer install

### 3. Instalar dependencias JS

npm install

### 4. Configurar el entorno
copy .env.example .env

Edita .env y configura:
APP_NAME="Hospital de Clínicas de Ceciamb"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hospital_ceciamb
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

MAIL_MAILER=smtp
MAIL_HOST=mail.grupoceciamb.com
MAIL_PORT=587
MAIL_USERNAME=atencionalpublico@grupoceciamb.com
MAIL_PASSWORD=tu_contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="atencionalpublico@grupoceciamb.com"
MAIL_FROM_NAME="${APP_NAME}"

### 5. Generar la clave de la aplicación

php artisan key:generate

### 6. Crear la base de datos

CREATE DATABASE hospital_ceciamb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

### 7. Ejecutar migraciones y seeders

php artisan migrate
php artisan db:seed

### 8. Crear el enlace simbólico de storage

php artisan storage:link

### 9. Compilar assets

En desarrollo:
   npm run dev

En producción:
   npm run build

### 10. Iniciar el servidor

En una terminal:
   php artisan serve

En otra terminal (para compilar assets en vivo):
   npm run dev

Abre http://localhost:8000 en el navegador.

### Estructura del Proyecto

hospital-ceciamb/
├── app/
│   ├── Console/Commands/          # Comandos de consola (Jobs programados)
│   ├── Filament/                  # Recursos del panel administrativo
│   ├── Http/
│   │   ├── Controllers/           # Controladores (público, paciente, api)
│   │   ├── Middleware/            # Middleware personalizado
│   │   └── Requests/              # Form Requests (validación)
│   ├── Mail/                      # Mailables (correos)
│   ├── Models/                    # Modelos Eloquent
│   ├── Observers/                 # Observers (eventos del modelo)
│   ├── Rules/                     # Reglas de validación personalizadas
│   └── Services/                  # Servicios de aplicación
├── bootstrap/                     # Configuración de Laravel
├── config/                        # Archivos de configuración
├── database/
│   ├── factories/                 # Factories (datos de prueba)
│   ├── migrations/                # Migraciones
│   └── seeders/                   # Seeders
├── docs/                          # Documentación
├── public/                        # Document root
│   ├── build/                     # Assets compilados
│   └── images/                    # Imágenes estáticas (logo)
├── resources/
│   ├── css/                       # CSS (Tailwind)
│   ├── js/
│   │   ├── Components/            # Componentes React reutilizables
│   │   ├── Layouts/               # Layouts React
│   │   └── Pages/                 # Páginas React (Inertia)
│   └── views/
│       ├── app.blade.php          # Vista raíz de Inertia
│       └── emails/                # Vistas de correos
├── routes/
│   ├── web.php                    # Rutas web
│   ├── api.php                    # Rutas API
│   └── console.php                # Comandos programados
├── storage/                       # Archivos generados
│   └── app/public/                # Archivos públicos (fotos, imágenes)
├── tests/                         # Tests automatizados
└── vendor/                        # Dependencias PHP

### Comandos Útiles

### Laravel:
php artisan serve                  # Iniciar servidor de desarrollo
php artisan migrate                # Ejecutar migraciones
php artisan migrate:fresh          # Recrear BD desde cero
php artisan db:seed                # Ejecutar seeders
php artisan tinker                 # Consola interactiva
php artisan config:clear           # Limpiar caché de configuración
php artisan cache:clear            # Limpiar caché de aplicación
php artisan route:clear            # Limpiar caché de rutas
php artisan view:clear             # Limpiar caché de vistas
php artisan optimize:clear         # Limpiar TODA la caché
php artisan test                   # Ejecutar tests
php artisan schedule:list          # Ver comandos programados
php artisan schedule:run           # Ejecutar comandos programados manualmente

### npm
npm install                        # Instalar dependencias
npm run dev                        # Compilar assets en desarrollo (watch)
npm run build                      # Compilar assets para producción

### Comandos personalizados

php artisan users:cleanup-unactivated       # Eliminar cuentas no activadas
php artisan appointments:release-expired     # Liberar franjas expiradas
php artisan appointments:send-reminders      # Enviar recordatorios

### Configuración de Cron Jobs

El servidor debe ejecutar php artisan schedule:run cada minuto:
* * * * * cd /ruta/al/proyecto && php artisan schedule:run >> /dev/null 2>&1

### Esto ejecuta automáticamente:

users:cleanup-unactivated — Cada hora
appointments:release-expired — Cada hora
appointments:send-reminders — Diariamente a las 08:00

### Credenciales de Prueba

Admin:
   Email: admin@ceciamb.test
   Contraseña: password

Recepcionista:
   Email: recepcion@ceciamb.test
   Contraseña: password

Paciente:
   Email: paciente@ceciamb.test
   Contraseña: password

### Importante: Cambiar estas contraseñas en producción.

### Soporte

Para soporte técnico, contactar a:
Desarrollador: Jump Daedalus — https://jumpdaedalus.com/
Cliente: Hospital de Clínicas de Ceciamb

### Licencia
Este proyecto es propiedad del Hospital de Clínicas de Ceciamb. Todos los derechos reservados.