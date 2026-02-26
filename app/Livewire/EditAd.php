<?php

namespace App\Livewire;

use App\Models\Ad;
use App\Models\Advertiser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditAd extends Component
{
    public Ad $ad;

    public $name = '';
    public $redirect_url = '';
    public $status = 'draft';
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
        'cancelEditAd' => 'cancel',
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
        'redirect_url.regex' => 'La URL de redirección debe usar HTTPS.',
        'status.required' => 'Debe seleccionar un estado.',
        'status.in' => 'El estado seleccionado no es válido.',
        'content.required' => 'El contenido del anuncio es obligatorio.',
        'content.min' => 'Debe agregar al menos un bloque de contenido.',
    ];

    public function mount(Ad $ad): void
    {
        $user = Auth::user();
        if (!$user || !in_array($user->rol, ['editor_chief', 'administrator', 'moderator'], true)) {
            abort(404);
        }
        $this->ad = $ad;
        $this->name = $ad->name;
        $this->redirect_url = $ad->redirect_url ?? '';
        $this->status = $ad->status;
        $this->advertiser_id = $ad->advertiser_id;
        $this->content = $ad->content ?? [];
        $this->dispatch('setContentBlocks', $this->content);
    }

    public function toggleSection(string $section): void
    {
        if (array_key_exists($section, $this->openSections)) {
            $this->openSections[$section] = !$this->openSections[$section];
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

    public function update(): void
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

        $this->validate($this->rules(), $this->messages);

        $slug = $this->ad->slug;
        if (trim($this->name) !== $this->ad->name) {
            $slug = generateUniqueSlugForAd($this->name, $this->ad->id);
        }

        $this->ad->update([
            'name' => $this->name,
            'slug' => $slug,
            'content' => $this->content,
            'redirect_url' => trim($this->redirect_url ?? '') !== '' ? $this->redirect_url : null,
            'status' => $this->status,
            'advertiser_id' => $this->advertiser_id ?: null,
        ]);

        session()->flash('message', 'Anuncio actualizado correctamente.');
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
        $this->dispatch('cleanupNewResources');
        session()->flash('message', 'Edición de anuncio cancelada.');
        $url = $this->cancelRedirectUrl;
        $this->cancelRedirectUrl = null;
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
        return view('livewire.edit-ad');
    }
}
