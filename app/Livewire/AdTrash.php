<?php

namespace App\Livewire;

use App\Models\Ad;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdTrash extends Component
{
    use WithPagination;

    public bool $showForceDeleteModal = false;
    public ?int $selectedAdId = null;
    public string $selectedAdName = '';

    public function paginationView(): string
    {
        return 'vendor.pagination.revista-livewire';
    }

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || !in_array($user->rol, ['editor_chief', 'administrator', 'moderator'], true)) {
            abort(404);
        }
    }

    public function restoreAd(int $id): void
    {
        $ad = Ad::onlyTrashed()->find($id);
        if (!$ad) {
            session()->flash('error', 'Anuncio no encontrado.');
            return;
        }
        $ad->restore();
        session()->flash('message', 'Anuncio restaurado correctamente.');
    }

    public function openForceDeleteModal(int $id): void
    {
        $ad = Ad::onlyTrashed()->find($id);
        if (!$ad) {
            return;
        }
        $this->selectedAdId = $id;
        $this->selectedAdName = $ad->name;
        $this->showForceDeleteModal = true;
    }

    public function closeForceDeleteModal(): void
    {
        $this->showForceDeleteModal = false;
        $this->selectedAdId = null;
        $this->selectedAdName = '';
    }

    public function confirmForceDelete(): void
    {
        if (!$this->selectedAdId) {
            $this->closeForceDeleteModal();
            return;
        }
        $ad = Ad::onlyTrashed()->find($this->selectedAdId);
        if (!$ad) {
            session()->flash('error', 'Anuncio no encontrado.');
            $this->closeForceDeleteModal();
            return;
        }
        $ad->forceDelete();
        session()->flash('message', 'Anuncio eliminado permanentemente.');
        $this->closeForceDeleteModal();
    }

    protected function getTrashedAds()
    {
        return Ad::onlyTrashed()
            ->orderByDesc('deleted_at')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.ad-trash', [
            'ads' => $this->getTrashedAds(),
        ]);
    }
}
