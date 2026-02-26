<?php

namespace App\Livewire;

use Illuminate\Http\UploadedFile;

trait OptimizesAdvertiserLogo
{
    /**
     * Guarda y optimiza el logo del anunciante: redimensiona (máx. 400px ancho) y guarda en PNG.
     * PNG conserva transparencia (logos sin fondo) y es el formato habitual para logos.
     * Devuelve la ruta relativa para guardar en logo_path (ej. logos/xxx.png).
     */
    protected function processLogoUpload(UploadedFile $file): string
    {
        $timestamp = now()->format('Ymd_His');
        $randomString = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 8);
        $originalExtension = strtolower($file->getClientOriginalExtension());

        if (!in_array($originalExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            throw new \Exception('Formato de imagen no soportado. Use JPG, PNG, GIF o WebP.');
        }

        if ($originalExtension === 'webp' && !$this->gdSupportsWebP()) {
            throw new \Exception('Tu servidor no tiene soporte WebP en GD. Usa JPG o PNG.');
        }

        $fileName = "logo_{$timestamp}_{$randomString}.png";
        $uploadPath = storage_path('app/public/logos');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $tempPath = $file->getRealPath();
        $finalPath = $uploadPath . '/' . $fileName;

        $previousHandler = set_error_handler(function ($severity, $message, $file, $line) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });
        try {
            $this->optimizeLogoImage($tempPath, $finalPath, $originalExtension);
        } finally {
            set_error_handler($previousHandler);
        }

        return 'logos/' . $fileName;
    }

    private function gdSupportsWebP(): bool
    {
        if (!function_exists('gd_info')) {
            return false;
        }
        $gd = gd_info();
        return !empty($gd['WebP Support']);
    }

    private function optimizeLogoImage(string $sourcePath, string $destinationPath, string $originalExtension): void
    {
        [$width, $height] = get_validated_image_dimensions($sourcePath);

        $maxWidth = 400;
        $maxHeight = 300;

        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int) ($width * $ratio);
            $newHeight = (int) ($height * $ratio);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        $sourceImage = $this->createLogoImageFromFile($sourcePath, $originalExtension);
        if (!$sourceImage) {
            throw new \Exception('No se pudo crear la imagen. Si es WebP animado, usa una imagen estática (JPG/PNG).');
        }

        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        if ($resizedImage === false) {
            imagedestroy($sourceImage);
            throw new \Exception('No se pudo crear la imagen redimensionada.');
        }

        // Preparar canvas para transparencia (PNG sin fondo): PNG, GIF y WebP pueden llevar alpha
        $hasAlpha = in_array($originalExtension, ['png', 'gif', 'webp']);
        if ($hasAlpha) {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefill($resizedImage, 0, 0, $transparent);
        }

        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Guardar como PNG: mantiene transparencia y es el estándar para logos
        imagepng($resizedImage, $destinationPath, 6);

        imagedestroy($sourceImage);
        imagedestroy($resizedImage);
    }

    private function createLogoImageFromFile(string $path, string $extension): \GdImage|false
    {
        $extension = strtolower($extension);
        if ($extension === 'webp') {
            if (!function_exists('imagecreatefromwebp')) {
                return false;
            }
            try {
                $img = @imagecreatefromwebp($path);
                return $img instanceof \GdImage ? $img : false;
            } catch (\Throwable) {
                return false;
            }
        }
        return match ($extension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png' => @imagecreatefrompng($path),
            'gif' => @imagecreatefromgif($path),
            default => false,
        };
    }

    /**
     * Borra del storage el logo anterior (ruta relativa en logo_path).
     */
    protected function deleteLogoFromStorage(?string $logoPath): void
    {
        if (!$logoPath) {
            return;
        }
        $fullPath = storage_path('app/public/' . $logoPath);
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }
}
