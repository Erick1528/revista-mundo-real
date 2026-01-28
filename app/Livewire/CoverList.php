<?php

namespace App\Livewire;

use App\Models\CoverArticle;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CoverList extends Component
{
    public string $search = '';

    public string $statusFilter = '';

    public string $visibilityFilter = '';

    public string $activeFilter = '';

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->visibilityFilter = '';
        $this->activeFilter = '';
    }

    protected function getFilteredCovers()
    {
        $query = CoverArticle::query()
            ->main()
            ->with(['creator', 'editor', 'activator', 'pendingVersions.editor'])
            ->orderByDesc('is_active')
            ->latest('updated_at');

        if ($this->search !== '') {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->visibilityFilter !== '') {
            $query->where('visibility', $this->visibilityFilter);
        }

        if ($this->activeFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->activeFilter === 'inactive') {
            $query->where('is_active', false);
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.cover-list', [
            'covers' => $this->getFilteredCovers(),
            'hasActiveFilters' => $this->search !== '' || $this->statusFilter !== '' || $this->visibilityFilter !== '' || $this->activeFilter !== '',
        ]);
    }
}
