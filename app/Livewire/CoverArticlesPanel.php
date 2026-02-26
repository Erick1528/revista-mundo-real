<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CoverArticlesPanel extends Component
{
    /**
     * Track which accordion sections are open.
     */
    public $openSections = [
        'my' => true,
        'all' => true,
    ];

    // Search filters
    public $filterUser = '';
    public $filterSection = '';
    public $filterStatus = '';
    public $filterVisibility = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    /**
     * Clear all search filters.
     */
    public function clearFilters()
    {
        $this->filterUser = '';
        $this->filterSection = '';
        $this->filterStatus = '';
        $this->filterVisibility = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
    }

    /**
     * Apply filters to query.
     */
    protected function applyFilters($query, $includeUserFilter = true)
    {
        if ($includeUserFilter && $this->filterUser) {
            $query->where('user_id', $this->filterUser);
        }

        if ($this->filterSection) {
            $query->where('section', $this->filterSection);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterVisibility) {
            $query->where('visibility', $this->filterVisibility);
        }

        if ($this->filterDateFrom) {
            $query->whereDate('created_at', '>=', $this->filterDateFrom);
        }

        if ($this->filterDateTo) {
            $query->whereDate('created_at', '<=', $this->filterDateTo);
        }

        return $query;
    }

    /**
     * Get articles for the current user. solo los que sean status published y visibility public, lo mismo para el getAllArticles.
     */
    public function getMyArticles()
    {
        $query = Article::with('user')
            ->where('user_id', Auth::id())
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->orderBy('updated_at', 'desc')
            ->limit(50);

        // In "My articles", don't apply user filter since it's always the authenticated user
        return $this->applyFilters($query, false)->get();
    }

    public function getAllArticles()
    {
        $user = Auth::user();
        $query = Article::with('user')
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->orderBy('updated_at', 'desc')
            ->limit(50);

        if (! in_array($user->rol, ['editor_chief', 'moderator', 'administrator'], true)) {
            $query->where('user_id', $user->id);
        }

        return $this->applyFilters($query)->get();
    }

    public function toggleSection(string $section): void
    {
        if (array_key_exists($section, $this->openSections)) {
            $this->openSections[$section] = ! $this->openSections[$section];
        }
    }

    public function openAllSections(): void
    {
        $this->openSections['my'] = true;
        $this->openSections['all'] = true;
    }

    public function render()
    {
        $user = Auth::user();
        $canFilterByUser = in_array($user->rol, ['editor_chief', 'moderator', 'administrator'], true);
        $users = $canFilterByUser ? User::orderBy('name')->get() : collect();

        return view('livewire.cover-articles-panel', [
            'myArticles' => $this->getMyArticles(),
            'allArticles' => $this->getAllArticles(),
            'users' => $users,
            'canFilterByUser' => $canFilterByUser,
        ]);
    }
}
