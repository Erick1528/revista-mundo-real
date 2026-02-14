<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\CoverArticle;
use App\Notifications\CoverNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditCover extends Component
{
    /**
     * Zone limits for article placement.
     */
    protected const ZONE_LIMITS = [
        'main' => 4,
        'mid' => 3,
        'latest' => 4,
    ];

    /**
     * The cover being edited.
     */
    public CoverArticle $cover;

    // Form fields
    public string $name = '';
    public string $notes = '';
    public string $visibility = 'public';
    public ?string $scheduled_at = null;
    public ?string $ends_at = null;

    // Modal states
    public bool $showSaveModal = false;
    public bool $showCancelModal = false;
    public bool $showDuplicateWarningModal = false;
    public bool $showActivateModal = false;
    public bool $showDeleteModal = false;

    public array $duplicateArticles = [];

    // Article IDs per zone - source of truth until saved
    public array $mainArticleIds = [];
    public array $midArticleIds = [];
    public array $latestArticleIds = [];

    /**
     * Validation messages (Spanish for user display).
     */
    protected array $messages = [
        'name.required' => 'El nombre de la portada es obligatorio.',
        'name.max' => 'El nombre no puede superar los 255 caracteres.',
        'name.unique' => 'Ya existe una portada con ese nombre. Elige otro para evitar confusiones.',
        'visibility.required' => 'Debes seleccionar la visibilidad.',
        'visibility.in' => 'La visibilidad no es válida.',
        'scheduled_at.date' => 'La fecha de inicio no es válida.',
        'ends_at.date' => 'La fecha de fin no es válida.',
        'ends_at.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
    ];

    /**
     * Mount the component with the cover to edit.
     */
    public function mount(CoverArticle $cover): void
    {
        $this->cover = $cover;

        // Load existing data into form fields
        $this->name = $cover->name ?? '';
        $this->notes = $cover->notes ?? '';
        $this->visibility = $cover->visibility ?? 'public';
        $this->scheduled_at = $cover->scheduled_at?->format('Y-m-d\TH:i');
        $this->ends_at = $cover->ends_at?->format('Y-m-d\TH:i');

        // Load article IDs
        $this->mainArticleIds = $cover->main_articles ?? [];
        $this->midArticleIds = $cover->mid_articles ?? [];
        $this->latestArticleIds = $cover->latest_articles ?? [];
    }

    // -------------------------------------------------------------------------
    // Modal Actions
    // -------------------------------------------------------------------------

    public function openSaveModal(): void
    {
        $this->showSaveModal = true;
        $this->dispatch('saveModalToggled', isOpen: true);
    }

    public function closeSaveModal(): void
    {
        $this->showSaveModal = false;
        $this->dispatch('saveModalToggled', isOpen: false);
    }

    public function openCancelModal(): void
    {
        $this->showCancelModal = true;
        $this->dispatch('cancelModalToggled', open: true);
    }

    public function closeCancelModal(): void
    {
        $this->showCancelModal = false;
        $this->dispatch('cancelModalToggled', open: false);
    }

    public function confirmCancel(): void
    {
        $this->showCancelModal = false;
        $this->dispatch('cancelModalToggled', open: false);
        session()->flash('message', 'Edición cancelada.');
        $this->redirect(route('cover.index'), navigate: true);
    }

    public function openDeleteModal(): void
    {
        if ($this->cover->isPendingVersion() || $this->cover->is_active) {
            return;
        }
        $user = Auth::user();
        if ($this->cover->created_by !== $user->id && ! CoverArticle::userCanActivate($user)) {
            session()->flash('error', 'No tienes permisos para eliminar esta portada.');
            return;
        }
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
    }

    public function confirmDeleteCover(): void
    {
        if ($this->cover->isPendingVersion() || $this->cover->is_active) {
            session()->flash('error', 'No se puede eliminar esta portada.');
            $this->closeDeleteModal();
            return;
        }
        $user = Auth::user();
        if ($this->cover->created_by !== $user->id && ! CoverArticle::userCanActivate($user)) {
            session()->flash('error', 'No tienes permisos para eliminar esta portada.');
            $this->closeDeleteModal();
            return;
        }
        $this->cover->delete();
        session()->flash('message', 'Portada eliminada correctamente.');
        $this->redirect(route('cover.index'), navigate: true);
    }

    public function openActivateModal(): void
    {
        $this->showActivateModal = true;
        $this->dispatch('activateModalToggled', open: true);
    }

    public function closeActivateModal(): void
    {
        $this->showActivateModal = false;
        $this->dispatch('activateModalToggled', open: false);
    }

    // -------------------------------------------------------------------------
    // Zone Management
    // -------------------------------------------------------------------------

    public function addToZone(string $zone, $articleId): void
    {
        if (! array_key_exists($zone, self::ZONE_LIMITS)) {
            return;
        }

        $articleId = (int) $articleId;
        if ($articleId <= 0) {
            return;
        }

        $ids = $this->getIdsForZone($zone);
        if (in_array($articleId, $ids, true)) {
            return;
        }

        $ids[] = $articleId;
        $ids = array_slice($ids, -self::ZONE_LIMITS[$zone]);
        $this->setIdsForZone($zone, array_values($ids));
    }

    public function removeFromZone(string $zone, $articleId): void
    {
        if (! array_key_exists($zone, self::ZONE_LIMITS)) {
            return;
        }

        $ids = $this->getIdsForZone($zone);
        $ids = array_values(array_filter($ids, fn ($id) => (int) $id !== (int) $articleId));
        $this->setIdsForZone($zone, $ids);
    }

    /**
     * Place an article at another article's position (reorder or insert from panel).
     */
    public function placeArticleAt(string $zone, $articleId, $targetArticleId): void
    {
        if (! array_key_exists($zone, self::ZONE_LIMITS)) {
            return;
        }

        $articleId = (int) $articleId;
        $targetArticleId = (int) $targetArticleId;
        if ($articleId <= 0 || $targetArticleId <= 0 || $articleId === $targetArticleId) {
            return;
        }

        $ids = $this->getIdsForZone($zone);
        $targetIndex = array_search($targetArticleId, $ids, true);
        if ($targetIndex === false) {
            return;
        }

        $articleIndex = array_search($articleId, $ids, true);
        $alreadyInZone = $articleIndex !== false;

        if ($alreadyInZone) {
            $ids[$articleIndex] = $targetArticleId;
            $ids[$targetIndex] = $articleId;
            $ids = array_values($ids);
        } else {
            array_splice($ids, $targetIndex, 0, [$articleId]);
            $ids = array_slice(array_values($ids), 0, self::ZONE_LIMITS[$zone]);
        }

        $this->setIdsForZone($zone, $ids);
    }

    protected function getIdsForZone(string $zone): array
    {
        return match ($zone) {
            'main' => $this->mainArticleIds,
            'mid' => $this->midArticleIds,
            'latest' => $this->latestArticleIds,
            default => [],
        };
    }

    protected function setIdsForZone(string $zone, array $ids): void
    {
        match ($zone) {
            'main' => $this->mainArticleIds = $ids,
            'mid' => $this->midArticleIds = $ids,
            'latest' => $this->latestArticleIds = $ids,
            default => null,
        };
    }

    // -------------------------------------------------------------------------
    // Validation
    // -------------------------------------------------------------------------

    /**
     * Validation rules for saving.
     * For pending versions, name is not required (inherits from parent).
     * For regular covers, name must be unique.
     */
    protected function rulesForSave(): array
    {
        // If editing an active cover, we create a pending version - name not strictly required
        // since it will show as "Cambios pendientes de [parent name]"
        if ($this->cover->is_active) {
            return [
                'name' => ['nullable', 'string', 'max:255'],
                'notes' => ['nullable', 'string'],
                'visibility' => ['required', 'in:public,private'],
                'scheduled_at' => ['nullable', 'date'],
                'ends_at' => ['nullable', 'date', 'after_or_equal:scheduled_at'],
            ];
        }

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cover_articles', 'name')->ignore($this->cover->id)->whereNull('deleted_at'),
            ],
            'notes' => ['nullable', 'string'],
            'visibility' => ['required', 'in:public,private'],
            'scheduled_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:scheduled_at'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'name' => 'nombre de la portada',
            'scheduled_at' => 'fecha de inicio',
            'ends_at' => 'fecha de fin',
        ];
    }

    /**
     * Parse datetime from input datetime-local (YYYY-MM-DDTHH:mm) or null.
     */
    protected function parseOptionalDatetime(?string $value): ?\Illuminate\Support\Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }
        try {
            return \Illuminate\Support\Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    // -------------------------------------------------------------------------
    // Save Actions
    // -------------------------------------------------------------------------

    /**
     * Whether the current user can edit this cover directly (update in place).
     * For non-active main covers: only the owner (creator) or editor_chief/moderator/administrator.
     * For active covers: no one (changes go as pending version). For pending versions: no one (edit in place of that row).
     */
    protected function userCanEditCoverDirectly(): bool
    {
        $user = Auth::user();
        if (! $user) {
            return false;
        }
        // Pending versions: can always save that row (it's your or someone's proposal)
        if ($this->cover->isPendingVersion()) {
            return true;
        }
        // Active main cover: never update in place, always create pending version
        if ($this->cover->is_active) {
            return false;
        }
        // Non-active main cover: only owner or users who can activate
        return (int) $this->cover->created_by === (int) $user->id
            || CoverArticle::userCanActivate($user);
    }

    /**
     * Save the cover as draft.
     * If cover is active, creates a pending version linked to the original.
     * If cover is not active, only the owner or editor_chief/moderator/administrator can update in place.
     */
    public function saveDraft(): void
    {
        $this->validate($this->rulesForSave());

        if (! $this->cover->is_active && ! $this->userCanEditCoverDirectly()) {
            session()->flash('error', 'Solo el creador de la portada o un editor con permisos puede guardar cambios en esta portada.');
            $this->showSaveModal = false;
            $this->dispatch('saveModalToggled', isOpen: false);

            return;
        }

        $scheduled = $this->parseOptionalDatetime($this->scheduled_at);
        $ends = $this->parseOptionalDatetime($this->ends_at);

        $data = [
            'main_articles' => $this->mainArticleIds,
            'mid_articles' => $this->midArticleIds,
            'latest_articles' => $this->latestArticleIds,
            'status' => 'draft',
            'visibility' => $this->visibility,
            'notes' => $this->notes ?: null,
            'scheduled_at' => $scheduled,
            'ends_at' => $ends,
        ];

        if ($this->cover->is_active) {
            // Always create a new pending version (multiple writers can each submit changes)
            $data['name'] = $this->name ?: $this->cover->name;
            $pending = $this->cover->createPendingVersion($data, Auth::user());
            CoverNotificationService::notifyEditorsPendingCoverCreated($pending);
            $message = 'Se crearon cambios pendientes. La portada activa no fue modificada.';
        } else {
            $data['name'] = $this->name;
            $data['edited_by'] = Auth::id();
            $this->cover->update($data);
            $message = 'Borrador actualizado correctamente.';
        }

        $this->showSaveModal = false;
        $this->dispatch('saveModalToggled', isOpen: false);
        session()->flash('message', $message);
        $this->redirect(route('cover.index'), navigate: true);
    }

    /**
     * Check for duplicate articles across zones.
     */
    protected function checkForDuplicates(): array
    {
        $allIds = array_merge($this->mainArticleIds, $this->midArticleIds, $this->latestArticleIds);
        $counts = array_count_values($allIds);
        $duplicates = array_filter($counts, fn ($count) => $count > 1);

        return array_keys($duplicates);
    }

    /**
     * Update the cover and send for review (pending_review status).
     */
    public function publish(): void
    {
        $this->validate($this->rulesForSave());

        // Check for duplicates before publishing
        $duplicates = $this->checkForDuplicates();
        if (! empty($duplicates)) {
            $this->duplicateArticles = $duplicates;
            $this->showSaveModal = false;
            $this->dispatch('saveModalToggled', isOpen: false);
            $this->showDuplicateWarningModal = true;
            $this->dispatch('duplicateWarningModalToggled', open: true);

            return;
        }

        $this->doPublish();
    }

    /**
     * Proceed with publication ignoring duplicates.
     */
    public function publishAnyway(): void
    {
        $this->showDuplicateWarningModal = false;
        $this->dispatch('duplicateWarningModalToggled', open: false);
        $this->doPublish();
    }

    /**
     * Close duplicate warning modal and allow section modification.
     */
    public function cancelPublish(): void
    {
        $this->showDuplicateWarningModal = false;
        $this->dispatch('duplicateWarningModalToggled', open: false);
    }

    /**
     * Execute the publication (send for review).
     * If cover is active, creates a pending version linked to the original.
     * If cover is not active, only the owner or editor_chief/moderator/administrator can update in place.
     */
    protected function doPublish(): void
    {
        if (! $this->cover->is_active && ! $this->userCanEditCoverDirectly()) {
            session()->flash('error', 'Solo el creador de la portada o un editor con permisos puede enviar a revisión esta portada.');
            $this->showSaveModal = false;
            $this->dispatch('saveModalToggled', isOpen: false);

            return;
        }

        $scheduled = $this->parseOptionalDatetime($this->scheduled_at);
        $ends = $this->parseOptionalDatetime($this->ends_at);

        $data = [
            'main_articles' => $this->mainArticleIds,
            'mid_articles' => $this->midArticleIds,
            'latest_articles' => $this->latestArticleIds,
            'status' => 'pending_review',
            'visibility' => $this->visibility,
            'notes' => $this->notes ?: null,
            'scheduled_at' => $scheduled,
            'ends_at' => $ends,
        ];

        if ($this->cover->is_active) {
            // Always create a new pending version (multiple writers can each submit changes)
            $data['name'] = $this->name ?: $this->cover->name;
            $pending = $this->cover->createPendingVersion($data, Auth::user());
            CoverNotificationService::notifyEditorsPendingCoverCreated($pending);
            $message = 'Cambios enviados a revisión. La portada activa no fue modificada.';
        } else {
            $data['name'] = $this->name;
            $data['edited_by'] = Auth::id();
            $this->cover->update($data);
            $message = 'Portada actualizada y enviada a revisión.';
        }

        $this->showSaveModal = false;
        $this->dispatch('saveModalToggled', isOpen: false);
        session()->flash('message', $message);
        $this->redirect(route('cover.index'), navigate: true);
    }

    // -------------------------------------------------------------------------
    // Activation
    // -------------------------------------------------------------------------

    /**
     * Activate this cover (and publish if needed).
     * Cannot activate an already active cover.
     */
    public function activate(): void
    {
        $user = Auth::user();

        if (! $user || ! CoverArticle::userCanActivate($user)) {
            session()->flash('error', 'No tienes permisos para activar portadas.');
            $this->closeActivateModal();

            return;
        }

        // Cannot activate an already active cover
        if ($this->cover->is_active) {
            session()->flash('error', 'Esta portada ya está activa.');
            $this->closeActivateModal();

            return;
        }

        // First save current changes
        $this->validate($this->rulesForSave());

        $scheduled = $this->parseOptionalDatetime($this->scheduled_at);
        $ends = $this->parseOptionalDatetime($this->ends_at);

        $this->cover->update([
            'name' => $this->name,
            'main_articles' => $this->mainArticleIds,
            'mid_articles' => $this->midArticleIds,
            'latest_articles' => $this->latestArticleIds,
            'visibility' => $this->visibility,
            'notes' => $this->notes ?: null,
            'scheduled_at' => $scheduled,
            'ends_at' => $ends,
        ]);

        // Now activate (this will also publish if needed)
        $result = $this->cover->activate($user);

        $this->closeActivateModal();

        if ($result) {
            session()->flash('message', 'Portada activada correctamente. Ahora es la portada visible en el sitio.');
        } else {
            session()->flash('error', 'No se pudo activar la portada.');
        }

        $this->redirect(route('cover.index'), navigate: true);
    }

    // -------------------------------------------------------------------------
    // Render
    // -------------------------------------------------------------------------

    public function render()
    {
        $mainArticles = $this->orderedArticlesFromIds($this->mainArticleIds);
        $midArticles = $this->orderedArticlesFromIds($this->midArticleIds);
        $latestArticles = $this->orderedArticlesFromIds($this->latestArticleIds);

        $hasContent = count($this->mainArticleIds) === self::ZONE_LIMITS['main']
            && count($this->midArticleIds) === self::ZONE_LIMITS['mid']
            && count($this->latestArticleIds) === self::ZONE_LIMITS['latest'];

        // Get duplicate article names for modal display
        $duplicateArticleNames = [];
        if (! empty($this->duplicateArticles)) {
            $duplicateArticleNames = Article::whereIn('id', $this->duplicateArticles)
                ->pluck('title', 'id')
                ->toArray();
        }

        // Check if user can activate
        $user = Auth::user();
        $canActivate = $user && CoverArticle::userCanActivate($user);
        $canEditDirectly = $this->userCanEditCoverDirectly();
        $canDeleteCover = ! $this->cover->isPendingVersion()
            && ! $this->cover->is_active
            && $user
            && ($this->cover->created_by === $user->id || $canActivate);

        return view('livewire.edit-cover', [
            'mainArticles' => $mainArticles,
            'midArticles' => $midArticles,
            'latestArticles' => $latestArticles,
            'hasContent' => $hasContent,
            'duplicateArticleNames' => $duplicateArticleNames,
            'canActivate' => $canActivate,
            'canEditDirectly' => $canEditDirectly,
            'canDeleteCover' => $canDeleteCover,
        ]);
    }

    protected function orderedArticlesFromIds(array $ids): \Illuminate\Support\Collection
    {
        if (empty($ids)) {
            return collect();
        }

        $items = Article::query()
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        return collect($ids)
            ->map(fn ($id) => $items->get($id))
            ->filter()
            ->values();
    }
}
