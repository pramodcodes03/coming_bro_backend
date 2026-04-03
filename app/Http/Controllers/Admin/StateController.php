<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index(Request $request)
    {
        $query = State::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $states = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.states.index', compact('states'));
    }

    public function create()
    {
        return view('admin.states.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'enable' => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        State::create($validated);

        return redirect()->route('admin.states.index')
            ->with('success', 'State created successfully.');
    }

    public function edit($id)
    {
        $state = State::findOrFail($id);

        return view('admin.states.form', compact('state'));
    }

    public function update(Request $request, $id)
    {
        $state = State::findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'enable' => 'nullable|boolean',
        ]);

        $validated['enable'] = $request->boolean('enable');

        $state->update($validated);

        return redirect()->route('admin.states.index')
            ->with('success', 'State updated successfully.');
    }

    public function destroy($id)
    {
        $state = State::findOrFail($id);
        $state->delete();

        return redirect()->route('admin.states.index')
            ->with('success', 'State deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $state = State::findOrFail($id);
        $state->enable = !$state->enable;
        $state->save();

        return redirect()->back()
            ->with('success', 'State status updated successfully.');
    }
}
