// Script para crear un usuario
// Ejecutar con: php artisan tinker < create-user.php
// O copiar y pegar el contenido dentro de tinker

// Configura estos valores según necesites
$name = 'Nombre del Usuario';
$email = 'usuario@example.com';
$password = 'password123'; // Cambia esto por una contraseña segura
$rol = 'writer_junior'; // Opciones: writer_junior, writer_senior, editor_junior, editor_senior, editor_chief, moderator, administrator

// Crear el usuario
$user = \App\Models\User::create([
    'name' => $name,
    'email' => $email,
    'password' => \Illuminate\Support\Facades\Hash::make($password),
    'rol' => $rol,
]);

echo "Usuario creado exitosamente:\n";
echo "ID: {$user->id}\n";
echo "Nombre: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Rol: {$user->rol}\n";
