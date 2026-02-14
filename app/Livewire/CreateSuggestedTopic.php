<?php

namespace App\Livewire;

use App\Models\SuggestedTopic;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateSuggestedTopic extends Component
{
    // Basic information
    public $title;
    public $section;
    public $description;

    // Resources (ContentEditor blocks)
    public $resources = [];

    // Assignment options
    public $assignmentType = 'none'; // 'none', 'take_myself', 'assign_to_user'
    public $assignedToUserId = null;

    // Accordion state
    public $openSections = [
        'basic' => true,
        'resources' => false,
        'assignment' => false,
    ];

    // Flag para controlar el flujo de validación
    public $waitingForContentData = false;

    // Errores específicos de validación de contenido
    public $contentErrors = [];

    // Modal de confirmación para cancelar
    public $showCancelModal = false;

    /** URL a la que redirigir al confirmar cancelación (cuando se hace clic en otro enlace del nav). */
    public $cancelRedirectUrl = null;

    // Listeners para eventos
    protected $listeners = [
        'contentDataResponse' => 'receiveContentData',
        'cancelCreateTopic' => 'cancel',
    ];

    protected function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'section' => 'required|in:destinations,inspiring_stories,social_events,health_wellness,gastronomy,living_culture',
            'description' => 'nullable|string|max:1000',
            'resources' => 'nullable|array',
            'assignmentType' => 'required|in:none,take_myself,assign_to_user',
        ];

        // Si se selecciona asignar a usuario, validar que se haya seleccionado un usuario
        if ($this->assignmentType === 'assign_to_user') {
            $user = Auth::user();
            if (in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
                $rules['assignedToUserId'] = 'required|exists:users,id';
            }
        }

        return $rules;
    }

    protected $messages = [
        'title.required' => 'El título del tema es obligatorio.',
        'title.string' => 'El título debe ser texto válido.',
        'title.max' => 'El título no puede tener más de 255 caracteres.',
        'section.required' => 'Debe seleccionar una sección para el tema.',
        'section.in' => 'La sección seleccionada no es válida.',
        'description.string' => 'La descripción debe ser texto válido.',
        'description.max' => 'La descripción no puede tener más de 1000 caracteres.',
        'resources.array' => 'Los recursos deben tener un formato válido.',
        'assignmentType.required' => 'Debe seleccionar una opción de asignación.',
        'assignmentType.in' => 'La opción de asignación seleccionada no es válida.',
        'assignedToUserId.required' => 'Debe seleccionar un usuario para asignar el tema.',
        'assignedToUserId.exists' => 'El usuario seleccionado no existe.',
    ];

    public function toggleSection($section)
    {
        $this->openSections[$section] = !$this->openSections[$section];
    }

    public function updatedAssignmentType($value)
    {
        // Limpiar el usuario asignado si se cambia la opción
        if ($value !== 'assign_to_user') {
            $this->assignedToUserId = null;
        }
    }

    public function receiveContentData($data)
    {
        // Actualizar la propiedad resources con los datos recibidos del editor
        $this->resources = $data['blocks'] ?? [];

        // Si estábamos esperando los datos para proceder con la validación
        if ($this->waitingForContentData) {
            $this->waitingForContentData = false;
            $this->proceedWithSave();
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

            // Verificar que el bloque tenga tipo
            if (!isset($block['type'])) {
                $errors[] = "Bloque #$blockNumber: Tipo de bloque no válido";
                continue;
            }

            // Validar contenido según el tipo de bloque (similar a CreateArticle)
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

    private function proceedWithSave()
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

            // Determinar estado y asignación según la opción seleccionada
            $status = 'available';
            $assignedTo = null;
            $takenAt = null;

            if ($this->assignmentType === 'take_myself') {
                $status = 'taken';
                $assignedTo = Auth::user()->id;
                $takenAt = now();
            } elseif ($this->assignmentType === 'assign_to_user' && $this->assignedToUserId) {
                $user = Auth::user();
                // Solo permitir asignación si tiene permisos
                if (in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
                    $status = 'taken';
                    $assignedTo = $this->assignedToUserId;
                    $takenAt = now();
                }
            }

            // Crear el tema sugerido
            $topic = SuggestedTopic::create([
                'title' => $this->title,
                'section' => $this->section,
                'description' => $this->description,
                'resources' => $this->resources,
                'status' => $status,
                'assigned_to' => $assignedTo,
                'taken_at' => $takenAt,
                'created_by' => Auth::user()->id,
            ]);

            if ($assignedTo) {
                $assignedUser = User::find($assignedTo);
                if ($assignedUser) {
                    \App\Notifications\SuggestedTopicNotificationService::notifyUserTopicAssigned($topic, $assignedUser);
                }
            }

            // Determinar mensaje según la acción realizada
            $message = 'Tema sugerido creado exitosamente.';
            if ($this->assignmentType === 'take_myself') {
                $message = 'Tema sugerido creado y tomado exitosamente.';
            } elseif ($this->assignmentType === 'assign_to_user' && $assignedTo) {
                $assignedUser = User::find($assignedTo);
                $message = 'Tema sugerido creado y asignado a ' . $assignedUser->name . ' exitosamente.';
            }

            // Resetear formulario
            $this->resetFormData();

            // Redireccionar al listado con mensaje de éxito
            session()->flash('message', $message);
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

    public function save()
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
        $this->resetFormData();

        $this->dispatch('cleanupBlockResources');

        $url = $this->cancelRedirectUrl;
        $this->cancelRedirectUrl = null;
        session()->flash('message', 'Creación de tema sugerido cancelada');
        return $url ? redirect()->to($url) : redirect()->route('suggested-topics.index');
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
    }

    private function resetFormData()
    {
        $this->title = '';
        $this->section = '';
        $this->description = '';
        $this->resources = [];
        $this->assignmentType = 'none';
        $this->assignedToUserId = null;

        $this->openSections = [
            'basic' => true,
            'resources' => false,
            'assignment' => false,
        ];

        $this->waitingForContentData = false;
        $this->contentErrors = [];

        $this->resetValidation();
        $this->resetErrorBag();
    }

    public function getUsersProperty()
    {
        return User::orderBy('name')->get();
    }

    public function getCanAssignProperty()
    {
        $user = Auth::user();
        return in_array($user->rol, ['editor_chief', 'moderator', 'administrator']);
    }

    public function render()
    {
        return view('livewire.create-suggested-topic', [
            'users' => $this->users,
            'canAssign' => $this->canAssign,
        ]);
    }
}
