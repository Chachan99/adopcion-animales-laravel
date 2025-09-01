# 🐾 Sistema de Adopción de Animales

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
</p>

## 📋 Descripción

Sistema web para la gestión de adopción de animales, diseñado para conectar fundaciones protectoras de animales con posibles adoptantes. La plataforma permite a las fundaciones publicar animales disponibles para adopción, gestionar solicitudes y recibir donaciones, mientras que los usuarios pueden buscar animales, realizar solicitudes de adopción y hacer donaciones.

## 🚀 Características Principales

### Para Usuarios Públicos
- 📝 Registro e inicio de sesión de usuarios
- 🔍 Búsqueda avanzada de animales por criterios
- 👀 Visualización de perfiles detallados de animales
- 💌 Envío de solicitudes de adopción
- 💳 Sistema de donaciones en línea
- 📱 Diseño responsive

### Para Fundaciones
- 🏠 Panel de control personalizado
- 🐶 Gestión de animales (CRUD)
- 📋 Revisión de solicitudes de adopción
- 💰 Gestión de donaciones recibidas
- 📊 Reportes y estadísticas
- 🖼️ Subida de imágenes para los perfiles de animales

### Para Administradores
- 👥 Gestión de usuarios y roles
- 🏛️ Aprobación de cuentas de fundaciones
- 📈 Estadísticas generales del sistema
- 🔄 Moderación de contenido
- ⚙️ Configuración del sistema

## 🛠️ Requisitos del Sistema

- PHP >= 8.1
- Composer
- MySQL >= 5.7 o MariaDB >= 10.3
- Node.js >= 14.x
- NPM o Yarn

## 🚀 Instalación

1. Clonar el repositorio:
   ```bash
   git clone [URL_DEL_REPOSITORIO]
   cd adopcion-animales
   ```

2. Instalar dependencias de PHP:
   ```bash
   composer install
   ```

3. Instalar dependencias de JavaScript:
   ```bash
   npm install
   npm run dev
   ```

4. Configurar el archivo .env:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configurar la base de datos en el archivo .env

6. Ejecutar migraciones y seeders:
   ```bash
   php artisan migrate --seed
   ```

7. Iniciar el servidor de desarrollo:
   ```bash
   php artisan serve
   ```

8. Acceder a la aplicación en: http://localhost:8000

## 🔑 Credenciales por defecto

### Administrador
- **Email:** admin@example.com
- **Contraseña:** password

### Fundación
- **Email:** fundacion@example.com
- **Contraseña:** password

### Usuario
- **Email:** usuario@example.com
- **Contraseña:** password

## 📚 Estructura del Proyecto

```
app/
├── Http/Controllers/   # Controladores de la aplicación
│   ├── AdminController.php     # Panel de administración
│   ├── FundacionController.php # Gestión de fundaciones
│   └── ...
├── Models/             # Modelos de la base de datos
│   ├── Animal.php
│   ├── Donacion.php
│   ├── PerfilFundacion.php
│   └── ...
├── Policies/           # Políticas de autorización
└── ...

database/
├── migrations/         # Migraciones de la base de datos
└── seeders/            # Datos de prueba

resources/
├── js/                # Archivos JavaScript
├── sass/              # Estilos SCSS
└── views/             # Vistas Blade
    ├── admin/         # Vistas del panel de administración
    ├── fundacion/     # Vistas del panel de fundación
    └── publico/       # Vistas públicas
```

## 🤝 Contribuir

¡Las contribuciones son bienvenidas! Por favor, sigue estos pasos:

1. Haz un Fork del proyecto
2. Crea una rama para tu característica (`git checkout -b feature/AmazingFeature`)
3. Haz commit de tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Haz push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más información.

## ✨ Créditos

- [Tu Nombre](https://tusitio.com)
- [Todos los colaboradores](https://github.com/tu-usuario/adopcion-animales/contributors)

## 🔒 Seguridad

Si descubres alguna vulnerabilidad de seguridad, por favor envía un correo a [tu-email@example.com](mailto:tu-email@example.com). Todas las vulnerabilidades serán atendidas con la mayor brevedad posible.

## 🙏 Agradecimientos

- A todos los que han contribuido al desarrollo de este proyecto.
- A la comunidad de Laravel por su increíble ecosistema.
- A todas las fundaciones que trabajan por el bienestar animal.
