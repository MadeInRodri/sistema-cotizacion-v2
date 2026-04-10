# L&&R's Solutions - Sistema de Cotizaciones v2

Una aplicación web moderna construida con PHP bajo el patrón de arquitectura MVC (Modelo-Vista-Controlador), diseñada para la gestión, cotización y venta de servicios tecnológicos.

## Características Principales

- **Arquitectura MVC:** Separación limpia de lógica de negocio (Modelos), controladores de API y presentación (Vistas).
- **Autenticación y Seguridad:** Sistema de Login y Registro seguro utilizando encriptación `BCRYPT` para las contraseñas.
- **Control de Acceso por Roles (RBAC):** \* **Usuarios:** Pueden navegar por el catálogo, agregar servicios al carrito y generar cotizaciones que se guardan en su historial.
  - **Administradores:** Tienen acceso a un panel exclusivo para gestionar el catálogo de servicios.
- **Catálogo Dinámico:** Filtrado de servicios por categoría en tiempo real.
- **Carrito de Cotizaciones:** Gestión de estado mediante sesiones de PHP y comunicación asíncrona (Fetch API) para una experiencia sin recargas.
- **Historial de Cotizaciones:** Generación de códigos únicos (ej. `COT-2026-0001`) y almacenamiento de detalles relacionales.

## Tecnologías Utilizadas

**Frontend:**

- HTML5 semántico.
- CSS3 (Variables globales, Flexbox, Grid, diseño 100% responsivo).
- Vanilla JavaScript (ES6+, Fetch API, Async/Await).
- Librerías externas: SweetAlert2 (Alertas) y FontAwesome (Iconos).

**Backend:**

- PHP 8+ (Programación Orientada a Objetos).
- Autoloader personalizado (PSR-4 inspirado).
- PDO (PHP Data Objects) para acceso seguro a datos.
- JSON API Responses.

**Base de Datos:**

- MySQL / MariaDB.
- Diseño relacional normalizado con integridad referencial (`ON DELETE CASCADE` / `RESTRICT`).

## Estructura del Proyecto

```text
SISTEMA-COTIZACION-V2/
├── config/                # Configuraciones globales, conexión a BD y Guardias de sesión
├── controllers/           # Controladores de la API (Auth, Cart, Quote, Service, Category)
├── models/                # Clases que mapean la base de datos y contienen la lógica SQL
├── public/
│   └── assets/            # Archivos estáticos (CSS, JS)
├── views/                 # Plantillas HTML consumidas por el usuario
├── autoload.php           # Cargador automático de clases
├── index.php              # Enrutador principal y director de tráfico
└── database.sql           # Script de estructura y semillas de la base de datos
```

## Instalación y Configuración local

1.  **Clonar el repositorio:**
    ```bash
    git clone [https://github.com/tu-usuario/sistema-cotizacion-v2.git](https://github.com/tu-usuario/sistema-cotizacion-v2.git)
    ```
2.  **Preparar el entorno:**
    Asegúrate de tener un servidor local configurado (XAMPP, Laragon, MAMP) con Apache y MySQL activados. Mueve la carpeta del proyecto al directorio público (ej. `htdocs`).
3.  **Base de datos:**
    - Abre tu gestor de base de datos (phpMyAdmin, DBeaver, etc.).
    - Ejecuta el script incluido en `database.sql` para crear la base de datos `sistema_cotizacion`, sus tablas y los datos de prueba iniciales.
4.  **Configurar credenciales:**
    - Verifica que los datos de conexión en la clase `Database` coincidan con tu servidor local (usuario `root`, sin contraseña por defecto en XAMPP).
5.  **Ejecutar:**
    - Abre tu navegador y navega a `http://localhost/sistema-cotizacion-v2`.

## Autores

- **Rodrigo Mejía**
- **Leonardo Enrique**

---
