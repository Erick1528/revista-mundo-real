<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuggestedTopic extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'section',
        'resources',
        'status',
        'assigned_to',
        'requested_by',
        'taken_at',
        'requested_at',
        'completed_at',
        'created_by',
        'updated_by',
        'notes',
    ];

    protected $casts = [
        'resources' => 'array',
        'taken_at' => 'datetime',
        'requested_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /** Solicitudes pendientes (varios usuarios pueden solicitar el mismo tema). */
    public function topicRequests(): HasMany
    {
        return $this->hasMany(SuggestedTopicRequest::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeTaken($query)
    {
        return $query->where('status', 'taken');
    }

    public function scopeRequested($query)
    {
        return $query->where('status', 'requested');
    }

    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeRequestedBy($query, $userId)
    {
        return $query->where('requested_by', $userId);
    }

    // -------------------------------------------------------------------------
    // Accessors - Display Names (Spanish for views)
    // -------------------------------------------------------------------------

    public function getStatusNameAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Disponible',
            'taken' => 'Tomado',
            'requested' => 'Solicitado',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default => $this->status ?? '—',
        };
    }

    public function getSectionNameAttribute(): string
    {
        $sections = [
            'destinations' => 'Destinos',
            'inspiring_stories' => 'Historias que Inspiran',
            'social_events' => 'Eventos Sociales',
            'health_wellness' => 'Salud y Bienestar',
            'gastronomy' => 'Gastronomía con Identidad',
            'living_culture' => 'Cultura Viva'
        ];

        return $sections[$this->section] ?? $this->section;
    }

    // -------------------------------------------------------------------------
    // Business Logic Methods
    // -------------------------------------------------------------------------

    /**
     * Tomar un tema disponible.
     * 
     * @param User $user Usuario que toma el tema
     * @return bool True si se tomó exitosamente, false si no está disponible
     */
    public function takeTopic(User $user): bool
    {
        if ($this->status !== 'available') {
            return false;
        }

        return $this->update([
            'status' => 'taken',
            'assigned_to' => $user->id,
            'taken_at' => now(),
            'updated_by' => $user->id,
        ]);
    }

    /**
     * Solicitar un tema tomado por otro usuario (varios usuarios pueden solicitar).
     *
     * @param User $user Usuario que solicita el tema
     * @return bool True si se solicitó exitosamente
     */
    public function requestTopic(User $user): bool
    {
        if ($this->status !== 'taken') {
            return false;
        }

        if ($this->assigned_to === $user->id) {
            return false;
        }

        // Evitar duplicados: si ya solicitó, no crear otra fila
        if ($this->topicRequests()->where('user_id', $user->id)->exists()) {
            return true;
        }

        $this->topicRequests()->create(['user_id' => $user->id]);
        $this->update(['updated_by' => $user->id]);

        return true;
    }

    /**
     * Comprueba si hay al menos una solicitud pendiente.
     */
    public function hasPendingRequests(): bool
    {
        return $this->topicRequests()->exists();
    }

    /**
     * Liberar un tema. Si hay solicitudes, se asigna al primero que solicitó.
     * 
     * @param User $user Usuario que libera (debe ser el asignado o admin/editor)
     * @return bool True si se liberó exitosamente
     */
    public function releaseTopic(User $user): bool
    {
        // Solo el usuario asignado o admin/editor puede liberar
        if ($this->assigned_to !== $user->id && !in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
            return false;
        }

        // Si hay solicitudes en la nueva tabla, asignar al primero
        $firstRequest = $this->topicRequests()->orderBy('created_at')->first();
        if ($firstRequest) {
            $this->topicRequests()->delete();
            return $this->update([
                'status' => 'taken',
                'assigned_to' => $firstRequest->user_id,
                'taken_at' => now(),
                'requested_by' => null,
                'requested_at' => null,
                'updated_by' => $user->id,
            ]);
        }

        // Compatibilidad: si hay requested_by en la tabla antigua
        if ($this->status === 'requested' && $this->requested_by) {
            return $this->update([
                'status' => 'taken',
                'assigned_to' => $this->requested_by,
                'taken_at' => now(),
                'requested_by' => null,
                'requested_at' => null,
                'updated_by' => $user->id,
            ]);
        }

        // Si no hay solicitudes, volver a disponible
        return $this->update([
            'status' => 'available',
            'assigned_to' => null,
            'taken_at' => null,
            'requested_by' => null,
            'requested_at' => null,
            'updated_by' => $user->id,
        ]);
    }

    /**
     * Rechazar la solicitud de un usuario (el que tiene el tema o admin puede rechazar).
     *
     * @param User $user Usuario que rechaza (asignado actual o admin/editor)
     * @param int $userIdToReject ID del usuario cuya solicitud se rechaza
     * @return bool True si se rechazó exitosamente
     */
    public function rejectRequest(User $user, int $userIdToReject): bool
    {
        $canReject = $this->assigned_to === $user->id
            || in_array($user->rol, ['editor_chief', 'moderator', 'administrator']);

        if (!$canReject) {
            return false;
        }

        $deleted = $this->topicRequests()->where('user_id', $userIdToReject)->delete();
        if ($deleted) {
            $this->update(['updated_by' => $user->id]);
        }

        return $deleted > 0;
    }

    /**
     * Asignar el tema a un solicitante (quien tiene el tema o admin elige a quién darle).
     *
     * @param User $user Usuario que asigna (asignado actual o admin/editor)
     * @param int $userIdToAssign ID del usuario al que se le asigna el tema
     * @return bool True si se asignó exitosamente
     */
    public function assignToRequester(User $user, int $userIdToAssign): bool
    {
        $canAssign = $this->assigned_to === $user->id
            || in_array($user->rol, ['editor_chief', 'moderator', 'administrator']);

        if (!$canAssign) {
            return false;
        }

        if (!$this->topicRequests()->where('user_id', $userIdToAssign)->exists()) {
            return false;
        }

        $this->topicRequests()->delete();
        return $this->update([
            'status' => 'taken',
            'assigned_to' => $userIdToAssign,
            'taken_at' => now(),
            'requested_by' => null,
            'requested_at' => null,
            'updated_by' => $user->id,
        ]);
    }

    /**
     * Marcar tema como completado.
     * 
     * @param User $user Usuario que completa (debe ser el asignado o admin/editor)
     * @return bool True si se completó exitosamente
     */
    public function completeTopic(User $user): bool
    {
        // Solo el usuario asignado o admin/editor puede completar
        if ($this->assigned_to !== $user->id && !in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
            return false;
        }

        return $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'updated_by' => $user->id,
        ]);
    }

    /**
     * Cancelar un tema.
     * 
     * @param User $user Usuario que cancela (debe ser admin/editor o creador)
     * @return bool True si se canceló exitosamente
     */
    public function cancelTopic(User $user): bool
    {
        // Solo admin/editor o el creador puede cancelar
        if ($this->created_by !== $user->id && !in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
            return false;
        }

        return $this->update([
            'status' => 'cancelled',
            'updated_by' => $user->id,
        ]);
    }
}
