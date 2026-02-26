<?php

namespace App\Livewire;

use App\Models\Advertiser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateAdvertiser extends Component
{
    use WithFileUploads;
    use OptimizesAdvertiserLogo;

    public $name = '';
    public $logo = null;

    /** Acordeones: mismo patrón que crear artículo */
    public $openSections = [
        'data' => true,
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    protected $messages = [
        'name.required' => 'El nombre de la empresa o anunciante es obligatorio.',
        'name.string' => 'El nombre debe ser texto válido.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
    ];

    public function mount()
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
        $errorKeys = array_keys($e->validator->getMessageBag()->getMessages());
        $dataFields = ['name', 'logo'];
        foreach ($dataFields as $field) {
            if (in_array($field, $errorKeys, true)) {
                $this->openSections['data'] = true;
                return;
            }
        }
    }

    public function save()
    {
        try {
            return $this->performSave();
        } catch (\Throwable $e) {
            $this->logo = null;
            $this->addError('name', $e->getMessage());
            $this->openSections['data'] = true;
            return;
        }
    }

    private function performSave()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->handleValidationErrors($e);
            throw $e;
        } catch (\Throwable $e) {
            $this->logo = null;
            $this->addError('name', $e->getMessage());
            $this->openSections['data'] = true;
            return;
        }

        $logoPath = null;
        if ($this->logo) {
            $err = $this->validateLogoFile();
            if ($err !== null) {
                $this->logo = null;
                $this->addError('logo', $err);
                $this->openSections['data'] = true;
                return;
            }
            try {
                $logoPath = $this->processLogoUpload($this->logo);
            } catch (\Throwable $e) {
                $this->logo = null;
                $this->addError('logo', $e->getMessage());
                $this->openSections['data'] = true;
                return;
            }
        }

        try {
            Advertiser::create([
                'name' => $this->name,
                'logo_path' => $logoPath,
            ]);
        } catch (\Throwable $e) {
            $this->logo = null;
            $this->addError('name', $e->getMessage());
            $this->openSections['data'] = true;
            return;
        }

        session()->flash('message', 'Anunciante creado correctamente.');
        return $this->redirect(route('advertisers.index'));
    }

    /** Valida el archivo de logo sin abrirlo (solo extensión y tamaño). Devuelve mensaje de error o null. */
    private function validateLogoFile(): ?string
    {
        if (!$this->logo || !is_object($this->logo)) {
            return null;
        }
        $ext = strtolower($this->logo->getClientOriginalExtension() ?? '');
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            return 'El logo debe ser JPG, PNG, WebP o GIF.';
        }
        $maxKb = 10240; // 10 MB
        if ($this->logo->getSize() > $maxKb * 1024) {
            return 'La imagen no puede ser mayor a 10 MB.';
        }
        return null;
    }

    public function getLogoPreviewUrlProperty(): ?string
    {
        if (!$this->logo || !is_object($this->logo) || !method_exists($this->logo, 'temporaryUrl')) {
            return null;
        }
        try {
            return $this->logo->temporaryUrl();
        } catch (\Throwable) {
            return null;
        }
    }

    public function render()
    {
        return view('livewire.create-advertiser');
    }
}
