<?php

namespace App\Http\Controllers;

use App\Models\SuggestedTopic;

class SuggestedTopicController extends Controller
{
    /**
     * Display a listing of suggested topics.
     * Filters and list are handled by the SuggestedTopicList Livewire component.
     */
    public function index()
    {
        return view('dashboard.suggested-topics.index');
    }

    /**
     * Show the form for creating a new suggested topic.
     */
    public function create()
    {
        return view('dashboard.suggested-topics.create');
    }

    /**
     * Display the specified suggested topic.
     */
    public function show(SuggestedTopic $topic)
    {
        return view('dashboard.suggested-topics.show', compact('topic'));
    }

    /**
     * Show the form for editing an existing suggested topic.
     */
    public function edit(SuggestedTopic $topic)
    {
        // Cargar relaciones necesarias
        $topic->load(['creator', 'assignedUser', 'requester']);
        return view('dashboard.suggested-topics.edit', compact('topic'));
    }
}
