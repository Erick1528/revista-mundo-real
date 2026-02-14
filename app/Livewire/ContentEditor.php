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
    public $showMoreBlocks = false;
    public $blockSelectorIndex = null;
    public $galleryFiles = [];
    public $reviewFiles = [];
    public $isUpdateMode = false;
    public $initialBlocks = []; // Copia inicial de bloques en modo de actualización
    
    // Mensajes de notificación
    public $errorMessage = null;
    public $successMessage = null;
    public $debugMessage = null;

    protected $listeners = [
        'requestContentData' => 'provideContentData',
        'cleanupBlockResources' => 'cleanupAllBlockResources',
        'setContentBlocks' => 'setContentBlocks',
        'cleanupUnusedResources' => 'cleanupUnusedResources',

        // Usar para cancelar la edición de un artículo
        'cleanupNewResources' => 'cleanupNewResources',
    ];

    public function setContentBlocks($blocks)
    {
        $this->blocks = $blocks;
        $this->isUpdateMode = true;
        // Guardar una copia profunda de los bloques iniciales para comparar después
        $this->initialBlocks = json_decode(json_encode($blocks), true);
    }

    // Métodos para mensajes
    private function setError($message)
    {
        $this->clearMessages();
        $this->errorMessage = $message;
    }

    private function setSuccess($message)
    {
        $this->clearMessages();
        $this->successMessage = $message;
    }

    private function setDebug($message)
    {
        $this->clearMessages();
        $this->debugMessage = $message;
    }

    private function clearMessages()
    {
        $this->errorMessage = null;
        $this->successMessage = null;
        $this->debugMessage = null;
    }

    public function provideContentData()
    {
        // Enviar los bloques actuales al componente padre
        $data = [
            'blocks' => $this->blocks,
            'word_count' => $this->calculateWordCount(),
            'blocks_count' => count($this->blocks)
        ];
        
        // En Livewire 3, los eventos se propagan automáticamente a los componentes padre
        // Usar dispatch sin especificar destino para que se propague a todos los componentes padre
        $this->dispatch('contentDataResponse', $data);
    }

    public function cleanupAllBlockResources()
    {
        // Primero eliminar todas las galerías
        foreach ($this->blocks as $block) {
            if ($block['type'] === 'gallery' && !empty($block['images'])) {
                foreach ($block['images'] as $imagePath) {
                    $this->deleteImageFromStorage($imagePath);
                }
            }
        }

        // Después eliminar todas las imágenes
        foreach ($this->blocks as $block) {
            if ($block['type'] === 'image' && !empty($block['url'])) {
                $this->deleteImageFromStorage($block['url']);
            }
        }

        // Eliminar fotos de reseñas
        foreach ($this->blocks as $block) {
            if ($block['type'] === 'review' && !empty($block['reviews'])) {
                foreach ($block['reviews'] as $review) {
                    if (!empty($review['photo'])) {
                        $this->deleteImageFromStorage($review['photo']);
                    }
                }
            }
        }
    }

    /**
     * Extrae todos los recursos (imágenes) de un array de bloques
     */
    private function extractResourcesFromBlocks($blocks)
    {
        $resources = [];

        foreach ($blocks as $block) {
            // Imágenes de bloques tipo 'image'
            if ($block['type'] === 'image' && !empty($block['url'])) {
                $resources[] = $block['url'];
            }

            // Imágenes de galerías
            if ($block['type'] === 'gallery' && !empty($block['images'])) {
                foreach ($block['images'] as $imageUrl) {
                    if (!empty($imageUrl)) {
                        $resources[] = $imageUrl;
                    }
                }
            }

            // Fotos de reseñas
            if ($block['type'] === 'review' && !empty($block['reviews'])) {
                foreach ($block['reviews'] as $review) {
                    if (!empty($review['photo'])) {
                        $resources[] = $review['photo'];
                    }
                }
            }
        }

        return array_unique($resources); // Eliminar duplicados
    }

    /**
     * Limpia recursos no utilizados comparando bloques iniciales vs finales
     * Solo elimina recursos que estaban en los bloques iniciales pero ya no están en los finales
     * Se usa cuando se guarda el artículo en modo de actualización
     * 
     * @param array $data Opcional: array con 'finalBlocks' que contiene los bloques finales
     */
    public function cleanupUnusedResources($data = [])
    {
        if (!$this->isUpdateMode || empty($this->initialBlocks)) {
            return; // Solo funciona en modo de actualización
        }

        // Usar los bloques finales pasados como parámetro, o los bloques actuales del componente
        $finalBlocks = $data['finalBlocks'] ?? $this->blocks;

        // Extraer recursos de bloques iniciales y finales
        $initialResources = $this->extractResourcesFromBlocks($this->initialBlocks);
        $finalResources = $this->extractResourcesFromBlocks($finalBlocks);

        // Encontrar recursos que ya no se usan
        $unusedResources = array_diff($initialResources, $finalResources);

        // Eliminar recursos no utilizados
        // Usar forceDelete=true para eliminar incluso si están en initialBlocks
        // porque estos recursos ya no están en los bloques finales
        foreach ($unusedResources as $resource) {
            $this->deleteImageFromStorage($resource, true);
        }
    }

    /**
     * Limpia solo recursos nuevos (no existentes) cuando se cancela en modo de actualización
     * Protege los recursos que estaban en los bloques iniciales
     */
    public function cleanupNewResources()
    {
        if (!$this->isUpdateMode || empty($this->initialBlocks)) {
            // En modo de creación, limpiar todos los recursos
            $this->cleanupAllBlockResources();
            return;
        }

        // En modo de actualización, extraer recursos nuevos (que no están en initialBlocks)
        $initialResources = $this->extractResourcesFromBlocks($this->initialBlocks);
        $currentResources = $this->extractResourcesFromBlocks($this->blocks);

        // Encontrar recursos nuevos (que no estaban en los iniciales)
        $newResources = array_diff($currentResources, $initialResources);

        // Eliminar solo recursos nuevos
        foreach ($newResources as $resource) {
            $this->deleteImageFromStorage($resource);
        }
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

    public function toggleMoreBlocks()
    {
        $this->showMoreBlocks = !$this->showMoreBlocks;
    }

    public function deleteBlock($index)
    {
        try {
            if (isset($this->blocks[$index])) {
                $block = $this->blocks[$index];

                // Eliminar imágenes del storage al eliminar un bloque
                // El método deleteImageFromStorage ya verifica si está en modo de actualización
                // y si la imagen está en los bloques iniciales para proteger recursos existentes
                if ($block['type'] === 'image' && !empty($block['url'])) {
                    $this->deleteImageFromStorage($block['url']);
                }

                // Si es un bloque de galería, eliminar todas las imágenes del storage
                if ($block['type'] === 'gallery' && !empty($block['images'])) {
                    foreach ($block['images'] as $imageUrl) {
                        $this->deleteImageFromStorage($imageUrl);
                    }
                }

                // Si es un bloque de reseñas, eliminar todas las fotos del storage
                if ($block['type'] === 'review' && !empty($block['reviews'])) {
                    foreach ($block['reviews'] as $review) {
                        if (!empty($review['photo'])) {
                            $this->deleteImageFromStorage($review['photo']);
                        }
                    }
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
                        $duplicatedBlock['url'] = ''; // No copiar la imagen
                        $duplicatedBlock['caption'] = ''; // No copiar el caption
                        $duplicatedBlock['alt_text'] = ''; // No copiar el alt_text
                        $duplicatedBlock['layout'] = $blockToDuplicate['layout'] ?? 'full';
                        $duplicatedBlock['size'] = $blockToDuplicate['size'] ?? 'large';
                        $duplicatedBlock['credits'] = ''; // No copiar los credits
                        $duplicatedBlock['image_file'] = null;
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

                    case 'gallery':
                        $duplicatedBlock['images'] = [];
                        $duplicatedBlock['currentImage'] = 0;
                        break;

                    case 'review':
                        $duplicatedBlock['reviews'] = [
                            [
                                'id' => uniqid(),
                                'name' => '',
                                'title' => '',
                                'rating' => 5,
                                'content' => ''
                            ]
                        ];
                        $duplicatedBlock['currentReview'] = 0;
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

            case 'gallery':
                return array_merge($baseBlock, [
                    'images' => [],
                    'currentImage' => 0,
                    'caption' => '',
                ]);

            case 'review':
                return array_merge($baseBlock, [
                    'reviews' => [
                        [
                            'name' => '',
                            'title' => '',
                            'content' => '',
                            'rating' => 5,
                            'photo' => '',
                        ]
                    ],
                    'currentReview' => 0,
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

    /**
     * Elimina una imagen del storage
     * 
     * @param string $imageUrl URL de la imagen a eliminar
     * @param bool $forceDelete Si es true, elimina la imagen incluso si está en initialBlocks (usado por cleanupUnusedResources)
     */
    private function deleteImageFromStorage($imageUrl, $forceDelete = false)
    {
        try {
            // En modo de actualización, verificar si la imagen está en los bloques iniciales
            // Si está, no eliminarla (es una imagen existente que no debe perderse)
            // A menos que forceDelete sea true (cuando se llama desde cleanupUnusedResources)
            if (!$forceDelete && $this->isUpdateMode && !empty($this->initialBlocks)) {
                $initialResources = $this->extractResourcesFromBlocks($this->initialBlocks);
                if (in_array($imageUrl, $initialResources)) {
                    // La imagen está en los bloques iniciales, no eliminar
                    return;
                }
            }

            // Extraer la ruta del storage desde la URL
            if (str_contains($imageUrl, '/storage/')) {
                // Quitar '/storage/' del inicio para obtener la ruta relativa
                $relativePath = ltrim($imageUrl, '/storage/');
                $fullPath = storage_path('app/public/' . $relativePath);

                // Eliminar el archivo si existe
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
        } catch (\Exception $e) {
            // Silencioso: si hay error eliminando, no mostrar al usuario
            session()->flash('debug', 'Error eliminando archivo: ' . $e->getMessage());
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
        // Nota: imagedestroy() está deprecado desde PHP 8.5 (desde PHP 8.0 los objetos GdImage se liberan automáticamente)
        // Nueva forma (si necesitas liberar explícitamente): unset($sourceImage, $resizedImage);
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

    // Métodos para galería
    public function updatedGalleryFiles($value, $key)
    {
        if (is_array($value) && !empty($value)) {
            $blockIndex = (int) explode('.', $key)[0];

            // Verificar límite de imágenes
            $currentImageCount = count($this->blocks[$blockIndex]['images'] ?? []);
            $maxImages = 15;

            if ($currentImageCount >= $maxImages) {
                session()->flash('error', "Máximo {$maxImages} imágenes permitidas por galería");
                $this->galleryFiles[$blockIndex] = [];
                return;
            }

            foreach ($value as $file) {
                // Verificar límite antes de procesar cada imagen
                if ($currentImageCount >= $maxImages) {
                    session()->flash('error', "Máximo {$maxImages} imágenes permitidas. Solo se procesaron las primeras imágenes.");
                    break;
                }

                if ($file && $file->isValid()) {
                    try {
                        // Usar el mismo método que las imágenes normales
                        $imagePath = $this->processImageUpload($file);

                        // Agregar URL a la galería (misma estructura que imágenes normales)
                        $imageUrl = '/storage/' . $imagePath;
                        if (!isset($this->blocks[$blockIndex]['images'])) {
                            $this->blocks[$blockIndex]['images'] = [];
                        }
                        $this->blocks[$blockIndex]['images'][] = $imageUrl;
                        $currentImageCount++;
                    } catch (\Exception $e) {
                        session()->flash('error', 'Error al subir imagen: ' . $e->getMessage());
                    }
                }
            }

            // Limpiar archivo temporal
            $this->galleryFiles[$blockIndex] = [];
        }
    }

    public function removeGalleryImage($blockIndex, $imageIndex)
    {
        try {
            if (isset($this->blocks[$blockIndex]['images'][$imageIndex])) {
                // Eliminar archivo físico usando el mismo método que las imágenes normales
                $imageUrl = $this->blocks[$blockIndex]['images'][$imageIndex];
                $this->deleteImageFromStorage($imageUrl);

                // Remover de la lista
                array_splice($this->blocks[$blockIndex]['images'], $imageIndex, 1);

                // Ajustar currentImage si es necesario
                $imagesCount = count($this->blocks[$blockIndex]['images']);
                if ($this->blocks[$blockIndex]['currentImage'] >= $imagesCount && $imagesCount > 0) {
                    $this->blocks[$blockIndex]['currentImage'] = $imagesCount - 1;
                } elseif ($imagesCount === 0) {
                    $this->blocks[$blockIndex]['currentImage'] = 0;
                }

                session()->flash('message', 'Imagen eliminada correctamente');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar imagen: ' . $e->getMessage());
        }
    }

    public function setGalleryImage($blockIndex, $imageIndex)
    {
        if (isset($this->blocks[$blockIndex]['images'][$imageIndex])) {
            $this->blocks[$blockIndex]['currentImage'] = $imageIndex;
        }
    }

    public function changeGalleryImage($blockIndex, $direction)
    {
        if (!isset($this->blocks[$blockIndex]['images']) || empty($this->blocks[$blockIndex]['images'])) {
            return;
        }

        $currentIndex = $this->blocks[$blockIndex]['currentImage'] ?? 0;
        $maxIndex = count($this->blocks[$blockIndex]['images']) - 1;

        if ($direction === 'next' && $currentIndex < $maxIndex) {
            $this->blocks[$blockIndex]['currentImage'] = $currentIndex + 1;
        } elseif ($direction === 'prev' && $currentIndex > 0) {
            $this->blocks[$blockIndex]['currentImage'] = $currentIndex - 1;
        }
    }

    // Método específico para archivos de reseñas
    public function updatedReviewFiles($value, $key)
    {
        // Patrón: blockIndex.reviewIndex
        $parts = explode('.', $key);
        if (count($parts) >= 2 && isset($value) && $value) {
            $blockIndex = (int) $parts[0];
            $reviewIndex = (int) $parts[1];
            
            try {
                // Si ya había una foto, eliminar la anterior
                if (!empty($this->blocks[$blockIndex]['reviews'][$reviewIndex]['photo'])) {
                    $this->deleteImageFromStorage($this->blocks[$blockIndex]['reviews'][$reviewIndex]['photo']);
                }

                // Procesar y optimizar la nueva imagen usando la función existente
                $imagePath = $this->processImageUpload($value);

                // Asegurar que existe la estructura de reseña
                if (!isset($this->blocks[$blockIndex]['reviews'][$reviewIndex])) {
                    $this->blocks[$blockIndex]['reviews'][$reviewIndex] = [
                        'name' => '',
                        'title' => '',
                        'content' => '',
                        'rating' => 5,
                        'photo' => '',
                    ];
                }
                
                // Actualizar la URL en la reseña
                $this->blocks[$blockIndex]['reviews'][$reviewIndex]['photo'] = '/storage/' . $imagePath;

                session()->flash('message', 'Foto de reseña subida correctamente');
            } catch (\Exception $e) {
                session()->flash('error', 'Error al procesar la foto: ' . $e->getMessage());
            }
        }
    }

    // Método unificado para agregar reseñas
    public function addReview($blockIndex)
    {
        try {
            // Verificar que el índice existe
            if (!isset($this->blocks[$blockIndex])) {
                $this->setError("No existe bloque en índice $blockIndex");
                return;
            }
            
            // Verificar que es de tipo review
            $blockType = $this->blocks[$blockIndex]['type'] ?? 'sin tipo';
            if ($blockType !== 'review') {
                $this->setError("Este bloque es de tipo '$blockType', no 'review'. Necesitas un bloque de reseñas.");
                return;
            }
            
            // Crear nueva reseña
            $newReview = [
                'name' => '',
                'title' => '',
                'content' => '',
                'rating' => 5,
                'photo' => '',
            ];
            
            // Inicializar array de reseñas si no existe
            if (!isset($this->blocks[$blockIndex]['reviews'])) {
                $this->blocks[$blockIndex]['reviews'] = [];
            }
            
            // Verificar límite: solo una reseña por ahora
            if (count($this->blocks[$blockIndex]['reviews']) >= 5) {
                $this->setError("Ya existe una reseña en este bloque. Solo se permite una reseña por bloque por ahora.");
                return;
            }
            
            // Agregar la nueva reseña
            $this->blocks[$blockIndex]['reviews'][] = $newReview;
            $this->blocks[$blockIndex]['currentReview'] = 0;
            
            $this->setSuccess("Reseña agregada correctamente en bloque $blockIndex");
            
        } catch (\Exception $e) {
            $this->setError('Error al agregar reseña: ' . $e->getMessage());
        }
    }

    // Método para debug: mostrar información de todos los bloques
    public function showBlocks()
    {
        $blocksInfo = "Bloques disponibles:\n";
        foreach ($this->blocks as $index => $block) {
            $type = $block['type'] ?? 'sin tipo';
            $id = $block['id'] ?? 'sin ID';
            $blocksInfo .= "Índice $index: $type (ID: $id)\n";
        }
        $this->setDebug($blocksInfo);
    }

    public function removeReview($blockIndex, $reviewIndex)
    {
        try {
            if (
                isset($this->blocks[$blockIndex]) &&
                $this->blocks[$blockIndex]['type'] === 'review' &&
                isset($this->blocks[$blockIndex]['reviews'][$reviewIndex]) &&
                count($this->blocks[$blockIndex]['reviews']) > 1
            ) {
                // Eliminar foto si existe
                $review = $this->blocks[$blockIndex]['reviews'][$reviewIndex];
                if (!empty($review['photo'])) {
                    $this->deleteImageFromStorage($review['photo']);
                }
                
                // Eliminar reseña del array
                array_splice($this->blocks[$blockIndex]['reviews'], $reviewIndex, 1);
                
                // Ajustar currentReview si es necesario
                $reviewCount = count($this->blocks[$blockIndex]['reviews']);
                if ($this->blocks[$blockIndex]['currentReview'] >= $reviewCount && $reviewCount > 0) {
                    $this->blocks[$blockIndex]['currentReview'] = $reviewCount - 1;
                } elseif ($reviewCount === 0) {
                    $this->blocks[$blockIndex]['currentReview'] = 0;
                }
                
                session()->flash('debug', 'Reseña eliminada');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar reseña: ' . $e->getMessage());
        }
    }

    public function changeReview($blockIndex, $direction)
    {
        try {
            if (!isset($this->blocks[$blockIndex]['reviews']) || empty($this->blocks[$blockIndex]['reviews'])) {
                return;
            }

            $currentIndex = $this->blocks[$blockIndex]['currentReview'] ?? 0;
            $maxIndex = count($this->blocks[$blockIndex]['reviews']) - 1;

            if ($direction === 'next' && $currentIndex < $maxIndex) {
                $this->blocks[$blockIndex]['currentReview'] = $currentIndex + 1;
            } elseif ($direction === 'prev' && $currentIndex > 0) {
                $this->blocks[$blockIndex]['currentReview'] = $currentIndex - 1;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar reseña: ' . $e->getMessage());
        }
    }

    public function setCurrentReview($blockIndex, $reviewIndex)
    {
        try {
            if (isset($this->blocks[$blockIndex]['reviews'][$reviewIndex])) {
                $this->blocks[$blockIndex]['currentReview'] = $reviewIndex;
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al seleccionar reseña: ' . $e->getMessage());
        }
    }

    public function removeReviewPhoto($blockIndex, $reviewIndex)
    {
        try {
            if (
                isset($this->blocks[$blockIndex]['reviews'][$reviewIndex]) &&
                !empty($this->blocks[$blockIndex]['reviews'][$reviewIndex]['photo'])
            ) {
                // Usar la función existente para eliminar del storage
                $photoUrl = $this->blocks[$blockIndex]['reviews'][$reviewIndex]['photo'];
                $this->deleteImageFromStorage($photoUrl);

                // Limpiar la foto de la reseña
                $this->blocks[$blockIndex]['reviews'][$reviewIndex]['photo'] = '';

                session()->flash('message', 'Foto eliminada correctamente');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar foto: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.content-editor');
    }
}
