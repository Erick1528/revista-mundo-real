<?php

namespace App\Livewire;

use App\Models\Ad;
use App\Models\Advertiser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateAd extends Component
{
    public $name = '';
    public $redirect_url = '';
    public $status = 'review';
    public $advertiser_id = null;
    public $content = [];

    public $waitingForContentData = false;
    public $contentErrors = [];
    public $showCancelModal = false;
    public $cancelRedirectUrl = null;

    /** Acordeones: mismo patrón que crear artículo */
    public $openSections = [
        'data' => true,
        'content' => false,
    ];

    protected $listeners = [
        'contentDataResponse' => 'receiveContentData',
        'cancelCreateAd' => 'cancel',
    ];

    protected function rules(): array
    {
        $redirectRules = ['nullable', 'string', 'url', 'max:2048'];
        if (!empty(trim($this->redirect_url ?? ''))) {
            $redirectRules[] = 'regex:/^https:\/\//i';
        }
        return [
            'name' => 'required|string|max:255',
            'redirect_url' => $redirectRules,
            'status' => 'required|in:draft,review,published,denied',
            'advertiser_id' => 'nullable|exists:advertisers,id',
            'content' => 'required|array|min:1',
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre del anuncio es obligatorio.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'redirect_url.url' => 'La URL de redirección no es válida.',
        'redirect_url.max' => 'La URL no puede tener más de 2048 caracteres.',
        'status.required' => 'Debe seleccionar un estado.',
        'status.in' => 'El estado seleccionado no es válido.',
        'content.required' => 'El contenido del anuncio es obligatorio.',
        'content.min' => 'Debe agregar al menos un bloque de contenido.',
    ];

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || !in_array($user->rol, ['editor_chief', 'administrator', 'moderator'], true)) {
            abort(404);
        }
    }

    public function toggleSection(string $section): void
    {
        if (array_key_exists($section, $this->openSections)) {
            $this->openSections[$section] = !$this->openSections[$section];
        }
    }

    private function handleValidationErrors(\Illuminate\Validation\ValidationException $e): void
    {
        $errorBags = $e->validator->getMessageBag()->getMessages();
        $sectionMap = [
            'data' => ['name', 'redirect_url', 'advertiser_id'],
            'content' => ['content'],
        ];
        foreach ($sectionMap as $section => $fields) {
            foreach ($fields as $field) {
                foreach (array_keys($errorBags) as $errorKey) {
                    if ($field === $errorKey || (str_ends_with($field, '.*') && str_starts_with($errorKey, rtrim($field, '.*')))) {
                        $this->openSections[$section] = true;
                        break 2;
                    }
                }
            }
        }
    }

    public function receiveContentData($data): void
    {
        $this->content = $data['blocks'] ?? [];
        if ($this->waitingForContentData) {
            $this->waitingForContentData = false;
            $this->proceedWithValidation();
        }
    }

    public function store(): void
    {
        $this->waitingForContentData = true;
        $this->dispatch('requestContentData');
    }

    private function proceedWithValidation(): void
    {
        $this->contentErrors = [];
        if (!empty($this->content)) {
            $this->resetErrorBag('content');
        }

        try {
            $this->validate($this->rules(), array_merge($this->messages, [
                'redirect_url.regex' => 'La URL de redirección debe usar HTTPS.',
            ]));
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->handleValidationErrors($e);
            throw $e;
        }

        $slug = generateUniqueSlugForAd($this->name);
        Ad::create([
            'name' => $this->name,
            'slug' => $slug,
            'content' => $this->content,
            'redirect_url' => trim($this->redirect_url ?? '') !== '' ? $this->redirect_url : null,
            'status' => $this->status,
            'visibility' => 'public',
            'user_id' => Auth::id(),
            'advertiser_id' => $this->advertiser_id ?: null,
        ]);

        session()->flash('message', 'Anuncio creado correctamente.');
        $this->redirect(route('ads.index'));
    }

    public function cancel($redirectUrl = null): void
    {
        if (is_array($redirectUrl) && isset($redirectUrl['redirectUrl'])) {
            $redirectUrl = $redirectUrl['redirectUrl'];
        }
        $this->cancelRedirectUrl = $redirectUrl;
        $this->showCancelModal = true;
    }

    public function confirmCancel()
    {
        $url = $this->cancelRedirectUrl;
        $this->reset([
            'name', 'redirect_url', 'status', 'advertiser_id', 'content',
            'waitingForContentData', 'contentErrors', 'cancelRedirectUrl',
        ]);
        $this->dispatch('cleanupBlockResources');
        session()->flash('message', 'Creación de anuncio cancelada.');
        $this->showCancelModal = false;
        return $url ? $this->redirect($url) : $this->redirect(route('ads.index'));
    }

    public function closeCancelModal(): void
    {
        $this->showCancelModal = false;
    }

    public function getAdvertisersProperty()
    {
        return Advertiser::query()->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.create-ad');
    }
}
