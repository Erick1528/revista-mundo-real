<?php

namespace App\Livewire;

use App\Models\Ad;
use App\Models\Advertiser;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateArticle extends Component
{
    use WithFileUploads;

    public $article;

    // Basic information
    public $title;

    public $subtitle;

    public $attribution;

    public $summary;

    // Media
    public $image;

    public $image_credits;

    public $image_alt_text;

    public $image_caption;

    // Content
    public $content = [];

    // Flag para controlar el flujo de validación
    public $waitingForContentData = false;

    /** 'draft' = Guardar cambios esperando contenido; 'update' = Actualizar esperando contenido */
    public $contentRequestPurpose = null;

    // Errores específicos de validación de contenido
    public $contentErrors = [];

    // Modal de confirmación para cancelar
    public $showCancelModal = false;

    /** URL a la que redirigir al confirmar cancelación (cuando se hace clic en otro enlace del nav). */
    public $cancelRedirectUrl = null;

    // Classification
    public $section;

    public $is_announcement = false;

    public $advertiser_id = null;

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

    public function updatedImage(): void
    {
        try {
            if (! $this->image) {
                $this->resetErrorBag('image');

                return;
            }
            $file = $this->image;
            if (! is_object($file) || ! method_exists($file, 'getClientOriginalExtension')) {
                $this->resetErrorBag('image');

                return;
            }
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (! in_array($extension, $allowedExtensions)) {
                $this->image = $this->article->image_path ?? null;
                $this->addError('image', 'Extensión no soportada. Solo se permiten: JPG, PNG, GIF, WebP');

                return;
            }
            $this->resetErrorBag('image');
        } catch (\Throwable $e) {
            $this->resetErrorBag('image');
            report($e);
        }
    }

    protected $listeners = [
        'contentDataResponse' => 'receiveContentData',
        'cancelEditArticle' => 'cancel',
    ];

    protected $rules = [
        'title' => 'required|string|max:255',
        'subtitle' => 'required|string|max:255',
        'attribution' => 'nullable|string|max:255',
        'summary' => 'nullable|string|max:500',
        'image' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:10240', // Max 10MB, nullable para update
        'image_credits' => 'nullable|string|max:255',
        'image_alt_text' => 'nullable|string|max:255',
        'image_caption' => 'nullable|string',
        'content' => 'required|array|min:1',
        'section' => 'required|in:destinations,inspiring_stories,social_events,health_wellness,gastronomy,living_culture',
        'is_announcement' => 'boolean',
        'advertiser_id' => 'nullable|exists:advertisers,id',
        'tags' => 'required|array|min:5|max:10',
        'tags.*' => 'string|max:50',
        'related_articles' => 'nullable|array|max:5',
        'related_articles.*' => 'integer|exists:articles,id',
        'visibility' => 'required|in:public,private',
        'published_at' => 'nullable|date',
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
        'image.image' => 'El archivo debe ser una imagen válida.',
        'image.mimes' => 'La imagen debe ser de tipo: jpeg, jpg, png, webp o gif.',
        'image.max' => 'La imagen no puede ser mayor a 10MB.',

        'image_credits.string' => 'Los créditos deben ser texto válido.',
        'image_credits.max' => 'Los créditos no pueden tener más de 255 caracteres.',

        'image_alt_text.string' => 'El alt text debe ser texto válido.',
        'image_alt_text.max' => 'El alt text no puede tener más de 255 caracteres.',

        'image_caption.string' => 'El caption debe ser texto válido.',

        // Content
        'content.required' => 'El contenido del artículo es obligatorio.',
        'content.array' => 'El contenido debe tener un formato válido.',
        'content.min' => 'Debe agregar al menos un bloque de contenido.',

        // Classification
        'section.required' => 'Debe seleccionar una sección para el artículo.',
        'section.in' => 'La sección seleccionada no es válida.',

        'tags.required' => 'Debe agregar al menos 5 tags.',
        'tags.array' => 'Los tags deben tener un formato válido.',
        'tags.min' => 'Debe agregar al menos 5 tags.',
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

        // SEO & Metadata
        'meta_description.string' => 'La meta descripción debe ser texto válido.',
        'meta_description.max' => 'La meta descripción no puede tener más de 160 caracteres.',

        'reading_time.integer' => 'El tiempo de lectura debe ser un número entero.',
        'reading_time.min' => 'El tiempo de lectura debe ser al menos 1 minuto.',
        'reading_time.max' => 'El tiempo de lectura no puede ser mayor a 60 minutos.',
    ];

    public function receiveContentData($data)
    {
        // Validar que los datos tengan bloques válidos
        $blocks = $data['blocks'] ?? [];

        // Si no es un array válido, usar array vacío
        if (! is_array($blocks)) {
            $blocks = [];
        }

        // Actualizar la propiedad content con los datos recibidos del editor
        // Incluso si está vacío, necesitamos actualizarlo para que la validación funcione
        $this->content = $blocks;

        if (! $this->waitingForContentData) {
            return;
        }

        $this->waitingForContentData = false;
        $purpose = $this->contentRequestPurpose;
        $this->contentRequestPurpose = null;

        if ($purpose === 'draft') {
            return $this->proceedWithSaveDraft();
        }

        return $this->proceedWithValidation();
    }

    public function mount(Article $article)
    {

        $user = Auth::user();

        // Validar permisos: solo el dueño del artículo, editor_chief, administrator y moderator pueden editar
        $isOwner = $article->user_id === $user->id;
        $isEditorChief = $user->rol === 'editor_chief';
        $isAdministrator = $user->rol === 'administrator';
        $isModerator = $user->rol === 'moderator';

        if (! $isOwner && ! $isEditorChief && ! $isAdministrator && ! $isModerator) {
            abort(403, 'Acceso denegado.');
        }

        $this->article = $article;

        $this->title = $article->title;
        $this->subtitle = $article->subtitle;
        $this->attribution = $article->attribution;
        $this->summary = $article->summary;
        $this->image = $article->image_path;
        $this->image_credits = $article->image_credits;
        $this->image_alt_text = $article->image_alt_text;
        $this->image_caption = $article->image_caption;

        // Pasar contenido a componente de editor de contenido
        $this->content = $article->content;
        $this->dispatch('setContentBlocks', $article->content);

        $this->section = $article->section;
        $this->is_announcement = (bool) $article->is_announcement;
        $this->advertiser_id = $article->advertiser_id;
        $this->tags = $article->tags;
        $this->related_articles = $article->related_articles;
        $this->visibility = $article->visibility;
        $this->published_at = $article->published_at;
        $this->meta_description = $article->meta_description;
        $this->reading_time = $article->reading_time;
    }

    public function toggleSection($section)
    {
        $this->openSections[$section] = ! $this->openSections[$section];
    }

    public function removeImage()
    {
        // Si hay una imagen original, restaurarla; si no, establecer en null
        $this->image = $this->article->image_path ?? null;
        $this->resetValidation(['image', 'image_credits', 'image_alt_text', 'image_caption']);
        $this->resetErrorBag(['image', 'image_credits', 'image_alt_text', 'image_caption']);
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

    public function setIsAnnouncement($value): void
    {
        $this->is_announcement = (bool) $value;
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
            'summary' => $articleSummary,
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
            ->where('visibility', 'public')
            ->where(function ($query) use ($matchingSections) {
                $query->where('title', 'like', '%'.$this->relatedArticleSearch.'%')
                    ->orWhere('subtitle', 'like', '%'.$this->relatedArticleSearch.'%')
                    ->orWhere('summary', 'like', '%'.$this->relatedArticleSearch.'%');

                // Si hay secciones que coinciden, también buscar por sección
                if (! empty($matchingSections)) {
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
                    'summary' => $article->summary ? substr($article->summary, 0, 80).'...' : null,
                    'published_at' => $article->published_at ? $article->published_at->format('d M Y') : null,
                ];
            })->toArray();
    }

    public function update()
    {
        // Marcar que estamos esperando los datos del content editor
        $this->waitingForContentData = true;

        // Solicitar los datos del content editor (enviar explícitamente al hijo ContentEditor)
        $this->dispatch('requestContentData')->to(ContentEditor::class);

        // La validación continuará en receiveContentData()
    }

    /**
     * Guarda los cambios manteniendo el artículo en borrador. Pide los datos al ContentEditor y continúa en receiveContentData -> proceedWithSaveDraft.
     */
    public function saveDraft()
    {
        try {
            $this->validate([
                'title' => 'required|string|max:255',
                'section' => 'required|in:destinations,inspiring_stories,social_events,health_wellness,gastronomy,living_culture',
            ], [
                'title.required' => 'El título es obligatorio para guardar.',
                'section.required' => 'Debe seleccionar una sección para guardar.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->handleValidationErrors($e);
            throw $e;
        }

        $this->contentRequestPurpose = 'draft';
        $this->waitingForContentData = true;
        $this->dispatch('requestContentData')->to(ContentEditor::class);
    }

    /**
     * Ejecuta el guardado del borrador tras recibir el contenido del ContentEditor.
     */
    private function proceedWithSaveDraft()
    {
        $titleChanged = $this->article->title !== $this->title;
        $subtitleChanged = $this->article->subtitle !== $this->subtitle;

        $articleData = [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'attribution' => $this->attribution,
            'summary' => $this->summary,
            'content' => $this->content,
            'section' => $this->section,
            'is_announcement' => (bool) $this->is_announcement,
            'advertiser_id' => $this->is_announcement ? $this->advertiser_id : null,
            'tags' => $this->tags,
            'related_articles' => array_column($this->related_articles, 'id'),
            'visibility' => 'private',
            'published_at' => null,
            'meta_description' => $this->meta_description,
            'reading_time' => $this->reading_time,
            'status' => 'draft',
            'image_credits' => $this->image_credits,
            'image_alt_text' => $this->image_alt_text,
            'image_caption' => $this->image_caption,
        ];

        if ($titleChanged || $subtitleChanged) {
            $articleData['slug'] = generateUniqueSlug($this->title, $this->subtitle, $this->article->id);
        }

        if ($this->image && is_object($this->image)) {
            try {
                $paths = $this->processImageUpload($this->image);
                foreach (['image_path', 'image_jpg_path'] as $pathField) {
                    $oldPath = $this->article->{$pathField};
                    if ($oldPath) {
                        $relativePath = ltrim($oldPath, '/storage/');
                        $fullPath = storage_path('app/public/'.$relativePath);
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    }
                }
                $articleData['image_path'] = '/storage/'.$paths['webp'];
                $articleData['image_jpg_path'] = '/storage/'.$paths['jpg'];
            } catch (\Throwable $e) {
                $this->openSections['image'] = true;
                $this->addError('image', $e->getMessage());

                return;
            }
        }

        $this->article->update($articleData);
        session()->flash('message', 'Cambios guardados. El artículo sigue en borrador.');

        return $this->redirect(route('dashboard'));
    }

    public function cancel($redirectUrl = null)
    {
        if (is_array($redirectUrl) && isset($redirectUrl['redirectUrl'])) {
            $redirectUrl = $redirectUrl['redirectUrl'];
        }
        $this->cancelRedirectUrl = $redirectUrl;
        $this->showCancelModal = true;
    }

    public function confirmCancel()
    {
        // Limpiar solo recursos nuevos agregados durante la edición
        $this->dispatch('cleanupNewResources');

        $url = $this->cancelRedirectUrl;
        $this->cancelRedirectUrl = null;
        session()->flash('message', 'Actualización de artículo cancelada');

        return $url ? $this->redirect($url) : $this->redirect(route('dashboard'));
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
    }

    private function validateBlocks()
    {
        $errors = [];

        for ($i = 0; $i < count($this->content); $i++) {
            $block = $this->content[$i];
            $blockNumber = $i + 1;

            // Verificar que el bloque tenga tipo
            if (! isset($block['type'])) {
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
                    if (empty($block['items']) || ! is_array($block['items'])) {
                        $errors[] = "Bloque #$blockNumber (Lista): Debe tener al menos un elemento";
                    } else {
                        $hasContent = false;
                        foreach ($block['items'] as $item) {
                            if (! empty(trim($item))) {
                                $hasContent = true;
                                break;
                            }
                        }
                        if (! $hasContent) {
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
                    if (empty($block['images']) || ! is_array($block['images']) || count($block['images']) === 0) {
                        $errors[] = "Bloque #$blockNumber (Galería): Debe tener al menos una imagen";
                    } else {
                        // Validar que las URLs de imágenes sean válidas
                        foreach ($block['images'] as $index => $imageUrl) {
                            if (empty(trim($imageUrl))) {
                                $errors[] = "Bloque #$blockNumber (Galería): Imagen #".($index + 1).' tiene una URL vacía';
                            }
                        }
                    }
                    break;

                case 'review':
                    if (empty($block['reviews']) || ! is_array($block['reviews'])) {
                        $errors[] = "Bloque #$blockNumber (Reseña): Debe tener al menos una reseña";
                    } else {
                        foreach ($block['reviews'] as $idx => $review) {
                            $num = $idx + 1;
                            if (empty(trim($review['name'] ?? ''))) {
                                $errors[] = "Bloque #$blockNumber (Reseña #$num): El campo 'Nombre' no puede estar vacío";
                            }
                            if (empty(trim($review['content'] ?? ''))) {
                                $errors[] = "Bloque #$blockNumber (Reseña #$num): El campo 'Contenido' no puede estar vacío";
                            }
                        }
                    }
                    break;

                case 'ad':
                    $adId = $block['ad_id'] ?? null;
                    if (empty($adId)) {
                        $errors[] = "Bloque #$blockNumber (Anuncio): Debe seleccionar un anuncio";
                    } elseif (! Ad::where('id', $adId)->where('status', 'published')->exists()) {
                        $errors[] = "Bloque #$blockNumber (Anuncio): El anuncio seleccionado no es válido o no está publicado";
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
            if (! empty($this->content)) {
                $this->resetErrorBag('content');
            }

            // 1) Validar bloques de contenido (no retornar: acumular errores)
            $blockErrors = $this->validateBlocks();
            if (! empty($blockErrors)) {
                $this->contentErrors = $blockErrors;
                $this->openSections['content'] = true;
                session()->flash('error', 'Hay errores en el contenido del artículo. Revisa los bloques vacíos.');
            }

            // 2) Actualizar exige imagen: existente o nueva (no retornar: acumular error)
            $hasExistingImage = ! empty(trim((string) ($this->article->image_path ?? '')));
            $hasNewImage = $this->image && is_object($this->image);
            if (! $hasExistingImage && ! $hasNewImage) {
                $this->openSections['image'] = true;
                $this->addError('image', 'La imagen principal es obligatoria para enviar a revisión.');
            }

            // 3) Validar el resto de reglas (título, tags, etc.) para mostrar todos los errores a la vez
            if (! empty($this->content)) {
                $rules = $this->rules;
                unset($rules['content']);
                if (! is_object($this->image)) {
                    unset($rules['image']);
                }
                $this->validate($rules);
            } else {
                $rules = $this->rules;
                if (! is_object($this->image)) {
                    unset($rules['image']);
                }
                $this->openSections['content'] = true;
                $this->validate($rules);
            }

            // Si hay errores de bloques o de imagen, no continuar (las reglas ya habrán lanzado o tenemos error manual)
            if (! empty($this->contentErrors) || $this->getErrorBag()->has('image')) {
                return;
            }

            // Verificar si el título o subtítulo cambiaron para actualizar el slug
            $titleChanged = $this->article->title !== $this->title;
            $subtitleChanged = $this->article->subtitle !== $this->subtitle;

            // Preparar datos del artículo
            $articleData = [
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'attribution' => $this->attribution,
                'summary' => $this->summary,
                'content' => $this->content,
                'section' => $this->section,
                'is_announcement' => (bool) $this->is_announcement,
                'advertiser_id' => $this->is_announcement ? $this->advertiser_id : null,
                'tags' => $this->tags,
                'related_articles' => array_column($this->related_articles, 'id'),
                'visibility' => $this->visibility,
                'published_at' => $this->published_at,
                'meta_description' => $this->meta_description,
                'reading_time' => $this->reading_time,
                'image_credits' => $this->image_credits,
                'image_alt_text' => $this->image_alt_text,
                'image_caption' => $this->image_caption,
            ];

            // Al actualizar (botón Actualizar) el artículo pasa a revisión
            $wasPublished = $this->article->status === 'published';
            $articleData['status'] = 'review';

            // Si el título o subtítulo cambiaron, generar nuevo slug
            if ($titleChanged || $subtitleChanged) {
                $articleData['slug'] = generateUniqueSlug($this->title, $this->subtitle, $this->article->id);
            }

            // Procesar imagen si existe y es nueva
            if ($this->image && is_object($this->image)) {
                try {
                    $paths = $this->processImageUpload($this->image);
                    // Eliminar imagen anterior (WebP y JPG) solo después de subir correctamente la nueva
                    foreach (['image_path', 'image_jpg_path'] as $pathField) {
                        $oldPath = $this->article->{$pathField};
                        if ($oldPath) {
                            $relativePath = ltrim($oldPath, '/storage/');
                            $fullPath = storage_path('app/public/'.$relativePath);
                            if (file_exists($fullPath)) {
                                unlink($fullPath);
                            }
                        }
                    }
                    $articleData['image_path'] = '/storage/'.$paths['webp'];
                    $articleData['image_jpg_path'] = '/storage/'.$paths['jpg'];
                } catch (\Exception $e) {
                    $this->openSections['image'] = true;
                    $this->addError('image', $e->getMessage());

                    return;
                }
            }

            // Limpiar recursos no utilizados del content editor ANTES de actualizar
            // Esto compara los bloques iniciales con los bloques finales y elimina recursos no utilizados
            // Pasamos los bloques finales como parámetro para asegurar que se usen los correctos
            $this->dispatch('cleanupUnusedResources', ['finalBlocks' => $this->content]);

            // Actualizar el artículo
            $this->article->update($articleData);
            $this->article->refresh();

            // Si estaba publicado y pasó a revisión, notificar a editores
            if ($wasPublished) {
                \App\Notifications\ArticleNotificationService::notifyEditorsArticleBackToReview($this->article);
            }

            // Redireccionar al dashboard con mensaje de éxito
            session()->flash('message', 'Artículo actualizado exitosamente.');

            return $this->redirect(route('dashboard'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($this->getErrorBag()->has('image')) {
                $e->validator->errors()->add('image', $this->getErrorBag()->first('image'));
            }
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
            'classification' => ['section', 'tags', 'tags.*', 'tagInput', 'related_articles', 'related_articles.*', 'relatedArticleSearch', 'is_announcement', 'advertiser_id'],
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

    private function processImageUpload($file): array
    {
        $timestamp = now()->format('Ymd_His');
        $randomString = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 8);
        $originalExtension = strtolower($file->getClientOriginalExtension());

        if (! in_array($originalExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            throw new \Exception('Formato de imagen no soportado. Use JPG, PNG, GIF o WebP.');
        }

        $baseName = "revista_article_{$timestamp}_{$randomString}";
        $fileNameWebp = $baseName.'.webp';
        $fileNameJpg = $baseName.'.jpg';

        $uploadPath = storage_path('app/public/images');
        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $tempPath = $file->getRealPath();
        $finalPathWebp = $uploadPath.'/'.$fileNameWebp;

        try {
            $this->optimizeImage($tempPath, $finalPathWebp, $originalExtension);
        } catch (\Exception $e) {
            throw $e;
        }

        return [
            'webp' => 'images/'.$fileNameWebp,
            'jpg' => 'images/'.$fileNameJpg,
        ];
    }

    private function optimizeImage($sourcePath, $destinationPath, $originalExtension)
    {
        // Obtener dimensiones originales (valida megapíxeles para no agotar memoria con GD)
        [$width, $height] = get_validated_image_dimensions($sourcePath);

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

        if (! $sourceImage) {
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

        // Guardar también JPG con el mismo nombre base
        $jpgPath = preg_replace('/\.webp$/i', '.jpg', $destinationPath);
        if ($originalExtension === 'png' || $originalExtension === 'gif') {
            $white = imagecreatetruecolor($newWidth, $newHeight);
            if ($white !== false) {
                $bg = imagecolorallocate($white, 255, 255, 255);
                imagefill($white, 0, 0, $bg);
                imagecopy($white, $resizedImage, 0, 0, 0, 0, $newWidth, $newHeight);
                imagejpeg($white, $jpgPath, 90);
                imagedestroy($white);
            } else {
                imagejpeg($resizedImage, $jpgPath, 90);
            }
        } else {
            imagejpeg($resizedImage, $jpgPath, 90);
        }

        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

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
                throw new \Exception('Formato de imagen no reconocido: '.$extension);
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

        // Si es muy grande, reducir calidad progresivamente
        if ($currentSize > $maxSize) {
            $image = imagecreatefromwebp($path);

            // Reducir calidad progresivamente
            $qualities = [90, 85, 80, 75, 70, 65];
            foreach ($qualities as $quality) {
                imagewebp($image, $path, $quality);
                $newSize = filesize($path);

                if ($newSize <= $maxSize && $newSize >= $minSize) {
                    imagedestroy($image);

                    return;
                }
            }

            imagedestroy($image);
        }
    }

    public function getAdvertisersProperty()
    {
        return Advertiser::query()->orderBy('name')->get();
    }

    public function getCanManageAdvertisersProperty(): bool
    {
        $user = Auth::user();

        return $user && in_array($user->rol, ['editor_chief', 'administrator', 'moderator'], true);
    }

    public function render()
    {
        return view('livewire.update-article');
    }
}
