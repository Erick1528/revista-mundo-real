# Checklist de deploy

## Antes del deploy

- [ ] **Variables de entorno** en producción:
  - `APP_ENV=production`
  - `APP_DEBUG=false`
  - `APP_KEY` generada (`php artisan key:generate`)
  - `APP_URL` con la URL final
  - `DB_*` configuradas para la base de datos de producción

- [ ] **Base de datos**:
  - Ejecutar migraciones: `php artisan migrate --force`
  - En local ya están aplicadas (incl. `cover_articles`, `parent_id`, `is_active`)

- [ ] **Assets**:
  - `npm ci`
  - `npm run build`

- [ ] **Cachés** (en el servidor tras el deploy):
  - `php artisan config:cache`
  - `php artisan route:cache`
  - `php artisan view:cache`

- [ ] **Storage**:
  - `php artisan storage:link` (si usas almacenamiento local para imágenes)

- [ ] **Permisos**: `storage` y `bootstrap/cache` escribibles por el servidor web.

## Verificación rápida

```bash
php -l app/Models/CoverArticle.php
php artisan route:list
php artisan migrate:status
php artisan test
npm run build
```

## Rutas de portadas (auth)

- `GET /portadas` → listado (filtros, activa primero)
- `GET /portadas/nueva` → crear
- `GET /portadas/{cover}/editar` → editar / ver cambios pendientes
- `POST /portadas/{cover}/activar`
- `POST /portadas/{cover}/aprobar`
- `POST /portadas/{cover}/rechazar`

Solo usuarios con rol `editor_chief`, `administrator` o `moderator` pueden acceder.
