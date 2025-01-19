<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingContent;
use App\Http\Requests\LandingContentRequest;
use Illuminate\Support\Facades\Storage;

class LandingController extends Controller
{
    public function index()
    {
        $contents = LandingContent::orderBy('order')->get();
        return view('admin.landing.index', compact('contents'));
    }

    public function create()
    {
        return view('admin.landing.create');
    }

    public function store(LandingContentRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('landing', 'public');
        }

        LandingContent::create($data);
        return redirect()->route('admin.landing.index')
            ->with('success', 'Content created successfully');
    }

    public function edit(LandingContent $content)
    {
        return view('admin.landing.edit', compact('content'));
    }

    public function update(LandingContentRequest $request, LandingContent $content)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($content->image) {
                Storage::disk('public')->delete($content->image);
            }
            $data['image'] = $request->file('image')->store('landing', 'public');
        }

        $content->update($data);
        return redirect()->route('admin.landing.index')
            ->with('success', 'Content updated successfully');
    }

    public function destroy(LandingContent $content)
    {
        if ($content->image) {
            Storage::disk('public')->delete($content->image);
        }
        $content->delete();
        return redirect()->route('admin.landing.index')
            ->with('success', 'Content deleted successfully');
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $id => $order) {
            LandingContent::where('id', $id)->update(['order' => $order]);
        }
        return response()->json(['success' => true]);
    }
} 