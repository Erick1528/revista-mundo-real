<?php

// Script para crear un usuario
// Ejecutar con: php artisan tinker < create-user.php
// O copiar y pegar el contenido dentro de tinker

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Configura estos valores según necesites
$name = 'Ana Menjivar';
$email = 'anamenjivar46@yahoo.com';
$password = 'Banyoles17820'; // Cambia esto por una contraseña segura
$rol = 'administrator'; // Opciones: writer_junior, writer_senior, editor_junior, editor_senior, editor_chief, moderator, administrator

// Crear el usuario
$user = User::create([
    'name' => $name,
    'email' => $email,
    'password' => Hash::make($password),
    'rol' => $rol,
]);

echo "Usuario creado exitosamente:\n";
echo "ID: {$user->id}\n";
echo "Nombre: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Rol: {$user->rol}\n";
