<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomepageContent;

class AdminHomepageController extends Controller
{
    public function index()
    {
        $content = HomepageContent::first() ?? new HomepageContent();
        return view('admin.homepage.index', compact('content'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['required', 'string'],
            'announcement_banner' => ['required', 'string', 'max:255'],
            'total_lawyers_count' => ['required', 'numeric'],
            'total_cases_count' => ['required', 'numeric'],
            'satisfaction_rate' => ['required', 'string', 'max:50'],
        ]);

        $content = HomepageContent::first();
        if ($content) {
            $content->update($validated);
        } else {
            HomepageContent::create($validated);
        }

        return back()->with('success', 'Homepage content updated successfully!');
    }
}
