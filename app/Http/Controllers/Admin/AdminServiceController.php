<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Str;

class AdminServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('created_at', 'asc')->get();
        return view('admin.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        Service::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? '',
            'icon' => $validated['icon'] ?? 'gavel',
            'is_active' => true,
        ]);

        return back()->with('success', 'Legal service category added successfully!');
    }

    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);
        $service->is_active = !$service->is_active;
        $service->save();

        return back()->with('success', 'Legal service status toggled!');
    }
}
