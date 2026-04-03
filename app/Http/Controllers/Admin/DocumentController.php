<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::where('is_deleted', false);

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $documents = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.driver-documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.driver-documents.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'front_side' => 'nullable|boolean',
            'back_side'  => 'nullable|boolean',
            'enable'     => 'nullable|boolean',
            'expire_at'  => 'nullable|boolean',
        ]);

        $validated['front_side'] = $request->boolean('front_side');
        $validated['back_side'] = $request->boolean('back_side');
        $validated['enable'] = $request->boolean('enable');
        $validated['expire_at'] = $request->boolean('expire_at');
        $validated['is_deleted'] = false;

        Document::create($validated);

        return redirect()->route('admin.driver-documents.index')
            ->with('success', 'Document created successfully.');
    }

    public function edit($id)
    {
        $document = Document::where('is_deleted', false)->findOrFail($id);

        return view('admin.driver-documents.form', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $document = Document::where('is_deleted', false)->findOrFail($id);

        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'front_side' => 'nullable|boolean',
            'back_side'  => 'nullable|boolean',
            'enable'     => 'nullable|boolean',
            'expire_at'  => 'nullable|boolean',
        ]);

        $validated['front_side'] = $request->boolean('front_side');
        $validated['back_side'] = $request->boolean('back_side');
        $validated['enable'] = $request->boolean('enable');
        $validated['expire_at'] = $request->boolean('expire_at');

        $document->update($validated);

        return redirect()->route('admin.driver-documents.index')
            ->with('success', 'Document updated successfully.');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $document->is_deleted = true;
        $document->save();

        return redirect()->route('admin.driver-documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $document = Document::where('is_deleted', false)->findOrFail($id);
        $document->enable = !$document->enable;
        $document->save();

        return redirect()->back()
            ->with('success', 'Document status updated successfully.');
    }
}
