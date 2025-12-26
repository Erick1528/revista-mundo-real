<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

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
    public $content;

    // Classification
    public $section;
    public $tags = [];
    public $related_articles = [];

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

        'related_articles' => 'nullable|array|max:3',
        'related_articles.*' => 'integer|exists:articles,id',

        'visibility' => 'required|in:public,private',

        'published_at' => 'nullable|date|after:now',

        'meta_description' => 'nullable|string|max:160',
        'reading_time' => 'nullable|integer|min:1|max:60',
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

    public function store()
    {
        dd('Guardar artículo');
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
