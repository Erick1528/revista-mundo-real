<?php

namespace App\Livewire;

use App\Models\Ad;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdList extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleteModal = false;
    public $selectedAdId = null;
    public $selectedAdName = '';

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

    public function getAdsProperty()
    {
        $query = Ad::query()->with('advertiser')->orderBy('name');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%');
            });
        }

        return $query->paginate(10);
    }

    public function openDeleteModal($id)
    {
        $ad = Ad::find($id);
        if (!$ad) {
            return;
        }
        $this->selectedAdId = $id;
        $this->selectedAdName = $ad->name;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->selectedAdId = null;
        $this->selectedAdName = '';
    }

    public function confirmDelete()
    {
        if (!$this->selectedAdId) {
            $this->closeDeleteModal();
            return;
        }
        $ad = Ad::find($this->selectedAdId);
        if (!$ad) {
            session()->flash('error', 'Anuncio no encontrado.');
            $this->closeDeleteModal();
            return;
        }
        $name = $ad->name;
        $ad->delete();
        session()->flash('message', "Anuncio \"{$name}\" movido a la papelera.");
        $this->closeDeleteModal();
    }

    public function render()
    {
        return view('livewire.ad-list', [
            'ads' => $this->getAdsProperty(),
            'hasActiveFilters' => (bool) $this->search,
        ]);
    }
}
