# 🎬 DAW2_P03_THE_FORK

### 👥 Miembros del Equipo
- Àngel Camps Ruíz
- Juanjo Gomez Rosales
- Aina Orozco Gonzalez

### 🌐 Repositorio del Proyecto
[GitHub - DAW2_P03_THE_FORK](https://github.com/JuanjGomez/DAW2_P03_THE_FORK.git)

---

### 📝 Descripción del Proyecto
Este proyecto transversal es una aplicación web desarrollada como parte del módulo 12 en el curso de **Desenvolupament d'Aplicacions Web (DAW)**. La aplicación permite acceder a The Fork, una web de restaurantes, con la posibilidad de registrarse, acceder a la web, y ver los restaurantes mas populares, hacer una busqueda por filtros, o ver los detalles de cada restaurante.

---

### 🚀 Funcionalidades Principales
- **Visualización de Restaurantes:** Visualizar los restaurantes mas populares, buscar por filtros, ver los detalles de cada restaurante, solo para usuarios registrados como estandares.
- **Valoración de Restaurantes:** Los usuarios pueden valorar los restaurantes al estar logeados.
- **Login de Usuarios/administradores:** Login de usuarios y administradores, con la posibilidad de registrarse.
- **CRUD para administradores:** Crear, leer, actualizar y eliminar restaurantes, usuarios.
---

### 📂 Estructura del Proyecto

DAW2_P03_THE_FORK/
├── app/
│   ├── Http/
│   │   ├── Controllers/    # Controladores de la aplicación
│   │   └── Middleware/     # Middlewares de autenticación
│   └── Models/             # Modelos de la base de datos
├── config/                 # Configuración de la aplicación
├── database/
│   ├── migrations/         # Migraciones de la base de datos
│   └── seeders/           # Seeders para datos iniciales
├── public/
│   ├── images/            # Imágenes públicas
│   ├── js/               # JavaScript compilado
│   └── css/              # CSS compilado
├── resources/
│   ├── css/              # Archivos fuente CSS
│   ├── js/               # Archivos fuente JavaScript
│   └── views/            # Vistas Blade
│       ├── auth/         # Vistas de autenticación
│       ├── layouts/      # Plantillas base
│       └── components/   # Componentes reutilizables
├── routes/
│   ├── web.php          # Rutas web
│   └── api.php          # Rutas API
├── storage/             # Archivos generados
├── tests/              # Pruebas
├── vendor/             # Dependencias de Composer
├── .env               # Variables de entorno
└── README.md          # Documentación

---

### 🔧 Tecnologías Utilizadas
- **Laravel:** Framework de PHP para el backend
- **Blade:** Motor de plantillas de Laravel
- **JavaScript:** Interactividad en el frontend
- **MySQL:** Base de datos relacional
- **Vite:** Compilador de assets
- **Git:** Control de versiones

---

### 🚀 Comenzar
Para comenzar con el proyecto, sigue estos pasos:

1. Clona el repositorio:
git clone https://github.com/JuanjGomez/DAW2_P03_THE_FORK.git

2. Instala las dependencias:
composer install
npm install

3. Configura el archivo .env:
cp .env.example .env
php artisan key:generate

4. Configura la base de datos en .env y ejecuta las migraciones:
php artisan migrate --seed

5. Inicia el servidor:
php artisan serve
npm run dev

---

### 🛡️ Recomendaciones de Seguridad
Este proyecto requiere que los usuarios se autentiquen antes de gestionar los recursos. Para proteger los datos y el acceso, es importante que cada usuario cierre sesión después de su uso.

---

### 🗒️ Planificación y Seguimiento del Proyecto
Se ha utilizado una planificación en GitHub para el seguimiento del proyecto. La organización incluye:

- **Daily Meetings:** Reuniones diarias al inicio de la jornada para coordinar tareas.
- **Branches, Commits diarios y roadmap:** El equipo hace commits diarios al principio y final de cada jornada para asegurar una integración continua.Además, se ha creado un roadmap para el proyecto.
- **Issues y Labels:** Uso de issues y etiquetas para gestionar el desarrollo.

---

### 📞 Contacto
Para preguntas o comentarios, contáctanos a través del repositorio de GitHub!.

---

### ⚠️ Nota
*Esta es una web de pruebas realizada para un Proyecto del Instituto.*
