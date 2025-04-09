<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;

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
}
