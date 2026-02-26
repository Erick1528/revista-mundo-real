<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertiser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'logo_path',
    ];

    /**
     * URL pública del logo (para usar en img src).
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) {
            return null;
        }
        return asset('storage/' . $this->logo_path);
    }
}
