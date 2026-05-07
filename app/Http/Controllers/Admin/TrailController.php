<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Issuance;
use App\Models\Trail;
use Illuminate\Http\Request;

class TrailController extends Controller
{
    public function index()
    {
        $trails = Trail::query()
            ->with(['badges', 'awardBadge'])
            ->withCount('badges')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.trails.index', compact('trails'));
    }

    public function show(Request $request, Trail $trail)
    {
        $trail->load([
            'badges' => function ($query) {
                $query->orderBy('trail_badges.order');
            },
            'awardBadge',
        ]);

        $email = trim((string) $request->get('email', ''));
        $progress = null;

        if ($email !== '') {
            $earnedBadgeIds = Issuance::query()
                ->where('recipient_email', strtolower($email))
                ->where('status', 'issued')
                ->pluck('badge_id')
                ->unique()
                ->values()
                ->all();

            $requiredBadgeIds = $trail->badges->pluck('id')->values()->all();

            $completedBadgeIds = array_values(array_intersect($requiredBadgeIds, $earnedBadgeIds));
            $missingBadgeIds = array_values(array_diff($requiredBadgeIds, $earnedBadgeIds));

            $progress = [
                'email' => strtolower($email),
                'required_count' => count($requiredBadgeIds),
                'completed_count' => count($completedBadgeIds),
                'missing_count' => count($missingBadgeIds),
                'completed_percentage' => count($requiredBadgeIds) > 0
                    ? round((count($completedBadgeIds) / count($requiredBadgeIds)) * 100)
                    : 0,
                'completed_badge_ids' => $completedBadgeIds,
                'missing_badge_ids' => $missingBadgeIds,
                'is_completed' => count($requiredBadgeIds) > 0 && count($missingBadgeIds) === 0,
            ];
        }

        return view('admin.trails.show', compact('trail', 'progress'));
    }

    public function create()
    {
        $badges = Badge::query()
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        return view('admin.trails.create', compact('badges'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'award_badge_id' => ['nullable', 'exists:badges,id'],
            'badges' => ['nullable', 'array'],
            'badges.*' => ['exists:badges,id'],
            'is_active' => ['nullable'],
        ]);

        $trail = Trail::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'award_badge_id' => $data['award_badge_id'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        if (!empty($data['badges'])) {
            foreach (array_values($data['badges']) as $index => $badgeId) {
                $trail->badges()->attach($badgeId, ['order' => $index + 1]);
            }
        }

        return redirect()
            ->route('admin.trails.index')
            ->with('status', 'Trilha criada: '.$trail->title);
    }

    public function edit(Trail $trail)
    {
        $trail->load('badges');

        $badges = Badge::query()
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        $selectedBadges = $trail->badges->pluck('id')->toArray();

        return view('admin.trails.edit', compact('trail', 'badges', 'selectedBadges'));
    }

    public function update(Request $request, Trail $trail)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'award_badge_id' => ['nullable', 'exists:badges,id'],
            'badges' => ['nullable', 'array'],
            'badges.*' => ['exists:badges,id'],
            'is_active' => ['nullable'],
        ]);

        $trail->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'award_badge_id' => $data['award_badge_id'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $syncData = [];
        if (!empty($data['badges'])) {
            foreach (array_values($data['badges']) as $index => $badgeId) {
                $syncData[$badgeId] = ['order' => $index + 1];
            }
        }

        $trail->badges()->sync($syncData);

        return redirect()
            ->route('admin.trails.index')
            ->with('status', 'Trilha atualizada: '.$trail->title);
    }

    public function destroy(Trail $trail)
    {
        $title = $trail->title;
        $trail->delete();

        return redirect()
            ->route('admin.trails.index')
            ->with('status', 'Trilha excluída: '.$title);
    }
}
