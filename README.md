# Gestor de Tareas CRUD

Sistema de gestión de tareas desarrollado con PHP, MySQL y Bootstrap.

## Características

- Crear, leer, actualizar y eliminar tareas (CRUD completo)
- Búsqueda de tareas por ID, nombre o descripción
- Interfaz responsiva usando Bootstrap
- Validación de formularios
- Mensajes de retroalimentación usando SweetAlert2
- Estándares de codificación implementados

## Estructura del Proyecto

CRUD/
├── config/
│   └── database.php
├── css/
│   └── style.css
├── js/
│   └── script.js
├── agregar-tarea.php
├── editar-tarea.php
├── eliminar-tarea.php
├── obtener-tarea.php
├── obtener-tareas.php
└── index.php


## Configuración

1. Crear una base de datos MySQL
2. Importar el archivo `database.sql`
3. Configurar las credenciales en `config/database.php`

## Tecnologías Utilizadas

- PHP 7.4+
- MySQL 5.7+
- Bootstrap 5.3.2
- SweetAlert2
- Bootstrap Icons

## Estándares de Codificación

- Nombres de variables en camelCase
- Nombres de clases en PascalCase
- Nombres de archivos en kebab-case
- Indentación consistente
- Comentarios descriptivos