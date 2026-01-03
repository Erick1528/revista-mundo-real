<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class ContentEditor extends Component
{
    use WithFileUploads;
    public $blocks = [];
    public $showBlockSelector = false;
    public $blockSelectorIndex = null;

    protected $listeners = ['requestContentData' => 'provideContentData'];

    public function provideContentData()
    {
        // Enviar los bloques actuales al componente padre
        $this->dispatch('contentDataResponse', [
            'blocks' => $this->blocks,
            'word_count' => $this->calculateWordCount(),
            'blocks_count' => count($this->blocks)
        ]);
    }

    private function calculateWordCount()
    {
        $totalWords = 0;
        
        foreach ($this->blocks as $block) {
            switch ($block['type']) {
                case 'paragraph':
                case 'heading':
                case 'quote':
                    $content = $block['content'] ?? '';
                    $totalWords += str_word_count(strip_tags($content));
                    break;
                    
                case 'list':
                    if (isset($block['items']) && is_array($block['items'])) {
                        foreach ($block['items'] as $item) {
                            $totalWords += str_word_count(strip_tags($item));
                        }
                    }
                    break;
            }
        }
        
        return $totalWords;
    }

    public function mount()
    {
        // Inicializar con array vacío si no se pasan bloques
        $this->blocks = $this->blocks ?: [];
    }

    public function addBlock($type, $position = null)
    {
        try {
            session()->flash('debug', "Intentando agregar bloque: tipo=$type, posición=$position, bloques actuales=" . count($this->blocks));

            $newBlock = $this->createBlock($type);

            if ($position !== null && is_numeric($position) && $position >= 0) {
                // Insertar en posición específica (después del índice dado)
                $insertPosition = $position + 1;
                if ($insertPosition <= count($this->blocks)) {
                    array_splice($this->blocks, $insertPosition, 0, [$newBlock]);
                } else {
                    // Si la posición es inválida, agregar al final
                    $this->blocks[] = $newBlock;
                }
            } else {
                // Agregar al final
                $this->blocks[] = $newBlock;
            }

            $this->closeBlockSelector();
            session()->flash('debug', 'Bloque agregado exitosamente. Total bloques: ' . count($this->blocks));
        } catch (\Exception $e) {
            session()->flash('debug', 'Error al agregar bloque: ' . $e->getMessage());
        }
    }

    public function openBlockSelector($index = null)
    {
        $this->showBlockSelector = true;
        $this->blockSelectorIndex = $index;
        // Debug
        session()->flash('debug', 'Selector mostrado - Index: ' . $index);
    }

    public function testClick()
    {
        session()->flash('debug', 'Test click funcionando!');
        $this->showBlockSelector = true;
    }

    public function closeBlockSelector()
    {
        $this->showBlockSelector = false;
        $this->blockSelectorIndex = null;
    }

    public function deleteBlock($index)
    {
        try {
            if (isset($this->blocks[$index])) {
                $block = $this->blocks[$index];

                // Si es un bloque de imagen, eliminar la imagen del storage
                if ($block['type'] === 'image' && !empty($block['url'])) {
                    $this->deleteImageFromStorage($block['url']);
                }

                // Eliminar el bloque del array
                unset($this->blocks[$index]);
                // Reindexar el array para mantener índices consecutivos
                $this->blocks = array_values($this->blocks);

                session()->flash('message', 'Bloque eliminado correctamente');
            } else {
                session()->flash('error', "Error: No se encontró bloque en índice $index");
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar bloque: ' . $e->getMessage());
        }
    }

    public function moveBlockUp($index)
    {
        try {
            // Verificar que el índice es válido y no es el primero
            if ($index > 0 && isset($this->blocks[$index]) && isset($this->blocks[$index - 1])) {
                // Intercambiar posiciones con el bloque anterior
                $temp = $this->blocks[$index];
                $this->blocks[$index] = $this->blocks[$index - 1];
                $this->blocks[$index - 1] = $temp;

                session()->flash('debug', "Bloque movido hacia arriba. Índice: $index -> " . ($index - 1));
            } else {
                session()->flash('debug', "No se puede mover hacia arriba. Índice: $index");
            }
        } catch (\Exception $e) {
            session()->flash('debug', 'Error al mover bloque hacia arriba: ' . $e->getMessage());
        }
    }

    public function moveBlockDown($index)
    {
        try {
            // Verificar que el índice es válido y no es el último
            if ($index >= 0 && $index < count($this->blocks) - 1 && isset($this->blocks[$index]) && isset($this->blocks[$index + 1])) {
                // Intercambiar posiciones con el bloque siguiente
                $temp = $this->blocks[$index];
                $this->blocks[$index] = $this->blocks[$index + 1];
                $this->blocks[$index + 1] = $temp;

                session()->flash('debug', "Bloque movido hacia abajo. Índice: $index -> " . ($index + 1));
            } else {
                session()->flash('debug', "No se puede mover hacia abajo. Índice: $index");
            }
        } catch (\Exception $e) {
            session()->flash('debug', 'Error al mover bloque hacia abajo: ' . $e->getMessage());
        }
    }

    public function duplicateBlock($index)
    {
        try {
            if (isset($this->blocks[$index])) {
                // Crear una copia profunda del bloque
                $blockToDuplicate = $this->blocks[$index];

                // Hacer copia profunda de todo el contenido
                $duplicatedBlock = [
                    'id' => uniqid('block_'),
                    'type' => $blockToDuplicate['type'],
                    'created_at' => now()->toISOString(),
                ];

                // Copiar contenido específico según el tipo de bloque
                switch ($blockToDuplicate['type']) {
                    case 'paragraph':
                        $duplicatedBlock['content'] = $blockToDuplicate['content'] ?? '';
                        break;

                    case 'heading':
                        $duplicatedBlock['content'] = $blockToDuplicate['content'] ?? '';
                        $duplicatedBlock['level'] = $blockToDuplicate['level'] ?? 2;
                        break;

                    case 'image':
                        $duplicatedBlock['url'] = $blockToDuplicate['url'] ?? '';
                        $duplicatedBlock['caption'] = $blockToDuplicate['caption'] ?? '';
                        $duplicatedBlock['alt_text'] = $blockToDuplicate['alt_text'] ?? '';
                        $duplicatedBlock['layout'] = $blockToDuplicate['layout'] ?? 'full';
                        $duplicatedBlock['size'] = $blockToDuplicate['size'] ?? 'large';
                        $duplicatedBlock['credits'] = $blockToDuplicate['credits'] ?? '';
                        break;

                    case 'quote':
                        $duplicatedBlock['content'] = $blockToDuplicate['content'] ?? '';
                        $duplicatedBlock['author'] = $blockToDuplicate['author'] ?? '';
                        break;

                    case 'list':
                        $duplicatedBlock['listType'] = $blockToDuplicate['listType'] ?? 'bullet';
                        $duplicatedBlock['items'] = $blockToDuplicate['items'] ?? [''];
                        break;

                    case 'video':
                        $duplicatedBlock['url'] = $blockToDuplicate['url'] ?? '';
                        $duplicatedBlock['caption'] = $blockToDuplicate['caption'] ?? '';
                        $duplicatedBlock['provider'] = $blockToDuplicate['provider'] ?? '';
                        break;

                    default:
                        $duplicatedBlock['content'] = $blockToDuplicate['content'] ?? '';
                        break;
                }

                // Insertar el bloque duplicado inmediatamente después del original
                array_splice($this->blocks, $index + 1, 0, [$duplicatedBlock]);

                session()->flash('debug', "Bloque duplicado con contenido. Índice original: $index. Total bloques: " . count($this->blocks));
            } else {
                session()->flash('debug', "Error: No se encontró bloque en índice $index para duplicar");
            }
        } catch (\Exception $e) {
            session()->flash('debug', 'Error al duplicar bloque: ' . $e->getMessage());
        }
    }

    public function addListItem($blockIndex)
    {
        try {
            if (isset($this->blocks[$blockIndex]) && $this->blocks[$blockIndex]['type'] === 'list') {
                // Agregar un nuevo elemento vacío al final de la lista
                $this->blocks[$blockIndex]['items'][] = '';
                session()->flash('debug', "Elemento agregado a lista en bloque $blockIndex");
            }
        } catch (\Exception $e) {
            session()->flash('debug', 'Error al agregar elemento: ' . $e->getMessage());
        }
    }

    public function removeListItem($blockIndex, $itemIndex)
    {
        try {
            if (
                isset($this->blocks[$blockIndex]) &&
                $this->blocks[$blockIndex]['type'] === 'list' &&
                isset($this->blocks[$blockIndex]['items'][$itemIndex]) &&
                count($this->blocks[$blockIndex]['items']) > 1
            ) {

                // Remover el elemento específico
                unset($this->blocks[$blockIndex]['items'][$itemIndex]);
                // Reindexar el array
                $this->blocks[$blockIndex]['items'] = array_values($this->blocks[$blockIndex]['items']);

                session()->flash('debug', "Elemento $itemIndex removido de lista en bloque $blockIndex");
            }
        } catch (\Exception $e) {
            session()->flash('debug', 'Error al remover elemento: ' . $e->getMessage());
        }
    }

    private function createBlock($type)
    {
        if (empty($type) || !is_string($type)) {
            $type = 'paragraph';
        }

        $baseBlock = [
            'id' => uniqid('block_'),
            'type' => $type,
            'created_at' => now()->toISOString(),
        ];

        switch ($type) {
            case 'paragraph':
                return array_merge($baseBlock, [
                    'content' => '',
                ]);

            case 'heading':
                return array_merge($baseBlock, [
                    'content' => '',
                    'level' => 2, // H2 por defecto
                ]);

            case 'image':
                return array_merge($baseBlock, [
                    'url' => '',
                    'caption' => '',
                    'alt_text' => '',
                    'layout' => 'full', // full, text-right, text-left, text-below
                    'size' => 'large', // small, medium, large
                    'credits' => '',
                    'image_file' => null,
                ]);

            case 'quote':
                return array_merge($baseBlock, [
                    'content' => '',
                    'author' => '',
                ]);

            case 'list':
                return array_merge($baseBlock, [
                    'listType' => 'bullet', // bullet | numbered
                    'items' => [''], // Comenzar con 1 elemento vacío
                ]);

            case 'video':
                return array_merge($baseBlock, [
                    'url' => '',
                    'caption' => '',
                    'provider' => '', // youtube | vimeo | other
                ]);

            default:
                return array_merge($baseBlock, [
                    'content' => '',
                ]);
        }
    }

    public function updatedBlocks($value, $key)
    {
        // Manejar subida de archivos de imagen
        if (str_contains($key, '.image_file')) {
            $blockIndex = (int) explode('.', $key)[0];

            if (isset($this->blocks[$blockIndex]['image_file']) && $this->blocks[$blockIndex]['image_file']) {
                $file = $this->blocks[$blockIndex]['image_file'];

                try {
                    // Si ya había una imagen, eliminar la anterior
                    if (!empty($this->blocks[$blockIndex]['url'])) {
                        $this->deleteImageFromStorage($this->blocks[$blockIndex]['url']);
                    }

                    // Procesar y optimizar la nueva imagen
                    $imagePath = $this->processImageUpload($file);

                    // Actualizar la URL del bloque (solo ruta relativa)
                    $this->blocks[$blockIndex]['url'] = '/storage/' . $imagePath;

                    // Limpiar el campo temporal
                    $this->blocks[$blockIndex]['image_file'] = null;

                    session()->flash('message', 'Imagen subida y optimizada correctamente');
                } catch (\Exception $e) {
                    session()->flash('error', 'Error al procesar la imagen: ' . $e->getMessage());
                    $this->blocks[$blockIndex]['image_file'] = null;
                }
            }
        }
    }

    public function removeImage($blockIndex)
    {
        try {
            if (isset($this->blocks[$blockIndex]) && $this->blocks[$blockIndex]['type'] === 'image') {
                // Eliminar imagen del storage si existe
                if (!empty($this->blocks[$blockIndex]['url'])) {
                    $this->deleteImageFromStorage($this->blocks[$blockIndex]['url']);
                }

                // Limpiar datos de la imagen
                $this->blocks[$blockIndex]['url'] = '';
                $this->blocks[$blockIndex]['caption'] = '';
                $this->blocks[$blockIndex]['alt_text'] = '';
                $this->blocks[$blockIndex]['credits'] = '';

                session()->flash('message', 'Imagen eliminada correctamente');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar imagen: ' . $e->getMessage());
        }
    }

    private function deleteImageFromStorage($imageUrl)
    {
        try {
            // Extraer la ruta del storage desde la URL
            if (str_contains($imageUrl, '/storage/')) {
                $relativePath = str_replace(asset('storage/'), '', $imageUrl);
                $fullPath = storage_path('app/public/' . $relativePath);

                // Eliminar el archivo si existe
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
        } catch (\Exception $e) {
            // Silencioso: si hay error eliminando, no mostrar al usuario
            Log::warning('No se pudo eliminar imagen: ' . $e->getMessage());
        }
    }

    private function processImageUpload($file)
    {
        // Generar nombre único para la imagen (siempre WebP)
        $timestamp = now()->format('Ymd_His');
        $randomString = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 8);
        $originalExtension = strtolower($file->getClientOriginalExtension());

        // Validar extensión de entrada
        if (!in_array($originalExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'])) {
            throw new \Exception('Formato de imagen no soportado. Use JPG, PNG, GIF, WebP o AVIF.');
        }

        // Siempre generar archivo WebP
        $fileName = "revista_{$timestamp}_{$randomString}.webp";

        // Crear directorio si no existe
        $uploadPath = storage_path('app/public/images');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Ruta temporal del archivo subido
        $tempPath = $file->getRealPath();
        $finalPath = $uploadPath . '/' . $fileName;

        // Optimizar imagen y convertir siempre a WebP
        $this->optimizeImage($tempPath, $finalPath, $originalExtension);

        return 'images/' . $fileName;
    }

    private function optimizeImage($sourcePath, $destinationPath, $originalExtension)
    {
        // Obtener dimensiones originales
        list($width, $height) = getimagesize($sourcePath);

        // Calcular nuevas dimensiones (máximo 1200px de ancho)
        $maxWidth = 1200;
        $maxHeight = 800;

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

        // Preservar transparencia para PNG y GIF (se convertirá a WebP con transparencia)
        if ($originalExtension === 'png' || $originalExtension === 'gif') {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefill($resizedImage, 0, 0, $transparent);
        }

        // Redimensionar imagen
        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Guardar siempre como WebP
        imagewebp($resizedImage, $destinationPath, 85); // Calidad 85%

        // Liberar memoria
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        // Verificar tamaño del archivo y recomprimir si es necesario
        $this->adjustFileSize($destinationPath, 'webp');
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
            case 'avif':
                // AVIF requiere extensión especial, convertir a WebP como fallback
                if (function_exists('imagecreatefromavif')) {
                    return imagecreatefromavif($path);
                } else {
                    // Si no hay soporte nativo, intentar crear desde WebP
                    return imagecreatefromwebp($path);
                }
            default:
                return false;
        }
    }

    private function saveOptimizedImage($image, $path, $extension)
    {
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($image, $path, 85); // Calidad 85%
                break;
            case 'png':
                imagepng($image, $path, 6); // Compresión nivel 6
                break;
            case 'gif':
                imagegif($image, $path);
                break;
            case 'webp':
                imagewebp($image, $path, 85); // Calidad 85%
                break;
            case 'avif':
                // AVIF requiere extensión especial, guardar como WebP como fallback
                if (function_exists('imageavif')) {
                    imageavif($image, $path, 85); // Calidad 85%
                } else {
                    // Si no hay soporte nativo, guardar como WebP
                    imagewebp($image, $path, 85);
                }
                break;
        }
    }

    private function adjustFileSize($path, $extension)
    {
        $maxSize = 150 * 1024; // 150KB en bytes
        $minSize = 100 * 1024; // 100KB en bytes
        $currentSize = filesize($path);

        // Si el archivo está dentro del rango deseado, no hacer nada
        if ($currentSize >= $minSize && $currentSize <= $maxSize) {
            return;
        }

        // Si es muy grande, reducir calidad (siempre WebP ahora)
        if ($currentSize > $maxSize) {
            $image = imagecreatefromwebp($path);

            // Reducir calidad progresivamente
            $qualities = [75, 65, 55, 45, 35, 25];

            foreach ($qualities as $quality) {
                imagewebp($image, $path, $quality);

                $newSize = filesize($path);
                if ($newSize <= $maxSize && $newSize >= $minSize) {
                    break;
                }
            }

            imagedestroy($image);
        }
    }

    public function render()
    {
        return view('livewire.content-editor');
    }
}
