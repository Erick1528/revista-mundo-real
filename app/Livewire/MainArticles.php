<?php

namespace App\Livewire;

use App\Models\CoverArticle;
use Livewire\Component;

class MainArticles extends Component
{
    public function render()
    {
        // Get active cover if exists
        $activeCover = CoverArticle::getActive();

        return view('livewire.main-articles', [
            'articles' => $activeCover ? $activeCover->ordered_main_articles : collect(),
            'hasActiveCover' => $activeCover !== null,
        ]);
    }
}
