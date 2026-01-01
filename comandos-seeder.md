# OPCIÓN 1: Ejecutar solo el seeder de artículos
php artisan db:seed --class=ArticleSeeder

# OPCIÓN 2: Ejecutar todos los seeders (incluyendo el de artículos)
php artisan db:seed

# OPCIÓN 3: Si quieres refrescar la base de datos y seedear todo
php artisan migrate:refresh --seed