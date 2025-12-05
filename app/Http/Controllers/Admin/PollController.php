<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PollController extends Controller
{
    public function index(): View
    {
        $polls = Poll::with('creator')
            ->withCount('votes')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.polls.index', [
            'polls' => $polls,
        ]);
    }

    public function create(): View
    {
        return view('admin.polls.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'description' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'allow_multiple' => 'boolean',
            'show_results' => 'boolean',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        $poll = Poll::create([
            'question' => $validated['question'],
            'description' => $validated['description'],
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'allow_multiple' => $validated['allow_multiple'] ?? false,
            'show_results' => $validated['show_results'] ?? true,
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);

        foreach ($validated['options'] as $index => $optionText) {
            $poll->options()->create([
                'option_text' => $optionText,
                'sort_order' => $index,
            ]);
        }

        return redirect()->route('admin.polls.index')
            ->with('success', 'Poll created successfully!');
    }

    public function edit(Poll $poll): View
    {
        $poll->load('options');

        return view('admin.polls.edit', [
            'poll' => $poll,
        ]);
    }

    public function update(Request $request, Poll $poll): RedirectResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'description' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'allow_multiple' => 'boolean',
            'show_results' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $poll->update($validated);

        return redirect()->route('admin.polls.index')
            ->with('success', 'Poll updated successfully!');
    }

    public function destroy(Poll $poll): RedirectResponse
    {
        $poll->delete();

        return redirect()->route('admin.polls.index')
            ->with('success', 'Poll deleted successfully!');
    }
}
