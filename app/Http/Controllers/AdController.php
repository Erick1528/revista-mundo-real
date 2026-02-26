<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    private const ALLOWED_ROLES = ['editor_chief', 'administrator', 'moderator'];

    private function canAccess(): bool
    {
        $user = Auth::user();
        return $user && in_array($user->rol, self::ALLOWED_ROLES, true);
    }

    public function index()
    {
        if (!$this->canAccess()) {
            abort(404);
        }
        return view('dashboard.ads.index');
    }

    public function create()
    {
        if (!$this->canAccess()) {
            abort(404);
        }
        return view('dashboard.ads.create');
    }

    public function show(Ad $ad)
    {
        if (!$this->canAccess()) {
            abort(404);
        }
        return view('dashboard.ads.show', compact('ad'));
    }

    public function edit(Ad $ad)
    {
        if (!$this->canAccess()) {
            abort(404);
        }
        return view('dashboard.ads.edit', compact('ad'));
    }

    public function trash()
    {
        if (!$this->canAccess()) {
            abort(404);
        }
        return view('dashboard.ads.trash');
    }
}
