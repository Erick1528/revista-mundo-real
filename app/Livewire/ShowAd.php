<?php

namespace App\Livewire;

use App\Models\Ad;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowAd extends Component
{
    public Ad $ad;

    /** Estado seleccionado para el formulario de cambio (solo para roles con permiso). */
    public string $newStatus = '';

    public function mount(Ad $ad): void
    {
        $this->ad = $ad;
        $this->newStatus = $ad->status;
    }

    public function canChangeStatus(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        return in_array($user->rol, ['editor_chief', 'moderator', 'administrator'], true);
    }

    public static function getAllowedStatuses(): array
    {
        return [
            'draft' => 'Borrador',
            'review' => 'En Revisión',
            'published' => 'Publicado',
            'denied' => 'Rechazado',
        ];
    }

    public function updateStatusFromSelect(): void
    {
        $this->updateStatus($this->newStatus);
    }

    public function updateStatus(string $newStatus)
    {
        $listRoute = route('ads.index');
        if (!$this->canChangeStatus()) {
            session()->flash('error', 'No tienes permiso para cambiar el estado del anuncio.');
            return $this->redirect($listRoute);
        }
        $allowed = array_keys(self::getAllowedStatuses());
        if (!in_array($newStatus, $allowed, true)) {
            session()->flash('error', 'No se pudo actualizar el estado.');
            return $this->redirect($listRoute);
        }
        try {
            $this->ad->update(['status' => $newStatus]);
            $this->ad->refresh();
            session()->flash('message', 'El estado del anuncio se actualizó correctamente.');
        } catch (\Throwable $e) {
            session()->flash('error', 'No se pudo actualizar el estado.');
        }
        return $this->redirect($listRoute);
    }

    public function render()
    {
        return view('livewire.show-ad');
    }
}
