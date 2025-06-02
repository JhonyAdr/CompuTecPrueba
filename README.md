# Computec Marketplace

Sistema de marketplace para la compra y venta de productos tecnológicos.

## Requisitos Previos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Composer (para gestión de dependencias)

## Instalación

### Windows (usando XAMPP/Laragon)

1. Clona el repositorio en tu carpeta de htdocs (XAMPP) o www (Laragon):
   ```bash
   git clone https://github.com/tu-usuario/CompuTecPrueba.git
   ```

2. Importa la base de datos:
   - Abre phpMyAdmin (http://localhost/phpmyadmin)
   - Crea una nueva base de datos llamada `computec_marketplace`
   - Importa el archivo `database.sql`

3. Configura la conexión a la base de datos:
   - Abre `config/database.php`
   - Actualiza los datos de conexión según tu configuración:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'tu_usuario');
     define('DB_PASS', 'tu_contraseña');
     define('DB_NAME', 'computec_marketplace');
     ```

4. Inicia tu servidor web y MySQL

5. Accede al proyecto en tu navegador:
   ```
   http://localhost/CompuTecPrueba
   ```

### Linux/Mac

1. Clona el repositorio:
   ```bash
   git clone https://github.com/tu-usuario/CompuTecPrueba.git
   ```

2. Configura el servidor web:
   ```bash
   # Para Apache
   sudo cp -r CompuTecPrueba /var/www/html/
   sudo chown -R www-data:www-data /var/www/html/CompuTecPrueba
   ```

3. Importa la base de datos:
   ```bash
   mysql -u root -p
   CREATE DATABASE computec_marketplace;
   USE computec_marketplace;
   source /ruta/a/database.sql
   ```

4. Configura la conexión a la base de datos como se mencionó anteriormente

5. Accede al proyecto:
   ```
   http://localhost/CompuTecPrueba
   ```

## Usuarios por Defecto

El sistema incluye tres tipos de usuarios predefinidos:

1. Administrador:
   - Usuario: admin_computec
   - Contraseña: admin123

2. Vendedor:
   - Usuario: vendedor_computec
   - Contraseña: vendedor123

3. Comprador:
   - Usuario: comprador_computec
   - Contraseña: comprador123

## Características Principales

- Registro y login de usuarios
- Gestión de productos (agregar, editar, eliminar)
- Sistema de categorías
- Gestión de transacciones
- Sistema de comentarios
- Favoritos
- Panel de administración

## Estructura de Directorios

```
CompuTecPrueba/
├── admin/              # Panel de administración
├── assets/            # Recursos estáticos (CSS, JS, imágenes)
├── config/            # Configuraciones
├── includes/          # Funciones y utilidades
├── uploads/           # Imágenes subidas
└── *.php             # Archivos principales
```

## Solución de Problemas

1. Error de conexión a la base de datos:
   - Verifica que MySQL esté corriendo
   - Comprueba las credenciales en config/database.php

2. Error de permisos:
   - Asegúrate que la carpeta uploads/ tenga permisos de escritura
   - En Linux/Mac: `chmod 755 uploads/`

3. Página en blanco:
   - Revisa los logs de error de PHP
   - Verifica que todas las extensiones necesarias estén habilitadas

## Soporte

Para reportar problemas o solicitar ayuda:
1. Abre un issue en el repositorio
2. Contacta al equipo de soporte

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles. 