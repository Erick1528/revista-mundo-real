<?php

namespace App\Livewire;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

// TODO:
// Mejorar el editor de contenido para manejar listas, texto enriquecido, titulos y otras cosas en un mismo bloque
// Calcular el tiempo de lectura automáticamente basado en el contenido
// Agregar campo de caption para la galeria de imagenes
// Mejorar el bloque de imagen (alt text, credits, tamaños, layouts)
// Agregar bloques faltantes
// Hacer las preview como desplegable y no fija

class CreateArticle extends Component
{

    use WithFileUploads;

    // Basic information
    public $title;
    public $subtitle;
    public $attribution;
    public $summary;

    // Media
    public $image;

    // Content
    public $content = [];

    // Classification
    public $section;
    public $tags = [];
    public $tagInput = ''; // Campo temporal para escribir nuevos tags
    public $related_articles = [];
    public $relatedArticleSearch = ''; // Campo para buscar artículos relacionados

    // Publication & Visibility
    public $visibility;
    public $published_at;

    // SEO & Metadata
    public $meta_description;
    public $reading_time;

    // Accordion state
    public $openSections = [
        'basic' => true,
        'image' => false,
        'content' => false,
        'classification' => false,
        'publication' => false,
        'seo' => false,
        'metrics' => false,
    ];

    // Flag para controlar el flujo de validación
    public $waitingForContentData = false;

    // Errores específicos de validación de contenido
    public $contentErrors = [];

    // Modal de confirmación para cancelar
    public $showCancelModal = false;

    public function updatedImage()
    {
        // Validar extensión inmediatamente cuando se selecciona archivo
        if ($this->image) {
            $extension = strtolower($this->image->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($extension, $allowedExtensions)) {
                $this->image = null;
                $this->addError('image', 'Extensión no soportada. Solo se permiten: JPG, PNG, GIF, WebP');
                return;
            }
        }

        // Limpiar errores previos si la extensión es válida
        $this->resetErrorBag('image');
    }

    // Listeners para eventos
    protected $listeners = [
        'contentDataResponse' => 'receiveContentData',
        'cancelCreateArticle' => 'cancel',
    ];

    // Agregar funciones para rellenar los arrays de tags y related_articles
    protected $rules = [
        'title' => 'required|string|max:255',
        'subtitle' => 'required|string|max:255',

        'attribution' => 'nullable|string|max:255',
        'summary' => 'nullable|string|max:500',

        'image' => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:10240', // Max 10MB

        'content' => 'required|array|min:1',

        'section' => 'required|in:destinations,inspiring_stories,social_events,health_wellness,gastronomy,living_culture',

        'tags' => 'required|array|min:1|max:10',
        'tags.*' => 'string|max:50',

        'related_articles' => 'nullable|array|max:5',
        'related_articles.*' => 'integer|exists:articles,id',

        'visibility' => 'required|in:public,private',

        'published_at' => 'nullable|date|after:now',

        'meta_description' => 'nullable|string|max:160',
        'reading_time' => 'nullable|integer|min:1|max:60',
    ];

