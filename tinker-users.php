<?php
// Comando para usar en php artisan tinker
// Uso: php artisan tinker < tinker-users.php

// Crear un usuario con email, rol, password y nombre
\App\Models\User::create([
    'name' => 'Alberto Martín',
    'email' => 'vassagoallen1@gmail.com',
    'password' => \Illuminate\Support\Facades\Hash::make('vassago66'),
    'rol' => 'editor_chief',
]);

echo "Usuario creado correctamente!\n";
