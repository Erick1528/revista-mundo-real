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
        $userId = Auth::id();

        $query = CoverArticle::query()
            ->main()
            ->with(['creator', 'editor', 'activator', 'pendingVersions.editor'])
            ->where(function ($q) use ($userId) {
                $q->where('is_active', true)
                    ->orWhere('created_by', $userId);
            })
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
        $user = Auth::user();

        return view('livewire.cover-list', [
            'covers' => $this->getFilteredCovers(),
            'hasActiveFilters' => $this->search !== '' || $this->statusFilter !== '' || $this->visibilityFilter !== '' || $this->activeFilter !== '',
            'canActivate' => $user && CoverArticle::userCanActivate($user),
        ]);
    }
}
