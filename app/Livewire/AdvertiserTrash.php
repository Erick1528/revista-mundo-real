<?php

namespace App\Livewire;

use App\Models\Advertiser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdvertiserTrash extends Component
{
    use WithPagination;
    use OptimizesAdvertiserLogo;

    public bool $showForceDeleteModal = false;
    public ?int $selectedAdvertiserId = null;
    public string $selectedAdvertiserName = '';

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

    public function restoreAdvertiser(int $id): void
    {
        $advertiser = Advertiser::onlyTrashed()->find($id);
        if (!$advertiser) {
            session()->flash('error', 'Anunciante no encontrado.');
            return;
        }
        $advertiser->restore();
        session()->flash('message', 'Anunciante restaurado correctamente.');
    }

    public function openForceDeleteModal(int $id): void
    {
        $advertiser = Advertiser::onlyTrashed()->find($id);
        if (!$advertiser) {
            return;
        }
        $this->selectedAdvertiserId = $id;
        $this->selectedAdvertiserName = $advertiser->name;
        $this->showForceDeleteModal = true;
    }

    public function closeForceDeleteModal(): void
    {
        $this->showForceDeleteModal = false;
        $this->selectedAdvertiserId = null;
        $this->selectedAdvertiserName = '';
    }

    public function confirmForceDelete(): void
    {
        if (!$this->selectedAdvertiserId) {
            $this->closeForceDeleteModal();
            return;
        }
        $advertiser = Advertiser::onlyTrashed()->find($this->selectedAdvertiserId);
        if (!$advertiser) {
            session()->flash('error', 'Anunciante no encontrado.');
            $this->closeForceDeleteModal();
            return;
        }
        $this->deleteLogoFromStorage($advertiser->logo_path);
        $advertiser->forceDelete();
        session()->flash('message', 'Anunciante eliminado permanentemente.');
        $this->closeForceDeleteModal();
    }

    protected function getTrashedAdvertisers()
    {
        return Advertiser::onlyTrashed()
            ->orderByDesc('deleted_at')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.advertiser-trash', [
            'advertisers' => $this->getTrashedAdvertisers(),
        ]);
    }
}
