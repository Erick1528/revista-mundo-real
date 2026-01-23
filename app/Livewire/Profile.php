<?php

namespace App\Livewire;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $editingMode = false;
    public $name;
    public $email;
    public $description;
    public $avatar;
    public $currentAvatar;
    public $rol;
    public $articles;
    public $totalArticles;
    public $createdAt;
    public $updatedAt;
    public $lastSession;

    // Estado de secciones del acordeón
    public $openSections = [
        'personal' => true,
        'avatar' => false,
        'password' => false,
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'avatar' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:10240',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'description.max' => 'La descripción no puede tener más de 1000 caracteres.',
        'avatar.image' => 'El archivo debe ser una imagen válida.',
        'avatar.mimes' => 'La imagen debe ser de tipo: jpeg, jpg, png, webp o gif.',
        'avatar.max' => 'La imagen no puede ser mayor a 10MB.',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->description = $user->description;
        $this->currentAvatar = $user->avatar;
        $this->rol = $user->rol;
        $this->createdAt = $user->created_at;
        $this->updatedAt = $user->updated_at;
        $this->loadLastSession();
        $this->loadArticles();
    }

    public function loadLastSession()
    {
        $user = Auth::user();
        $session = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->first();
        
        if ($session) {
            $this->lastSession = \Carbon\Carbon::createFromTimestamp($session->last_activity);
        } else {
            $this->lastSession = null;
        }
    }

    public function loadArticles()
    {
        $user = Auth::user();
        $this->totalArticles = Article::where('user_id', $user->id)->count();
        $this->articles = Article::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }

    public function toggleEditMode()
    {
        $this->editingMode = !$this->editingMode;
        if ($this->editingMode) {
            // Recargar datos del usuario al entrar en modo edición
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->description = $user->description;
            $this->currentAvatar = $user->avatar;
        } else {
            // Recargar datos al salir del modo edición
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->description = $user->description;
            $this->currentAvatar = $user->avatar;
            $this->loadArticles();
        }
    }


    public function updatedAvatar()
    {
        // Validar extensión inmediatamente cuando se selecciona archivo
        if ($this->avatar && is_object($this->avatar)) {
            $extension = strtolower($this->avatar->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($extension, $allowedExtensions)) {
                $this->avatar = null;
                $this->addError('avatar', 'Extensión no soportada. Solo se permiten: JPG, PNG, GIF, WebP');
                return;
            }
        }

        // Limpiar errores previos si la extensión es válida
        $this->resetErrorBag('avatar');
    }

    public function toggleSection($section)
    {
        $this->openSections[$section] = !$this->openSections[$section];
    }

    public function removeAvatar()
    {
        $this->avatar = null;
        $this->resetValidation(['avatar']);
        $this->resetErrorBag(['avatar']);
    }

    public function updateProfile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Si no hay avatar nuevo, no validar avatar
        $rules = $this->rules;
        if (!$this->avatar || !is_object($this->avatar)) {
            unset($rules['avatar']);
        }

        $this->validate($rules);

        try {
            $updateData = [
                'name' => $this->name,
                'description' => $this->description,
            ];

            // Procesar avatar si existe y es nuevo
            if ($this->avatar && is_object($this->avatar)) {
                // Eliminar avatar anterior si existe
                if ($user->avatar && Storage::disk('public')->exists(str_replace('/storage/', '', $user->avatar))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
                }

                $avatarPath = $this->processAvatarUpload($this->avatar);
                $updateData['avatar'] = $avatarPath;
                $this->currentAvatar = $avatarPath;
            }

            $user->fill($updateData);
            $user->save();

            // Actualizar propiedades locales
            $this->name = $user->name;
            $this->description = $user->description;
            $this->currentAvatar = $user->avatar;

            session()->flash('message', 'Perfil actualizado exitosamente.');
            
            // Salir del modo edición después de guardar
            $this->editingMode = false;
            $this->loadArticles();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    private function processAvatarUpload($file)
    {
        // Generar nombre único para el avatar (siempre WebP)
        $timestamp = now()->format('Ymd_His');
        $randomString = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 8);
        $originalExtension = strtolower($file->getClientOriginalExtension());

        // Validar extensión de entrada
        if (!in_array($originalExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            throw new \Exception('Formato de imagen no soportado. Use JPG, PNG, GIF o WebP.');
        }

        // Siempre generar archivo WebP
        $fileName = "avatar_{$timestamp}_{$randomString}.webp";

        // Crear directorio si no existe
        $uploadPath = storage_path('app/public/avatars');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Ruta temporal del archivo subido
        $tempPath = $file->getRealPath();
        $finalPath = $uploadPath . '/' . $fileName;

        try {
            // Optimizar imagen y convertir siempre a WebP
            $this->optimizeImage($tempPath, $finalPath, $originalExtension);
        } catch (\Exception $e) {
            throw $e;
        }

        return '/storage/avatars/' . $fileName;
    }

    private function optimizeImage($sourcePath, $destinationPath, $originalExtension)
    {
        // Obtener dimensiones originales
        $imageInfo = getimagesize($sourcePath);
        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // Calcular nuevas dimensiones (máximo 400x400px para avatar)
        $maxWidth = 400;
        $maxHeight = 400;

        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = intval($width * $ratio);
            $newHeight = intval($height * $ratio);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        // Crear imagen desde el archivo original
        $sourceImage = $this->createImageFromFile($sourcePath, $originalExtension);

        if (!$sourceImage) {
            throw new \Exception('No se pudo procesar la imagen');
        }

        // Crear nueva imagen redimensionada
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preservar transparencia para PNG y GIF
        if ($originalExtension === 'png' || $originalExtension === 'gif') {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefill($resizedImage, 0, 0, $transparent);
        }

        // Redimensionar imagen
        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Guardar siempre como WebP
        imagewebp($resizedImage, $destinationPath, 85);

        // Liberar memoria
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);
    }

    private function createImageFromFile($path, $extension)
    {
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return imagecreatefromjpeg($path);
            case 'png':
                return imagecreatefrompng($path);
            case 'gif':
                return imagecreatefromgif($path);
            case 'webp':
                return imagecreatefromwebp($path);
            default:
                throw new \Exception('Formato de imagen no reconocido: ' . $extension);
        }
    }

    public function getRolName()
    {
        $roles = [
            'writer_junior' => 'Escritor Junior',
            'writer_senior' => 'Escritor Senior',
            'editor_junior' => 'Editor Junior',
            'editor_senior' => 'Editor Senior',
            'editor_chief' => 'Editor Jefe',
            'moderator' => 'Moderador',
            'administrator' => 'Administrador'
        ];

        return $roles[$this->rol] ?? $this->rol;
    }

    public function formatDate($date)
    {
        if (!$date) {
            return 'N/A';
        }

        $now = now();
        $diffInDays = $now->diffInDays($date);

        // Si es menos de 7 días, mostrar tiempo relativo
        if ($diffInDays < 7) {
            return $date->diffForHumans();
        }

        // Si es más de 7 días, mostrar fecha formateada en español
        $meses = [
            'ene' => 'Ene',
            'feb' => 'Feb',
            'mar' => 'Mar',
            'abr' => 'Abr',
            'may' => 'May',
            'jun' => 'Jun',
            'jul' => 'Jul',
            'ago' => 'Ago',
            'sep' => 'Sep',
            'oct' => 'Oct',
            'nov' => 'Nov',
            'dic' => 'Dic',
        ];

        $fecha = $date->locale('es')->translatedFormat('j M Y');
        $fecha = str_replace('.', '', $fecha);
        $fecha = preg_replace_callback(
            '/\b([a-z]{3})\b/i',
            function ($matches) use ($meses) {
                $mes = strtolower($matches[1]);
                return $meses[$mes] ?? $matches[1];
            },
            $fecha,
        );

        return $fecha;
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
