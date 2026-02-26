<?php

namespace App\Livewire;

use App\Models\Ad;
use Livewire\Component;

class ContentView extends Component
{

    public $content;
    public $blocks = [];

    public $isAd = false;
    public $adId = null;
    public $adUrl = null;

    public function mount()
    {
        if ($this->isAd && $this->adId) {
            $ad = Ad::find((int) $this->adId);
            if ($ad) {
                $this->content = $ad->content;
                if ($ad->redirect_url) {
                    $this->adUrl = $ad->redirect_url;
                }
            }
        }

        $content = $this->content;
        if (is_string($content)) {
            $this->blocks = json_decode($content, true) ?? [];
        } elseif (is_array($content)) {
            $this->blocks = $content;
        } else {
            $this->blocks = [];
        }
    }

    public function clickAd($adUrl): void
    {
        if (!$adUrl) {
            return;
        }
        // Añadir UTM para que el destino sepa que el tráfico viene de esta web
        $separator = str_contains($adUrl, '?') ? '&' : '?';
        $utm = http_build_query([
            'utm_source' => 'revista-mundo-real',
            'utm_medium' => 'referral',
            'utm_campaign' => 'anuncio',
        ]);
        $this->dispatch('open-ad-new-tab', url: $adUrl . $separator . $utm);
    }

    public function render()
    {
        return view('livewire.content-view');
    }
}
