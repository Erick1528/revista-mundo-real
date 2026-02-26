<?php

namespace App\Http\Controllers;

use App\Models\Advertiser;
use Illuminate\Support\Facades\Auth;

class AdvertiserController extends Controller
{
    private const ALLOWED_ROLES = ['editor_chief', 'administrator'];

    private function canAccess(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->rol, self::ALLOWED_ROLES, true);
    }

    /**
     * Listado de anunciantes. Solo editor_chief y administrator.
     */
    public function index()
    {
        if (!$this->canAccess()) {
            abort(404);
        }
        return view('dashboard.advertisers.index');
    }

    /**
     * Formulario de creación. Solo editor_chief y administrator.
     */
    public function create()
    {
        if (!$this->canAccess()) {
            abort(404);
        }
        return view('dashboard.advertisers.create');
    }

    /**
     * Formulario de edición. Solo editor_chief y administrator.
     */
    public function edit(Advertiser $advertiser)
    {
        if (!$this->canAccess()) {
            abort(404);
        }
        return view('dashboard.advertisers.edit', compact('advertiser'));
    }

    /**
     * Papelera de anunciantes eliminados. Solo editor_chief y administrator.
     */
    public function trash()
    {
        if (!$this->canAccess()) {
            abort(404);
        }
        return view('dashboard.advertisers.trash');
    }
}
