<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\Subscriber;

class MessageController extends Controller
{
    public function index()
    {
        $messages = ContactSubmission::latest()->get();
        return view('admin.messages.index', compact('messages'));
    }

    public function show(ContactSubmission $message)
    {
        return view('admin.messages.show', compact('message'));
    }

    public function destroy(ContactSubmission $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')
            ->with('success', 'Message deleted successfully');
    }
    public function subscribe_index()
    {
        $messages = Subscriber::latest()->get();
        return view('admin.subscribe.index', compact('messages'));
    }
    public function subscribe_show(Subscriber $message)
    {
        $message = Subscriber::find($message->id);
        if (!$message) {
            return redirect()->route('admin.subscribe.index')
                ->with('error', 'Message not found');
        }
        return view('admin.subscribe.show', compact('message'));
    }
    public function subscribe_destroy(Subscriber $message)
    {
        $message->delete();
        return redirect()->route('admin.subscribe.index')
            ->with('success', 'Message deleted successfully');
    }
}
