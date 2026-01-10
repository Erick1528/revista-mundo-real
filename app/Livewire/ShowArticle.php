<?php

namespace App\Livewire;

use Livewire\Component;

class ShowArticle extends Component
{

    public $article;
    public $section;
    public $fecha;
    public $authorName;

    public function mount()
    {
        if ($this->article->status !== 'published') {
            abort(404, 'Pagina no encontrada');
        }

        if ($this->article->section == 'destinations') {
            $this->section = 'Destinos';
        } elseif ($this->article->section == 'inspiring_stories') {
            $this->section = 'Historias que inspiran';
        } elseif ($this->article->section == 'social_events') {
            $this->section = 'Eventos sociales';
        } elseif ($this->article->section == 'health_wellness') {
            $this->section = 'Salud y Bienestar';
        } elseif ($this->article->section == 'gastronomy') {
            $this->section = 'Gastronomía';
        } elseif ($this->article->section == 'living_culture') {
            $this->section = 'Cultura viva';
        } else {
            $this->section = 'Sección desconocida';
        }

        if (!empty(trim($this->article->attribution))) {
            $this->authorName = $this->article->attribution;
        } else {
            $this->authorName = $this->article->user->name ?? 'Autor desconocido';
        }

        $meses = [
            'ene' => 'Ene',
            'feb' => 'Feb',
            'mar' => 'Mar',
            'abr' => 'Abr',
            'may' => 'May',
            'jun' => 'Jun',
            'jul' => 'Jul',
            'ago' => 'Ago',
            'sep' => 'Sep',
            'oct' => 'Oct',
            'nov' => 'Nov',
            'dic' => 'Dic',
        ];
        $fecha = $this->article->created_at->locale('es')->translatedFormat('j M Y');
        $fecha = str_replace('.', '', $fecha);
        $fecha = preg_replace_callback(
            '/\b([a-z]{3})\b/i',
            function ($matches) use ($meses) {
                $mes = strtolower($matches[1]);
                return $meses[$mes] ?? $matches[1];
            },
            $fecha,
        );

        $this->fecha = $fecha;
    }

    public function render()
    {
        return view('livewire.show-article');
    }
}
