<?php

namespace App\Livewire;

use App\Models\CoverArticle;
use Livewire\Component;

class MidArticles extends Component
{
    public function render()
    {
        // Get active cover if exists
        $activeCover = CoverArticle::getActive();

        return view('livewire.mid-articles', [
            'articles' => $activeCover ? $activeCover->ordered_mid_articles : collect(),
            'hasActiveCover' => $activeCover !== null,
        ]);
    }
}
