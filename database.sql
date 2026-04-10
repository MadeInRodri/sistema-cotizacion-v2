-- v2
-- 1. Crear y usar la base de datos
CREATE DATABASE IF NOT EXISTS sistema_cotizacion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_cotizacion;

-- 2. Crear la tabla de Usuarios (users)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Crear la tabla de Categorías
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Modificar la tabla de Servicios (Reemplaza la anterior)
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

-- 5. Crear la tabla de Cotizaciones (quotes)
CREATE TABLE quotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    descuento DECIMAL(10, 2) NOT NULL,
    impuesto DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    fecha DATE NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
ALTER TABLE quotes ADD COLUMN empresa VARCHAR(100) NOT NULL AFTER user_id;

-- 6. Crear la tabla de Detalles de Cotización (quote_details)
CREATE TABLE quote_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote_id INT NOT NULL,
    service_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (quote_id) REFERENCES quotes(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT
);

-- ==========================================
-- INSERCIÓN DE DATOS INICIALES (SEEDS)
-- ==========================================

-- Insertar las categorías
INSERT INTO categories (id, name) VALUES 
(1, 'Web'), 
(2, 'Backend'), 
(3, 'Mobile'), 
(4, 'DevOps');

-- Insertar los servicios (Ahora usan el category_id)
INSERT INTO services (id, name, description, price, category_id) VALUES
(1, 'Landing Page', 'Diseño de una página optimizada para captación de leads y conversiones.', 250.00, 1),
(2, 'E-commerce Starter', 'Tienda online básica con pasarela de pagos y gestión de inventario.', 1200.00, 1),
(3, 'Portafolio Profesional', 'Sitio web personalizado para profesionales y creativos.', 450.00, 1),
(4, 'Dashboard Administrativo', 'Panel de control interno para la gestión de datos y métricas.', 850.00, 1),
(5, 'Blog Corporativo', 'Sistema de gestión de contenidos (CMS) para publicación de artículos.', 600.00, 1),
(6, 'API REST Personalizada', 'Desarrollo de endpoints seguros y escalables para aplicaciones.', 500.00, 2),
(7, 'Integración de Base de Datos', 'Diseño y optimización de esquemas relacionales o NoSQL.', 400.00, 2),
(8, 'Sistema de Autenticación', 'Implementación de login seguro mediante JWT u OAuth2.', 300.00, 2),
(9, 'Procesamiento de Pagos', 'Integración técnica con plataformas como Stripe o PayPal.', 350.00, 2),
(10, 'Arquitectura de Microservicios', 'Diseño de sistemas distribuidos para procesos complejos.', 950.00, 2),
(11, 'App Híbrida iOS/Android', 'Desarrollo multiplataforma eficiente utilizando React Native.', 1500.00, 3),
(12, 'Mantenimiento de App', 'Servicio de actualización de librerías y corrección de errores.', 300.00, 3),
(13, 'Notificaciones Push', 'Configuración de sistema de alertas y mensajes en tiempo real.', 200.00, 3),
(14, 'Diseño UI/UX Mobile', 'Prototipado y diseño de interfaces centradas en el usuario móvil.', 400.00, 3),
(15, 'Integración de Mapas', 'Servicios de geolocalización y trazado de rutas en tiempo real.', 450.00, 3),
(16, 'Despliegue en Docker', 'Contenerización de aplicaciones para entornos consistentes.', 350.00, 4),
(17, 'CI/CD Pipelines', 'Automatización total de procesos de prueba y despliegue.', 500.00, 4),
(18, 'Configuración de AWS', 'Configuración de infraestructura cloud y escalado automático.', 700.00, 4),
(19, 'Monitoreo de Servidores', 'Implementación de métricas de rendimiento y alertas de salud.', 250.00, 4),
(20, 'Orquestación con Kubernetes', 'Gestión avanzada de clústeres y réplicas de servicios.', 1200.00, 4);

-- Insertar usuarios de prueba (Nota: Las contraseñas están encriptadas con BCRYPT. Ambas contraseñas son "password123")
INSERT INTO users (name, email, password, role) VALUES 
('Administrador del Sistema', 'admin@sistema.com', '$2y$10$N2Xw.pUqGZlqVfH4D6OQ.e.3G1K9b4l4l4l4l4l4l4l4l4l4l4l4', 'admin'),
('Usuario Cliente', 'cliente@ejemplo.com', '$2y$10$N2Xw.pUqGZlqVfH4D6OQ.e.3G1K9b4l4l4l4l4l4l4l4l4l4l4l4', 'user');
