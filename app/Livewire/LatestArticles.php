<?php

namespace App\Livewire;

use App\Models\CoverArticle;
use Livewire\Component;

class LatestArticles extends Component
{
    public function render()
    {
        // Get active cover if exists
        $activeCover = CoverArticle::getActive();

        return view('livewire.latest-articles', [
            'articles' => $activeCover ? $activeCover->ordered_latest_articles : collect(),
            'hasActiveCover' => $activeCover !== null,
        ]);
    }
}
