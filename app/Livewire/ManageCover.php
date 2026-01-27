<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\CoverArticle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ManageCover extends Component
{
    protected const ZONE_LIMITS = [
        'main' => 4,
        'mid' => 3,
        'latest' => 4,
    ];

    public string $name = '';

    public string $notes = '';

    public string $visibility = 'public';

    public ?string $scheduled_at = null;

    public ?string $ends_at = null;

    public bool $showSaveModal = false;

    public bool $showCancelModal = false;

    public bool $showDuplicateWarningModal = false;

    public array $duplicateArticles = [];

    /** Ids de artículos por zona; única fuente de verdad hasta guardar. No se persiste en BD hasta "Guardar borrador". */
    public array $mainArticleIds = [];

    public array $midArticleIds = [];

    public array $latestArticleIds = [];

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

    public function mount(): void
    {
        // Sin borrador en BD hasta que se pulse "Guardar borrador". Arrays y formulario vacíos.
    }

    public function openSaveModal(): void
    {
        // Usar solo el estado actual del formulario (o vacío); no cargar desde borrador.
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
     * Coloca un artículo en la posición de otro (reordenar o insertar desde el panel).
     * Si el artículo ya está en la zona, se reordena. Si no, se inserta en esa posición.
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

    /**
     * Reglas de validación para guardar (nombre único y fechas).
     * Hasta que se guarda no hay fila; siempre validar nombre único sin ignorar id.
     */
    protected function rulesForSave(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('cover_articles', 'name')->whereNull('deleted_at')],
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
     * Parsea fecha desde input datetime-local (YYYY-MM-DDTHH:mm) o null.
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

    /**
     * Crea un nuevo CoverArticle con status draft a partir de los arrays y el formulario.
     * Redirige a dashboard con flash de guardado exitoso.
     */
    public function saveDraft(): void
    {
        $this->validate($this->rulesForSave());

        $scheduled = $this->parseOptionalDatetime($this->scheduled_at);
        $ends = $this->parseOptionalDatetime($this->ends_at);

        CoverArticle::create([
            'name' => $this->name,
            'main_articles' => $this->mainArticleIds,
            'mid_articles' => $this->midArticleIds,
            'latest_articles' => $this->latestArticleIds,
            'status' => 'draft',
            'visibility' => $this->visibility,
            'notes' => $this->notes ?: null,
            'scheduled_at' => $scheduled,
            'ends_at' => $ends,
            'created_by' => Auth::id(),
            'edited_by' => Auth::id(),
        ]);

        $this->showSaveModal = false;
        $this->dispatch('saveModalToggled', isOpen: false);
        $this->dispatch('cover-saved');
        session()->flash('message', 'Borrador guardado correctamente.');
        $this->redirect(route('cover.index'), navigate: true);
    }

    /**
     * Verifica si hay artículos duplicados entre las diferentes zonas.
     */
    protected function checkForDuplicates(): array
    {
        $allIds = array_merge($this->mainArticleIds, $this->midArticleIds, $this->latestArticleIds);
        $counts = array_count_values($allIds);
        $duplicates = array_filter($counts, fn($count) => $count > 1);
        
        return array_keys($duplicates);
    }

    /**
     * Crea un nuevo CoverArticle con status pending_review a partir de los arrays y el formulario.
     */
    public function publish(): void
    {
        $this->validate($this->rulesForSave());

        // Verificar duplicados antes de publicar
        $duplicates = $this->checkForDuplicates();
        if (!empty($duplicates)) {
            $this->duplicateArticles = $duplicates;
            // Cerrar el modal de guardar primero
            $this->showSaveModal = false;
            $this->dispatch('saveModalToggled', isOpen: false);
            // Mostrar el modal de advertencia
            $this->showDuplicateWarningModal = true;
            $this->dispatch('duplicateWarningModalToggled', open: true);
            return;
        }

        $this->doPublish();
    }

    /**
     * Procede con la publicación ignorando los duplicados.
     */
    public function publishAnyway(): void
    {
        $this->showDuplicateWarningModal = false;
        $this->dispatch('duplicateWarningModalToggled', open: false);
        $this->doPublish();
    }

    /**
     * Cierra el modal de advertencia y permite modificar las secciones.
     */
    public function cancelPublish(): void
    {
        $this->showDuplicateWarningModal = false;
        $this->dispatch('duplicateWarningModalToggled', open: false);
        // Los datos del formulario se mantienen, el usuario puede seguir editando
    }

    /**
     * Ejecuta la publicación de la portada.
     */
    protected function doPublish(): void
    {
        $scheduled = $this->parseOptionalDatetime($this->scheduled_at);
        $ends = $this->parseOptionalDatetime($this->ends_at);

        CoverArticle::create([
            'name' => $this->name,
            'main_articles' => $this->mainArticleIds,
            'mid_articles' => $this->midArticleIds,
            'latest_articles' => $this->latestArticleIds,
            'status' => 'pending_review',
            'visibility' => $this->visibility,
            'notes' => $this->notes ?: null,
            'scheduled_at' => $scheduled,
            'ends_at' => $ends,
            'created_by' => Auth::id(),
            'edited_by' => Auth::id(),
        ]);

        $this->showSaveModal = false;
        $this->dispatch('saveModalToggled', isOpen: false);
        $this->dispatch('cover-published');
        session()->flash('message', 'Portada enviada a revisión.');
        $this->redirect(route('cover.index'), navigate: true);
    }

    public function render()
    {
        $mainArticles = $this->orderedArticlesFromIds($this->mainArticleIds);
        $midArticles = $this->orderedArticlesFromIds($this->midArticleIds);
        $latestArticles = $this->orderedArticlesFromIds($this->latestArticleIds);

        $hasContent = count($this->mainArticleIds) === self::ZONE_LIMITS['main']
            && count($this->midArticleIds) === self::ZONE_LIMITS['mid']
            && count($this->latestArticleIds) === self::ZONE_LIMITS['latest'];

        // Obtener nombres de artículos duplicados para mostrar en el modal
        $duplicateArticleNames = [];
        if (!empty($this->duplicateArticles)) {
            $articles = Article::whereIn('id', $this->duplicateArticles)->pluck('title', 'id')->toArray();
            $duplicateArticleNames = $articles;
        }

        return view('livewire.manage-cover', [
            'draft' => null,
            'mainArticles' => $mainArticles,
            'midArticles' => $midArticles,
            'latestArticles' => $latestArticles,
            'hasContent' => $hasContent,
            'duplicateArticleNames' => $duplicateArticleNames,
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