    protected $messages = [
        // Basic Information
        'title.required' => 'El título del artículo es obligatorio.',
        'title.string' => 'El título debe ser texto válido.',
        'title.max' => 'El título no puede tener más de 255 caracteres.',

        'subtitle.required' => 'El subtítulo del artículo es obligatorio.',
        'subtitle.string' => 'El subtítulo debe ser texto válido.',
        'subtitle.max' => 'El subtítulo no puede tener más de 255 caracteres.',

        'attribution.string' => 'Los créditos deben ser texto válido.',
        'attribution.max' => 'Los créditos no pueden tener más de 255 caracteres.',

        'summary.string' => 'El resumen debe ser texto válido.',
        'summary.max' => 'El resumen no puede tener más de 500 caracteres.',

        // Media
        'image.required' => 'La imagen principal es obligatoria.',
        'image.image' => 'El archivo debe ser una imagen válida.',
        'image.mimes' => 'La imagen debe ser de tipo: jpeg, jpg, png, webp o gif.',
        'image.max' => 'La imagen no puede ser mayor a 10MB.',

        // Content
        'content.required' => 'El contenido del artículo es obligatorio.',
        'content.array' => 'El contenido debe tener un formato válido.',
        'content.min' => 'Debe agregar al menos un bloque de contenido.',

        // Classification
        'section.required' => 'Debe seleccionar una sección para el artículo.',
        'section.in' => 'La sección seleccionada no es válida.',

        'tags.required' => 'Debe agregar al menos un tag.',
        'tags.array' => 'Los tags deben tener un formato válido.',
        'tags.min' => 'Debe agregar al menos un tag.',
        'tags.max' => 'No puede agregar más de 10 tags.',
        'tags.*.string' => 'Cada tag debe ser texto válido.',
        'tags.*.max' => 'Cada tag no puede tener más de 50 caracteres.',

        'related_articles.array' => 'Los artículos relacionados deben tener un formato válido.',
        'related_articles.max' => 'No puede agregar más de 5 artículos relacionados.',
        'related_articles.*.integer' => 'El ID del artículo relacionado debe ser un número válido.',
        'related_articles.*.exists' => 'El artículo relacionado seleccionado no existe.',

        // Publication & Visibility
        'visibility.required' => 'Debe seleccionar el tipo de visibilidad.',
        'visibility.in' => 'El tipo de visibilidad seleccionado no es válido.',

        'published_at.date' => 'La fecha de publicación debe ser una fecha válida.',
        'published_at.after' => 'La fecha de publicación debe ser posterior a la fecha actual.',

        // SEO & Metadata
        'meta_description.string' => 'La meta descripción debe ser texto válido.',
        'meta_description.max' => 'La meta descripción no puede tener más de 160 caracteres.',

        'reading_time.integer' => 'El tiempo de lectura debe ser un número entero.',
        'reading_time.min' => 'El tiempo de lectura debe ser al menos 1 minuto.',
        'reading_time.max' => 'El tiempo de lectura no puede ser mayor a 60 minutos.',
    ];

    public function toggleSection($section)
    {
        $this->openSections[$section] = !$this->openSections[$section];
    }

    public function removeImage()
    {
        $this->image = null;
        $this->resetValidation('image');
        $this->resetErrorBag('image');
    }

    public function addTag()
    {
        // Limpiar y validar el input
        $tag = trim($this->tagInput);

        if (empty($tag)) {
            return;
        }

        // Verificar si el tag ya existe
        if (in_array($tag, $this->tags)) {
            $this->addError('tagInput', 'Este tag ya ha sido agregado.');
            return;
        }

        // Verificar límite máximo de tags
        if (count($this->tags) >= 10) {
            $this->addError('tagInput', 'Máximo 10 tags permitidos.');
            return;
        }

        // Verificar longitud del tag
        if (strlen($tag) > 50) {
            $this->addError('tagInput', 'El tag no puede tener más de 50 caracteres.');
            return;
        }

        // Agregar el tag al array
        $this->tags[] = $tag;

        // Limpiar el input y errores
        $this->tagInput = '';
        $this->resetErrorBag('tagInput');
    }

    public function removeTag($index)
    {
        if (isset($this->tags[$index])) {
            unset($this->tags[$index]);
            $this->tags = array_values($this->tags); // Reindexar el array
        }
    }

