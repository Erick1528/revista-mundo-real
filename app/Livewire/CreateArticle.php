<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

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

    public function store()
    {
        try {
            $this->validate();
            return session()->flash('message', 'Artículo creado exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
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
            throw $e;
        }
    }

    public function saveDraft()
    {
        dd('Guardar borrador');
    }

    public function cancel()
    {
        dd('Cancelar creación');
    }

    public function render()
    {
        return view('livewire.create-article');
    }
}
