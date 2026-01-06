<?php

namespace App\Http\Controllers;

use App\Models\HealthTip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthTipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = HealthTip::with('doctor')->where('is_published', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $healthTips = $query->latest()->paginate(10);
        $categories = HealthTip::distinct()->pluck('category');
        return view('health-tips.index', compact('healthTips', 'categories'));
    }

    public function show(HealthTip $tip)
    {
        if (!$tip->is_published && !Auth::user()->isAdmin() && 
            (Auth::user()->isDoctor() && $tip->doctor_id !== Auth::user()->doctor->id)) {
            abort(403);
        }

        $tip->load('doctor');
        return view('health-tips.show', compact('tip'));
    }

    public function create()
    {
        if (!Auth::user()->isDoctor()) {
            abort(403);
        }

        return view('health-tips.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isDoctor()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'is_published' => 'boolean'
        ]);

        Auth::user()->doctor->healthTips()->create($request->all());
        return redirect()->route('health-tips.index')
            ->with('success', 'Health tip created successfully');
    }

    public function edit(HealthTip $tip)
    {
        if (!Auth::user()->isDoctor() || $tip->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        return view('health-tips.edit', compact('tip'));
    }

    public function update(Request $request, HealthTip $tip)
    {
        if (!Auth::user()->isDoctor() || $tip->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'is_published' => 'boolean'
        ]);

        $tip->update($request->all());
        return redirect()->route('health-tips.show', $tip)
            ->with('success', 'Health tip updated successfully');
    }

    public function destroy(HealthTip $tip)
    {
        if (!Auth::user()->isDoctor() || $tip->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $tip->delete();
        return redirect()->route('health-tips.index')
            ->with('success', 'Health tip deleted successfully');
    }

    public function togglePublish(HealthTip $tip)
    {
        if (!Auth::user()->isDoctor() || $tip->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $tip->update(['is_published' => !$tip->is_published]);
        return back()->with('success', 
            $tip->is_published ? 'Health tip published successfully' : 'Health tip unpublished successfully'
        );
    }
} 