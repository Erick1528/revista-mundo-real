<?php

/**
 * Script para crear un usuario desde consola.
 * Ejecutar desde la ra�z del proyecto: php create-user.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// ??? Datos del usuario (editar seg�n necesidad) ???

$name     = "Nombre de usuario";
$email    = "correo@ejemplo.com";
$password = "contraseña";
$rol      = "rol_del_usuario"; // writer_junior, writer_senior, editor_junior, editor_senior, editor_chief, moderator, administrator

// Ejemplos de otros roles (si los usas en tu app):
// $rol = "editor";
// $rol = "administrator";

// ??? Crear usuario ???

$user = User::create([
    "name"     => $name,
    "email"    => $email,
    "password" => Hash::make($password),
    "rol"      => $rol,
]);

echo "Usuario creado! (id: {$user->id})\n";
