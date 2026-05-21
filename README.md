# Management of Students - Web Page

Aplicación web básica para gestionar estudiantes, desarrollada como parte del **Taller SQL - Bases de Datos I** de la Universidad Tecnológica de Pereira (UTP).

## Descripción

Permite **agregar** y **visualizar** estudiantes almacenados en una base de datos PostgreSQL, usando PHP y Python (Flask) como backends, y HTML puro como frontend.

## Tecnologías

| Capa | Tecnología |
|------|-----------|
| Base de datos | PostgreSQL |
| Insertar estudiantes | PHP 8 + PDO |
| Listar estudiantes | Python 3 + Flask |
| Interfaz visual | HTML + JavaScript |

## Estructura del proyecto

```
estudiantes-app/
├── db/
│   └── init.sql          # Script para crear la tabla e insertar datos de prueba
├── php/
│   ├── agregar.php       # Endpoint para insertar estudiantes (puerto 8000)
│   └── clientes.php      # Endpoint para insertar clientes (puerto 8000)
├── python/
│   ├── app.py            # API Flask para listar estudiantes (puerto 5000)
│   ├── clientes.py       # API Flask para listar clientes y pedidos (puerto 5001)
│   └── requirements.txt  # Dependencias Python
└── frontend/
    └── index.html        # Interfaz web
```

## Requisitos previos

- PostgreSQL instalado y corriendo
- PHP 8+ con extensiones `pdo_pgsql` y `pgsql` habilitadas
- Python 3 con pip

## Instalación y configuración

### 1. Crear la base de datos en PostgreSQL

```bash
psql -U postgres
CREATE DATABASE Universidad;
\c Universidad
```

### 2. Crear la tabla e insertar datos de prueba

```bash
psql -U postgres -d Universidad -f db/init.sql
```

### 3. Instalar dependencias Python

```bash
pip install -r python/requirements.txt
```

### 4. Configurar la contraseña de PostgreSQL

Edita los archivos `php/agregar.php`, `php/clientes.php`, `python/app.py` y `python/clientes.py` y reemplaza `TU_CONTRASEÑA_AQUI` con tu contraseña de PostgreSQL.

## Cómo abrir la página web

Debes levantar **3 servidores** antes de abrir el frontend. Abre 3 terminales y ejecuta uno en cada una:

**Terminal 1 — Servidor PHP (puerto 8000):**
```bash
# Con XAMPP en Windows:
C:\xampp\php\php.exe -S localhost:8000 -t php/

# En Linux/Mac:
php -S localhost:8000 -t php/
```

**Terminal 2 — API Flask estudiantes (puerto 5000):**
```bash
python python/app.py
```

**Terminal 3 — API Flask clientes/pedidos (puerto 5001):**
```bash
python python/clientes.py
```

**Abrir el frontend:**

Abre el archivo `frontend/index.html` en tu navegador. Puedes hacerlo:
- Doble clic sobre el archivo → clic derecho → "Abrir con" → tu navegador
- O escribe en la barra de direcciones del navegador: `C:/estudiantes-app/frontend/index.html`

## Endpoints disponibles

| Método | URL | Descripción |
|--------|-----|-------------|
| POST | `http://localhost:8000/agregar.php` | Agregar un estudiante |
| GET | `http://localhost:5000/estudiantes` | Listar todos los estudiantes |
| POST | `http://localhost:8000/clientes.php` | Agregar un cliente |
| GET | `http://localhost:5001/clientes_pedidos` | Listar clientes con sus pedidos |

## Autor

Desarrollado por **alejoramirez27** — UTP Ingeniería de Sistemas y Computación