    public function addRelatedArticle($articleId, $articleTitle, $articleSection = null, $articleAttribution = null, $articleSummary = null)
    {
        // Verificar si el artículo ya está agregado
        foreach ($this->related_articles as $article) {
            if ($article['id'] == $articleId) {
                $this->addError('relatedArticleSearch', 'Este artículo ya ha sido agregado.');
                return;
            }
        }

        // Verificar límite máximo de artículos relacionados
        if (count($this->related_articles) >= 5) {
            $this->addError('relatedArticleSearch', 'Máximo 5 artículos relacionados permitidos.');
            return;
        }

        // Agregar el artículo al array
        $this->related_articles[] = [
            'id' => $articleId,
            'title' => $articleTitle,
            'section' => $articleSection,
            'attribution' => $articleAttribution,
            'summary' => $articleSummary
        ];

        // Limpiar el input y errores
        $this->relatedArticleSearch = '';
        $this->resetErrorBag('relatedArticleSearch');
    }

    public function removeRelatedArticle($index)
    {
        if (isset($this->related_articles[$index])) {
            unset($this->related_articles[$index]);
            $this->related_articles = array_values($this->related_articles); // Reindexar el array
        }
    }

    public function getSearchSuggestionsProperty()
    {
        if (empty($this->relatedArticleSearch) || strlen($this->relatedArticleSearch) < 2) {
            return [];
        }

        // Mapear términos de búsqueda en español a secciones en inglés
        $sectionMapping = [
            'destino' => 'destinations',
            'destinos' => 'destinations',
            'viaje' => 'destinations',
            'turismo' => 'destinations',
            'historia' => 'inspiring_stories',
            'historias' => 'inspiring_stories',
            'inspirar' => 'inspiring_stories',
            'inspiran' => 'inspiring_stories',
            'inspiración' => 'inspiring_stories',
            'evento' => 'social_events',
            'eventos' => 'social_events',
            'social' => 'social_events',
            'sociales' => 'social_events',
            'festival' => 'social_events',
            'festivales' => 'social_events',
            'salud' => 'health_wellness',
            'bienestar' => 'health_wellness',
            'wellness' => 'health_wellness',
            'equilibrio' => 'health_wellness',
            'mindfulness' => 'health_wellness',
            'meditación' => 'health_wellness',
            'yoga' => 'health_wellness',
            'gastronomía' => 'gastronomy',
            'gastronomia' => 'gastronomy',
            'comida' => 'gastronomy',
            'cocina' => 'gastronomy',
            'sabor' => 'gastronomy',
            'sabores' => 'gastronomy',
            'cultura' => 'living_culture',
            'cultural' => 'living_culture',
            'tradición' => 'living_culture',
            'tradicion' => 'living_culture',
            'ancestral' => 'living_culture',
            'artesanía' => 'living_culture',
            'artesania' => 'living_culture',
        ];

        // Buscar secciones que coincidan con la búsqueda
        $searchTerm = strtolower($this->relatedArticleSearch);
        $matchingSections = [];
        foreach ($sectionMapping as $keyword => $section) {
            if (strpos($searchTerm, $keyword) !== false) {
                $matchingSections[] = $section;
            }
        }

        // Obtener artículos publicados con los campos necesarios
        return Article::where('status', 'published')
            ->where(function ($query) use ($matchingSections) {
                $query->where('title', 'like', '%' . $this->relatedArticleSearch . '%')
                    ->orWhere('subtitle', 'like', '%' . $this->relatedArticleSearch . '%')
                    ->orWhere('summary', 'like', '%' . $this->relatedArticleSearch . '%');

                // Si hay secciones que coinciden, también buscar por sección
                if (!empty($matchingSections)) {
                    $query->orWhereIn('section', $matchingSections);
                }
            })
            ->select('id', 'title', 'section', 'attribution', 'summary', 'published_at')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'section' => $article->section,
                    'attribution' => $article->attribution,
                    'summary' => $article->summary ? substr($article->summary, 0, 80) . '...' : null,
                    'published_at' => $article->published_at ? $article->published_at->format('d M Y') : null
                ];
            })->toArray();
    }

    public function receiveContentData($data)
    {
        // Actualizar la propiedad content con los datos recibidos del editor
        $this->content = $data['blocks'] ?? [];

        // Si estábamos esperando los datos para proceder con la validación
        if ($this->waitingForContentData) {
            $this->waitingForContentData = false;
            $this->proceedWithValidation();
        }
    }

    private function validateBlocks()
    {
        $errors = [];

        for ($i = 0; $i < count($this->content); $i++) {
            $block = $this->content[$i];
            $blockNumber = $i + 1;

            // Verificar que el bloque tenga tipo
            if (!isset($block['type'])) {
                $errors[] = "Bloque #$blockNumber: Tipo de bloque no válido";
                continue;
            }

            // Validar contenido según el tipo de bloque
            switch ($block['type']) {
                case 'paragraph':
                    if (empty(trim($block['content'] ?? ''))) {
                        $errors[] = "Bloque #$blockNumber (Párrafo): No puede estar vacío";
                    }
                    break;

                case 'heading':
                    if (empty(trim($block['content'] ?? ''))) {
                        $errors[] = "Bloque #$blockNumber (Título): No puede estar vacío";
                    }
                    break;

                case 'quote':
                    if (empty(trim($block['content'] ?? ''))) {
                        $errors[] = "Bloque #$blockNumber (Cita): No puede estar vacío";
                    }
                    break;

                case 'list':
                    if (empty($block['items']) || !is_array($block['items'])) {
                        $errors[] = "Bloque #$blockNumber (Lista): Debe tener al menos un elemento";
                    } else {
                        $hasContent = false;
                        foreach ($block['items'] as $item) {
                            if (!empty(trim($item))) {
                                $hasContent = true;
                                break;
                            }
                        }
                        if (!$hasContent) {
                            $errors[] = "Bloque #$blockNumber (Lista): Debe tener al menos un elemento con contenido";
                        }
                    }
                    break;

                case 'image':
                    if (empty($block['url'] ?? '')) {
                        $errors[] = "Bloque #$blockNumber (Imagen): Debe tener una URL válida";
                    }
                    break;

                case 'video':
                    if (empty($block['url'] ?? '')) {
                        $errors[] = "Bloque #$blockNumber (Video): Debe tener una URL válida";
                    }
                    break;

                case 'gallery':
                    if (empty($block['images']) || !is_array($block['images']) || count($block['images']) === 0) {
                        $errors[] = "Bloque #$blockNumber (Galería): Debe tener al menos una imagen";
                    } else {
                        // Validar que las URLs de imágenes sean válidas
                        foreach ($block['images'] as $index => $imageUrl) {
                            if (empty(trim($imageUrl))) {
                                $errors[] = "Bloque #$blockNumber (Galería): Imagen #" . ($index + 1) . " tiene una URL vacía";
                            }
                        }
                    }
                    break;

                case 'separator':
                    // Los separadores no necesitan validación de contenido
                    break;

                default:
                    $errors[] = "Bloque #$blockNumber: Tipo desconocido '{$block['type']}'";
                    break;
            }
        }

        return $errors;
    }

    private function proceedWithValidation()
    {
        try {
            // Limpiar errores previos de contenido
            $this->contentErrors = [];

            // Si hay bloques, limpiar error de validación de Laravel para content
            if (!empty($this->content)) {
                $this->resetErrorBag('content');
            }

            // Validar bloques de contenido antes de la validación general
            $blockErrors = $this->validateBlocks();
            if (!empty($blockErrors)) {
                // Guardar errores en la propiedad específica
                $this->contentErrors = $blockErrors;
                // Abrir la sección de contenido para mostrar los errores
                $this->openSections['content'] = true;
                session()->flash('error', 'Hay errores en el contenido del artículo. Revisa los bloques vacíos.');
                return;
            }

            // Si hay bloques, validar sin las reglas de content para evitar mensaje duplicado
            if (!empty($this->content)) {
                $rules = $this->rules;
                unset($rules['content']);
                $this->validate($rules);
            } else {
                $this->validate();
            }

            // Preparar datos del artículo
            $articleData = [
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'slug' => $this->generateUniqueSlug($this->title, $this->subtitle),
                'attribution' => $this->attribution,
                'summary' => $this->summary,
                'content' => $this->content,
                'section' => $this->section,
                'tags' => $this->tags,
                'related_articles' => array_column($this->related_articles, 'id'),
                'visibility' => $this->visibility,
                'published_at' => $this->published_at,
                'meta_description' => $this->meta_description,
                'reading_time' => $this->reading_time,
                'user_id' => Auth::user()->id,
                'status' => 'review',
            ];

            // Procesar imagen si existe
            if ($this->image) {
                try {
                    $imagePath = $this->processImageUpload($this->image);
                    $articleData['image_path'] = '/storage/' . $imagePath;
                } catch (\Exception $e) {
                    // Mostrar error específico para debugging
                    session()->flash('error', 'Error al procesar la imagen: ' . $e->getMessage());
                    // También abrir la sección de imagen para mostrar el error
                    $this->openSections['image'] = true;
                    return; // No continuar si hay error en la imagen
                }
            }

            // Crear el artículo
            Article::create($articleData);

            // Resetear formulario
            $this->resetFormData();

            // Redireccionar al dashboard con mensaje de éxito
            session()->flash('message', 'Artículo creado exitosamente.');
            return redirect()->route('dashboard');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->handleValidationErrors($e);
            throw $e;
        }
    }

    private function handleValidationErrors($e)
    {
        $errorBags = $e->validator->getMessageBag()->getMessages();
        // Mapear errores a secciones
        $sectionMap = [
            'basic' => ['title', 'subtitle', 'attribution', 'summary'],
            'image' => ['image'],
            'content' => ['content'],
            'classification' => ['section', 'tags', 'tags.*', 'tagInput', 'related_articles', 'related_articles.*', 'relatedArticleSearch'],
            'publication' => ['visibility', 'published_at'],
            'seo' => ['meta_description', 'reading_time'],
            'metrics' => [],
        ];

        foreach ($sectionMap as $section => $fields) {
            foreach ($fields as $field) {
                foreach ($errorBags as $errorKey => $messages) {
                    // Soporta errores tipo tags.0, tags.1, etc.
                    if ($field === $errorKey || (str_ends_with($field, '.*') && str_starts_with($errorKey, rtrim($field, '.*')))) {
                        $this->openSections[$section] = true;
                    }
                }
            }
        }
    }

    public function store()
    {
        // Marcar que estamos esperando los datos del content editor
        $this->waitingForContentData = true;

        // Solicitar los datos del content editor
        $this->dispatch('requestContentData');

        // La validación continuará en receiveContentData()
    }

    public function saveDraft()
    {
        dd('Guardar borrador');
    }

    public function cancel()
    {
        $this->showCancelModal = true;
    }

    public function confirmCancel()
    {
        $this->resetFormData();

        // Enviar dispatch al content-editor para limpiar recursos de bloques de imagen/galería
        $this->dispatch('cleanupBlockResources');

        session()->flash('message', 'Creación de artículo cancelada');
        return redirect()->route('dashboard'); // Ajustar ruta según tu aplicación
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
    }

    private function resetFormData()
    {
        // Resetear información básica
        $this->title = '';
        $this->subtitle = '';
        $this->attribution = '';
        $this->summary = '';

        // Resetear media
        $this->image = null;

        // Resetear contenido
        $this->content = [];

        // Resetear clasificación
        $this->section = '';
        $this->tags = [];
        $this->tagInput = '';
        $this->related_articles = [];
        $this->relatedArticleSearch = '';

        // Resetear publicación
        $this->visibility = '';
        $this->published_at = '';

        // Resetear SEO
        $this->meta_description = '';
        $this->reading_time = '';

        // Resetear accordion state
        $this->openSections = [
            'basic' => true,
            'image' => false,
            'content' => false,
            'classification' => false,
            'publication' => false,
            'seo' => false,
            'metrics' => false,
        ];

        // Resetear flags
        $this->waitingForContentData = false;
        $this->contentErrors = [];

        // Limpiar errores
        $this->resetValidation();
        $this->resetErrorBag();
    }

    private function generateUniqueSlug($title, $subtitle = null)
    {
        // Crear slug base desde título o título + subtítulo
        $baseText = trim($title);
        if (!empty($subtitle)) {
            $baseText = trim($title . ' ' . $subtitle);
        }

        // Generar slug base
        $baseSlug = Str::slug($baseText);

        // Si el slug base está vacío, usar un slug genérico
        if (empty($baseSlug)) {
            $baseSlug = 'articulo';
        }

        // Verificar si el slug existe
        $slug = $baseSlug;
        $counter = 1;

        while (Article::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function processImageUpload($file)
    {
        // Generar nombre único para la imagen (siempre WebP)
        $timestamp = now()->format('Ymd_His');
        $randomString = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 8);
        $originalExtension = strtolower($file->getClientOriginalExtension());

        Log::info("Procesando imagen", [
            'extension' => $originalExtension,
            'size' => $file->getSize(),
            'mime' => $file->getMimeType()
        ]);

        // Validar extensión de entrada
        if (!in_array($originalExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            throw new \Exception('Formato de imagen no soportado. Use JPG, PNG, GIF o WebP.');
        }

        // Siempre generar archivo WebP
        $fileName = "revista_article_{$timestamp}_{$randomString}.webp";

        // Crear directorio si no existe
        $uploadPath = storage_path('app/public/images');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Ruta temporal del archivo subido
        $tempPath = $file->getRealPath();
        $finalPath = $uploadPath . '/' . $fileName;

        try {
            // Optimizar imagen y convertir siempre a WebP
            $this->optimizeImage($tempPath, $finalPath, $originalExtension);
            Log::info("Imagen procesada exitosamente", ['file' => $fileName]);
        } catch (\Exception $e) {
            Log::error("Error procesando imagen", [
                'extension' => $originalExtension,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }

        return 'images/' . $fileName;
    }

    private function optimizeImage($sourcePath, $destinationPath, $originalExtension)
    {
        // Obtener dimensiones originales
        $imageInfo = getimagesize($sourcePath);

        if ($imageInfo === false) {
            throw new \Exception('No se puede leer la información de la imagen');
        }

        list($width, $height) = $imageInfo;

        // Calcular nuevas dimensiones (máximo 1920px de ancho para imagen principal)
        $maxWidth = 1920;
        $maxHeight = 1440;

        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = intval($width * $ratio);
            $newHeight = intval($height * $ratio);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        // Crear imagen desde el archivo original
        try {
            $sourceImage = $this->createImageFromFile($sourcePath, $originalExtension);
        } catch (\Exception $e) {
            throw new \Exception('Error al cargar la imagen (' . $originalExtension . '): ' . $e->getMessage());
        }

        if (!$sourceImage) {
            throw new \Exception('No se pudo crear la imagen desde el archivo ' . $originalExtension);
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

        // Guardar siempre como WebP con alta calidad
        imagewebp($resizedImage, $destinationPath, 95); // Calidad 95% para portada

        // Liberar memoria
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        // Verificar tamaño del archivo y recomprimir si es necesario
        $this->adjustFileSize($destinationPath);
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

    private function adjustFileSize($path)
    {
        $maxSize = 300 * 1024; // 300KB en bytes para imagen de portada
        $minSize = 150 * 1024; // 150KB en bytes
        $currentSize = filesize($path);

        // Si el archivo está dentro del rango deseado, no hacer nada
        if ($currentSize >= $minSize && $currentSize <= $maxSize) {
            return;
        }

        // Si es muy grande, reducir calidad progresivamente (manteniendo alta calidad)
        if ($currentSize > $maxSize) {
            $image = imagecreatefromwebp($path);

            // Reducir calidad progresivamente con valores más altos
            $qualities = [90, 85, 80, 75, 70, 65];

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
        return view('livewire.create-article');
    }
}
