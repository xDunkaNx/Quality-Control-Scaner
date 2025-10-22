# DEFECTOS

Aplicación interna para el registro, seguimiento y análisis de defectos detectados en tiendas o centros logísticos. El proyecto está construido con Laravel 12 y utiliza Breeze como andamiaje base.

## Tabla de contenidos

- [Requisitos](#requisitos)
- [Configuración inicial](#configuración-inicial)
- [Catálogos administrables](#catálogos-administrables)
- [Permisos y roles](#permisos-y-roles)
- [Integraciones](#integraciones)
- [Generación de reportes](#generación-de-reportes)
- [Pruebas automáticas](#pruebas-automáticas)

## Requisitos

- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (para las pruebas incluidas) u otro motor compatible configurado en `.env`

## Configuración inicial

```bash
cp .env.example .env              # copia la configuración base
composer install                  # instala dependencias PHP
npm install                       # instala dependencias front-end
php artisan key:generate          # genera APP_KEY
php artisan migrate               # crea las tablas
php artisan db:seed               # carga roles, permisos y usuario admin
```

El usuario administrador por defecto es `admin@empresa.test` con clave `secret123`.

## Catálogos administrables

Se añadieron dos módulos en el panel autenticado (visible con el permiso `manage catalogs`):

1. **Tipos de defecto**
   - CRUD completo con filtros por código y nombre.
   - Indicador `requires_photo` para forzar fotografía al crear un defecto.

2. **Ubicaciones**
   - CRUD con jerarquía opcional (`parent_code`) y coordenadas geográficas.
   - Bloquea la eliminación si hay defectos asociados o sububicaciones.

Ambos catálogos se listan en la navegación principal para perfiles autorizados.

## Experiencia de escaneo renovada

- La vista de escaneo fue rediseñada con un flujo guiado: primero el código, luego el contexto (ubicación, lote, notas) y finalmente la confirmación.
- Se incluyeron atajos visuales (auto-guardado, mantenimiento de foco, uso de Shift + Enter) y tarjetas laterales con buenas prácticas para los operarios.
- El formulario aplica validaciones previas antes de enviar y autocompleta el nombre del producto si existe en la base al leer el código.
- Se aplicó un tema oscuro consistente al panel completo (listados, formularios y navegación) para una experiencia uniforme con el nuevo escáner.

## Permisos y roles

Los roles y permisos se gestionan con `spatie/laravel-permission`. Además de los existentes, ahora se crea el permiso `manage catalogs`.

Después de desplegar los cambios ejecuta nuevamente el seeder para asegurar que los roles se actualicen:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

El rol `admin` recibe todos los permisos automáticamente.

## Integraciones

Se expone una API protegida por token para sincronizar catálogos con otros sistemas.

- **Autenticación**: encabezado `Authorization: Bearer {TOKEN}` o `X-Integration-Token`.
- **Configurar token**: define `INTEGRATION_TOKEN` en tu `.env`.

### Endpoints

| Método | Ruta                          | Descripción                                |
| ------ | ----------------------------- | ------------------------------------------ |
| GET    | `/api/catalogs/defect-types`  | Lista paginada de tipos de defecto.        |
| GET    | `/api/catalogs/locations`     | Lista paginada de ubicaciones.             |

Parámetros opcionales:

- `search`: filtra por código o nombre.
- `updated_since`: fecha ISO (p. ej. `2025-01-01T00:00:00Z`) para traer solo registros actualizados desde entonces.
- `per_page`: tamaño de página entre 10 y 500 (por defecto 100).

Ejemplo:

```bash
curl -H "Authorization: Bearer $INTEGRATION_TOKEN" \
  "https://tuservidor/api/catalogs/locations?search=almacen&per_page=50"
```

La respuesta incluye metadatos de paginación (`current_page`, `last_page`, `next_page_url`, etc.).

## Generación de reportes

Desde el módulo de defectos puedes exportar registros a CSV (`Exportar CSV`) y generar reportes semanales agregados. Ambos respentan los filtros aplicados.

## Pruebas automáticas

```bash
php artisan test
```

> Nota: la suite de Breeze usa SQLite por defecto. Si el entorno no tiene el driver instalado aparecerá el error `could not find driver`. Instala `pdo_sqlite` o actualiza la configuración de tests (`phpunit.xml`) según tu motor de base de datos.
