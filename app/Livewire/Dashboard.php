<?php

namespace App\Livewire;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $sectionFilter = '';
    public $visibilityFilter = '';
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingSectionFilter()
    {
        $this->resetPage();
    }

    public function updatingVisibilityFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->statusFilter = '';
        $this->sectionFilter = '';
        $this->visibilityFilter = '';
        $this->search = '';
        $this->resetPage();
    }

    public function showInDevelopment($action)
    {
        $this->dispatch('openDevelopModal', action: $action);
    }

    public function getFilteredArticles()
    {
        $query = Article::with('user')
            ->where('user_id', Auth::user()->id)
            ->orderBy('updated_at', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('subtitle', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->sectionFilter) {
            $query->where('section', $this->sectionFilter);
        }

        if ($this->visibilityFilter) {
            $query->where('visibility', $this->visibilityFilter);
        }

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'articles' => $this->getFilteredArticles()
        ]);
    }
}
