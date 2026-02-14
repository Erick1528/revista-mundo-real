<?php

namespace App\Livewire;

use App\Models\SuggestedTopic;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditSuggestedTopic extends Component
{
    public $topic;

    // Basic information
    public $title;
    public $section;
    public $description;

    // Resources (ContentEditor blocks)
    public $resources = [];


    // Accordion state
    public $openSections = [
        'basic' => true,
        'resources' => false,
    ];

    // Flag para controlar el flujo de validación
    public $waitingForContentData = false;

    // Errores específicos de validación de contenido
    public $contentErrors = [];

    // Modal de confirmación para cancelar
    public $showCancelModal = false;

    /** URL a la que redirigir al confirmar cancelación (cuando se hace clic en otro enlace del nav). */
    public $cancelRedirectUrl = null;

    // Modales de confirmación para acciones
    public $showDeleteModal = false;

    // Listeners para eventos
    protected $listeners = [
        'contentDataResponse' => 'receiveContentData',
        'cancelEditTopic' => 'cancel',
    ];

    protected $rules = [
        'title' => 'required|string|max:255',
        'section' => 'required|in:destinations,inspiring_stories,social_events,health_wellness,gastronomy,living_culture',
        'description' => 'nullable|string|max:1000',
        'resources' => 'nullable|array',
    ];

    protected $messages = [
        'title.required' => 'El título del tema es obligatorio.',
        'title.string' => 'El título debe ser texto válido.',
        'title.max' => 'El título no puede tener más de 255 caracteres.',
        'section.required' => 'Debe seleccionar una sección para el tema.',
        'section.in' => 'La sección seleccionada no es válida.',
        'description.string' => 'La descripción debe ser texto válido.',
        'description.max' => 'La descripción no puede tener más de 1000 caracteres.',
        'resources.array' => 'Los recursos deben tener un formato válido.',
    ];

    public function mount(SuggestedTopic $topic)
    {
        $user = Auth::user();

        // Validar permisos: creador, usuario asignado (si está taken/requested), o admin/editor
        $isCreator = $topic->created_by === $user->id;
        $isAssigned = $topic->assigned_to === $user->id && in_array($topic->status, ['taken', 'requested']);
        $isAdmin = in_array($user->rol, ['editor_chief', 'moderator', 'administrator']);

        if (!$isCreator && !$isAssigned && !$isAdmin) {
            abort(403, 'No tienes permisos para editar este tema.');
        }

        $this->topic = $topic;
        
        // Cargar relaciones necesarias
        $this->topic->load(['creator', 'assignedUser', 'requester']);

        $this->title = $topic->title;
        $this->section = $topic->section;
        $this->description = $topic->description;

        // Cargar recursos en ContentEditor
        $this->resources = $topic->resources ?? [];
        if (!empty($this->resources)) {
            $this->dispatch('setContentBlocks', $this->resources);
        }
    }

    public function toggleSection($section)
    {
        $this->openSections[$section] = !$this->openSections[$section];
    }

    public function receiveContentData($data)
    {
        // Actualizar la propiedad resources con los datos recibidos del editor
        $this->resources = $data['blocks'] ?? [];

        // Si estábamos esperando los datos para proceder con la validación
        if ($this->waitingForContentData) {
            $this->waitingForContentData = false;
            $this->proceedWithUpdate();
        }
    }

    private function validateBlocks()
    {
        $errors = [];

        if (empty($this->resources) || !is_array($this->resources)) {
            return $errors; // Los recursos son opcionales
        }

        for ($i = 0; $i < count($this->resources); $i++) {
            $block = $this->resources[$i];
            $blockNumber = $i + 1;

            if (!isset($block['type'])) {
                $errors[] = "Bloque #$blockNumber: Tipo de bloque no válido";
                continue;
            }

            // Validar contenido según el tipo de bloque (similar a CreateSuggestedTopic)
            switch ($block['type']) {
                case 'paragraph':
                case 'heading':
                case 'quote':
                    if (empty(trim($block['content'] ?? ''))) {
                        $errors[] = "Bloque #$blockNumber (" . ucfirst($block['type']) . "): No puede estar vacío";
                    }
                    break;

                case 'list':
                    if (empty($block['items']) || !is_array($block['items'])) {
                        $errors[] = "Bloque #$blockNumber (Lista): Debe tener al menos un elemento";
                    } else {
                        $hasContent = false;
                        foreach ($block['items'] as $item) {
                            if (!empty(trim($item))) {
                                $hasContent = true;
                                break;
                            }
                        }
                        if (!$hasContent) {
                            $errors[] = "Bloque #$blockNumber (Lista): Debe tener al menos un elemento con contenido";
                        }
                    }
                    break;

                case 'image':
                    if (empty($block['url'] ?? '')) {
                        $errors[] = "Bloque #$blockNumber (Imagen): Debe tener una URL válida";
                    }
                    break;

                case 'video':
                    if (empty($block['url'] ?? '')) {
                        $errors[] = "Bloque #$blockNumber (Video): Debe tener una URL válida";
                    }
                    break;

                case 'gallery':
                    if (empty($block['images']) || !is_array($block['images']) || count($block['images']) === 0) {
                        $errors[] = "Bloque #$blockNumber (Galería): Debe tener al menos una imagen";
                    }
                    break;

                case 'review':
                    if (empty($block['reviews']) || !is_array($block['reviews'])) {
                        $errors[] = "Bloque #$blockNumber (Reseña): Debe tener al menos una reseña";
                    } else {
                        foreach ($block['reviews'] as $idx => $review) {
                            $num = $idx + 1;
                            if (empty(trim($review['name'] ?? ''))) {
                                $errors[] = "Bloque #$blockNumber (Reseña #$num): El campo 'Nombre' no puede estar vacío";
                            }
                            if (empty(trim($review['content'] ?? ''))) {
                                $errors[] = "Bloque #$blockNumber (Reseña #$num): El campo 'Contenido' no puede estar vacío";
                            }
                        }
                    }
                    break;
            }
        }

        return $errors;
    }

    private function proceedWithUpdate()
    {
        try {
            // Limpiar errores previos de recursos
            $this->contentErrors = [];

            // Validar bloques de recursos antes de la validación general
            $blockErrors = $this->validateBlocks();
            if (!empty($blockErrors)) {
                $this->contentErrors = $blockErrors;
                $this->openSections['resources'] = true;
                session()->flash('error', 'Hay errores en los recursos. Revisa los bloques vacíos.');
                return;
            }

            // Validar campos básicos
            $this->validate();

            // Preparar datos para actualizar (solo contenido, no estado/asignación)
            $updateData = [
                'title' => $this->title,
                'section' => $this->section,
                'description' => $this->description,
                'resources' => $this->resources,
                'updated_by' => Auth::user()->id,
            ];

            // Limpiar recursos no utilizados antes de actualizar
            $this->dispatch('cleanupUnusedResources', ['finalBlocks' => $this->resources]);

            // Actualizar el tema
            $this->topic->update($updateData);

            // Redireccionar al listado con mensaje de éxito
            session()->flash('message', 'Tema sugerido actualizado exitosamente.');
            return redirect()->route('suggested-topics.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->handleValidationErrors($e);
            throw $e;
        }
    }

    private function handleValidationErrors($e)
    {
        $errorBags = $e->validator->getMessageBag()->getMessages();
        $sectionMap = [
            'basic' => ['title', 'section', 'description'],
            'resources' => ['resources'],
            'status' => ['status', 'assignedTo'],
        ];

        foreach ($sectionMap as $section => $fields) {
            foreach ($fields as $field) {
                foreach ($errorBags as $errorKey => $messages) {
                    if ($field === $errorKey) {
                        $this->openSections[$section] = true;
                    }
                }
            }
        }
    }

    public function update()
    {
        // Marcar que estamos esperando los datos del content editor
        $this->waitingForContentData = true;

        // Solicitar los datos del content editor
        $this->dispatch('requestContentData');

        // La validación continuará en receiveContentData()
    }

    public function cancel($redirectUrl = null)
    {
        if (is_array($redirectUrl) && isset($redirectUrl['redirectUrl'])) {
            $redirectUrl = $redirectUrl['redirectUrl'];
        }
        $this->cancelRedirectUrl = $redirectUrl;
        $this->showCancelModal = true;
    }

    public function confirmCancel()
    {
        $this->dispatch('cleanupNewResources');

        $url = $this->cancelRedirectUrl;
        $this->cancelRedirectUrl = null;
        session()->flash('message', 'Edición de tema sugerido cancelada');
        return $url ? redirect()->to($url) : redirect()->route('suggested-topics.index');
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
    }

    public function getUsersProperty()
    {
        return User::orderBy('name')->get();
    }


    public function openDeleteModal()
    {
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
    }

    public function confirmDeleteTopic()
    {
        $user = Auth::user();

        // Creador o editor_chief/moderator/administrator pueden eliminar
        if ($this->topic->created_by !== $user->id && ! in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
            session()->flash('error', 'No tienes permisos para eliminar este tema.');
            $this->closeDeleteModal();
            return;
        }

        $this->topic->delete();
        session()->flash('message', 'Tema eliminado exitosamente.');
        return redirect()->route('suggested-topics.index');
    }

    public function getIsOwnerProperty()
    {
        $user = Auth::user();
        return $this->topic->created_by === $user->id;
    }

    /** Creador o editor_chief/moderator/administrator pueden eliminar. */
    public function getCanDeleteProperty()
    {
        $user = Auth::user();
        return $this->topic->created_by === $user->id || in_array($user->rol, ['editor_chief', 'moderator', 'administrator']);
    }

    public function render()
    {
        return view('livewire.edit-suggested-topic', [
            'isOwner' => $this->isOwner,
            'canDelete' => $this->canDelete,
        ]);
    }
}
