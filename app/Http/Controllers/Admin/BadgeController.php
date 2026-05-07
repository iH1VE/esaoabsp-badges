<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::query()
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.badges.index', compact('badges'));
    }

    public function create()
    {
        return view('admin.badges.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'hours' => ['required', 'integer', 'min:0', 'max:10000'],
            'skills' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'is_active' => ['nullable'],
        ]);

        $skills = [];
        if (!empty($data['skills'])) {
            $skills = collect(preg_split("/\r\n|\n|\r/", $data['skills']))
                ->map(fn ($s) => trim($s))
                ->filter()
                ->values()
                ->all();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('badges', 'public');
        }

        $badge = Badge::create([
            'code' => $data['code'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'hours' => (int) $data['hours'],
            'skills' => $skills ?: null,
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.badges.index')
            ->with('status', 'Badge criada: ' . $badge->title);
    }

    public function edit(Badge $badge)
    {
        return view('admin.badges.edit', compact('badge'));
    }

    public function update(Request $request, Badge $badge)
    {
        $data = $request->validate([
            'code' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'hours' => ['required', 'integer', 'min:0', 'max:10000'],
            'skills' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'remove_image' => ['nullable'],
            'is_active' => ['nullable'],
        ]);

        $skills = [];
        if (!empty($data['skills'])) {
            $skills = collect(preg_split("/\r\n|\n|\r/", $data['skills']))
                ->map(fn ($s) => trim($s))
                ->filter()
                ->values()
                ->all();
        }

        $imagePath = $badge->image_path;

        if ($request->boolean('remove_image') && $badge->image_path) {
            Storage::disk('public')->delete($badge->image_path);
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            if ($badge->image_path) {
                Storage::disk('public')->delete($badge->image_path);
            }
            $imagePath = $request->file('image')->store('badges', 'public');
        }

        $badge->update([
            'code' => $data['code'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'hours' => (int) $data['hours'],
            'skills' => $skills ?: null,
            'image_path' => $imagePath,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.badges.index')
            ->with('status', 'Badge atualizada: ' . $badge->title);
    }

    public function destroy(Badge $badge)
    {
        if ($badge->image_path) {
            Storage::disk('public')->delete($badge->image_path);
        }

        $title = $badge->title;
        $badge->delete();

        return redirect()
            ->route('admin.badges.index')
            ->with('status', 'Badge excluída: ' . $title);
    }
}
