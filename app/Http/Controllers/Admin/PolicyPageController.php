<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PolicyPage;
use Illuminate\Http\Request;

class PolicyPageController extends Controller
{
    public function index()
    {
        $policies = PolicyPage::all()->keyBy('type');
        return view('admin.policies.policy-pages.index', compact('policies'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($type)
    {
        $validTypes = ['privacy_policy', 'cookie_policy', 'terms_agreement'];

        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $policy = PolicyPage::where('type', $type)->first();

        return view('admin.policies.policy-pages.edit', [
            'type' => $type,
            'policy' => $policy
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $type)
{
    $validTypes = ['privacy_policy', 'cookie_policy', 'terms_agreement'];

    if (!in_array($type, $validTypes)) {
        abort(404);
    }

    $request->validate([
        'content' => 'required|string',
    ]);

    PolicyPage::updateOrCreate(
        ['type' => $type],
        ['content' => $request->content]
    );

    return redirect()->route('admin.policy-pages.index')
        ->with('success', ucwords(str_replace('_', ' ', $type)) . ' updated successfully');
}
}
