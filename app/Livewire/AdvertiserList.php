<?php

namespace App\Livewire;

use App\Models\Advertiser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdvertiserList extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleteModal = false;
    public $selectedAdvertiserId = null;
    public $selectedAdvertiserName = '';

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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function getAdvertisersProperty()
    {
        $query = Advertiser::query()->orderBy('name');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $query->paginate(10);
    }

    public function openDeleteModal($id)
    {
        $advertiser = Advertiser::find($id);
        if (!$advertiser) {
            return;
        }
        $this->selectedAdvertiserId = $id;
        $this->selectedAdvertiserName = $advertiser->name;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->selectedAdvertiserId = null;
        $this->selectedAdvertiserName = '';
    }

    public function confirmDelete()
    {
        if (!$this->selectedAdvertiserId) {
            $this->closeDeleteModal();
            return;
        }
        $advertiser = Advertiser::find($this->selectedAdvertiserId);
        if (!$advertiser) {
            session()->flash('error', 'Anunciante no encontrado.');
            $this->closeDeleteModal();
            return;
        }
        $name = $advertiser->name;
        $advertiser->delete();
        session()->flash('message', "Anunciante \"{$name}\" eliminado correctamente.");
        $this->closeDeleteModal();
    }

    public function render()
    {
        return view('livewire.advertiser-list', [
            'advertisers' => $this->getAdvertisersProperty(),
            'hasActiveFilters' => (bool) $this->search,
        ]);
    }
}
