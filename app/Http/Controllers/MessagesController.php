<?php

namespace App\Http\Controllers;

use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    /**
     * Show all message threads.
     */
    public function index()
    {
        $threads = Thread::forUser(Auth::id())
            ->latest('updated_at')
            ->paginate(10);

        return view('messages.index', compact('threads'));
    }

    /**
     * Show a specific message thread.
     */
    public function show(int $id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException) {
            return redirect()
                ->route('messages.index')
                ->with('error', 'Thread not found.');
        }

        // Don't show deleted threads
        if ($thread->trashed()) {
            return redirect()
                ->route('messages.index')
                ->with('error', 'Thread not found.');
        }

        // Check if user is a participant
        $participant = $thread->getParticipantFromUser(Auth::id());
        if (! $participant) {
            return redirect()
                ->route('messages.index')
                ->with('error', 'You are not a participant of this conversation.');
        }

        // Mark thread as read
        $thread->markAsRead(Auth::id());

        $messages = $thread->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $participants = $thread->participantsUserIds();
        $users = \App\Models\User::whereIn('id', $participants)->get();

        return view('messages.show', compact('thread', 'messages', 'users'));
    }

    /**
     * Show form to create a new thread.
     */
    public function create()
    {
        $users = \App\Models\User::where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get();

        return view('messages.create', compact('users'));
    }

    /**
     * Store a new message thread.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'exists:users,id',
        ]);

        $thread = Thread::create([
            'subject' => $validated['subject'],
        ]);

        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $validated['message'],
        ]);

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'last_read' => now(),
        ]);

        // Recipients
        $thread->addParticipant($validated['recipients']);

        return redirect()
            ->route('messages.show', $thread->id)
            ->with('success', 'Message sent successfully!');
    }

    /**
     * Add a new message to an existing thread.
     */
    public function update(Request $request, int $id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException) {
            return redirect()
                ->route('messages.index')
                ->with('error', 'Thread not found.');
        }

        // Check if user is a participant
        if (! $thread->hasParticipant(Auth::id())) {
            return redirect()
                ->route('messages.index')
                ->with('error', 'You cannot reply to this conversation.');
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        // Create the message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $validated['message'],
        ]);

        // Update participant's last read
        $participant = $thread->getParticipantFromUser(Auth::id());
        $participant->last_read = now();
        $participant->save();

        return redirect()
            ->route('messages.show', $thread->id)
            ->with('success', 'Reply sent successfully!');
    }

    /**
     * Get unread message count for the current user.
     */
    public function unreadCount()
    {
        $count = Auth::user()->newThreadsCount();

        return response()->json(['count' => $count]);
    }
}
