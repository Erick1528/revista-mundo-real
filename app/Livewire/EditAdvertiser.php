<?php

namespace App\Livewire;

use App\Models\Advertiser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditAdvertiser extends Component
{
    use WithFileUploads;
    use OptimizesAdvertiserLogo;

    public Advertiser $advertiser;
    public $name;
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
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
    ];

    public function mount(Advertiser $advertiser)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->rol, ['editor_chief', 'administrator', 'moderator'], true)) {
            abort(404);
        }
        $this->advertiser = $advertiser;
        $this->name = $advertiser->name;
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
            session()->flash('error', 'Error: ' . $e->getMessage());
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
            session()->flash('error', 'Error al validar: ' . $e->getMessage());
            return;
        }

        $data = ['name' => $this->name];

        if ($this->logo) {
            $err = $this->validateLogoFile();
            if ($err !== null) {
                $this->logo = null;
                $this->addError('logo', $err);
                $this->openSections['data'] = true;
                return;
            }
            try {
                $newLogoPath = $this->processLogoUpload($this->logo);
                $this->deleteLogoFromStorage($this->advertiser->logo_path);
                $data['logo_path'] = $newLogoPath;
            } catch (\Throwable $e) {
                $this->logo = null;
                $this->addError('logo', 'Error al procesar el logo: ' . $e->getMessage());
                $this->openSections['data'] = true;
                return;
            }
        }

        try {
            $this->advertiser->update($data);
        } catch (\Throwable $e) {
            $this->logo = null;
            session()->flash('error', 'Error al actualizar el anunciante: ' . $e->getMessage());
            return;
        }

        session()->flash('message', 'Anunciante actualizado correctamente.');
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
        return view('livewire.edit-advertiser');
    }
}
