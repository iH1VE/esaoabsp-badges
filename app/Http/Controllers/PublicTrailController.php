<?php

namespace App\Http\Controllers;

use App\Models\Issuance;
use App\Models\Trail;
use Illuminate\Http\Request;

class PublicTrailController extends Controller
{
    public function show(Request $request, Trail $trail)
    {
        $trail->load([
            'badges' => function ($query) {
                $query->orderBy('trail_badges.order');
            },
            'awardBadge',
        ]);

        $email = strtolower(trim((string) $request->get('email', '')));
        $progress = null;

        if ($email !== '') {
            $issuances = Issuance::query()
                ->with('badge')
                ->where('recipient_email', $email)
                ->where('status', 'issued')
                ->get()
                ->keyBy('badge_id');

            $items = $trail->badges->map(function ($badge) use ($issuances) {
                $issuance = $issuances->get($badge->id);

                return [
                    'badge' => $badge,
                    'earned' => (bool) $issuance,
                    'issued_at' => $issuance?->issued_at,
                    'public_id' => $issuance?->public_id,
                ];
            });

            $requiredCount = $items->count();
            $completedCount = $items->where('earned', true)->count();

            $progress = [
                'email' => $email,
                'items' => $items,
                'required_count' => $requiredCount,
                'completed_count' => $completedCount,
                'missing_count' => max($requiredCount - $completedCount, 0),
                'completed_percentage' => $requiredCount > 0
                    ? round(($completedCount / $requiredCount) * 100)
                    : 0,
                'is_completed' => $requiredCount > 0 && $completedCount === $requiredCount,
            ];
        }

        return view('public.trails.show', compact('trail', 'progress'));
    }
}
