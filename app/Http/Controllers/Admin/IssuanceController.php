<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BadgeIssuedMail;
use App\Models\Badge;
use App\Models\Issuance;
use App\Models\Trail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class IssuanceController extends Controller
{
    public function index(Request $request)
    {
        $badges = Badge::query()
            ->orderBy('title')
            ->get();

        $query = Issuance::with('badge')->orderByDesc('id');

        if ($request->filled('badge_id')) {
            $query->where('badge_id', $request->badge_id);
        }

        if ($request->filled('issued_date')) {
            $query->whereDate('issued_at', $request->issued_date);
        }

        if ($request->filled('q')) {
            $search = trim($request->q);

            $query->where(function ($q) use ($search) {
                $q->where('recipient_name', 'like', "%{$search}%")
                  ->orWhere('recipient_email', 'like', "%{$search}%")
                  ->orWhere('public_id', 'like', "%{$search}%");
            });
        }

        $issuances = $query->paginate(20)->withQueryString();

        return view('admin.issuances.index', compact('issuances', 'badges'));
    }

    public function create()
    {
        $badges = Badge::query()
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        return view('admin.issuances.create', compact('badges'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'badge_id' => ['required', 'exists:badges,id'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_email' => ['required', 'email', 'max:255'],
            'issued_at' => ['nullable', 'date'],
            'courses' => ['nullable', 'string'],
            'total_hours' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'skills' => ['nullable', 'string'],
        ]);

        $badge = Badge::findOrFail($data['badge_id']);
        $recipientEmail = strtolower($data['recipient_email']);

        $courses = [];
        if (!empty($data['courses'])) {
            $courses = collect(preg_split("/\r\n|\n|\r/", $data['courses']))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->map(fn ($title) => ['title' => $title])
                ->values()
                ->all();
        }

        $skills = [];
        if (!empty($data['skills'])) {
            $skills = collect(preg_split("/\r\n|\n|\r/", $data['skills']))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->values()
                ->all();
        }

        $evidence = [
            'courses' => $courses,
            'total_hours' => !empty($data['total_hours']) ? (int) $data['total_hours'] : $badge->hours,
            'skills' => !empty($skills) ? $skills : ($badge->skills ?? []),
        ];

        $alreadyIssued = Issuance::query()
            ->where('badge_id', $badge->id)
            ->where('recipient_email', $recipientEmail)
            ->exists();

        if ($alreadyIssued) {
            return redirect()
                ->back()
                ->withInput()
                ->with('status', 'Essa badge já foi emitida para este e-mail.');
        }

        $issuance = Issuance::create([
            'badge_id' => $badge->id,
            'recipient_name' => $data['recipient_name'],
            'recipient_email' => $recipientEmail,
            'issued_at' => $data['issued_at'] ?? now(),
            'evidence' => $evidence,
            'status' => 'issued',
        ]);

        $this->issueCompletedTrailBadges(
            recipientName: $issuance->recipient_name,
            recipientEmail: $recipientEmail
        );

        try {
            Mail::to($issuance->recipient_email)->send(new BadgeIssuedMail($issuance));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('admin.issuances.index')
            ->with('status', 'Badge emitida para '.$issuance->recipient_name);
    }

    public function revokeForm(Issuance $issuance)
    {
        $issuance->load('badge');

        return view('admin.issuances.revoke', compact('issuance'));
    }

    public function revoke(Request $request, Issuance $issuance)
    {
        $data = $request->validate([
            'revocation_reason' => ['required', 'string', 'max:1000'],
        ]);

        $issuance->update([
            'status' => 'revoked',
            'revoked_at' => now(),
            'revocation_reason' => $data['revocation_reason'],
        ]);

        return redirect()
            ->route('admin.issuances.index')
            ->with('status', 'Emissão revogada: '.$issuance->recipient_name);
    }

    protected function issueCompletedTrailBadges(string $recipientName, string $recipientEmail): void
    {
        $earnedBadgeIds = Issuance::query()
            ->where('recipient_email', $recipientEmail)
            ->where('status', 'issued')
            ->pluck('badge_id')
            ->unique()
            ->values()
            ->all();

        $trails = Trail::query()
            ->with(['badges', 'awardBadge'])
            ->where('is_active', true)
            ->whereNotNull('award_badge_id')
            ->get();

        foreach ($trails as $trail) {
            $requiredBadgeIds = $trail->badges->pluck('id')->values()->all();

            if (empty($requiredBadgeIds)) {
                continue;
            }

            $isCompleted = count(array_diff($requiredBadgeIds, $earnedBadgeIds)) === 0;

            if (!$isCompleted || !$trail->awardBadge) {
                continue;
            }

            $alreadyIssued = Issuance::query()
                ->where('recipient_email', $recipientEmail)
                ->where('badge_id', $trail->award_badge_id)
                ->exists();

            if ($alreadyIssued) {
                continue;
            }

            $trailIssuance = Issuance::create([
                'badge_id' => $trail->award_badge_id,
                'recipient_name' => $recipientName,
                'recipient_email' => $recipientEmail,
                'issued_at' => now(),
                'evidence' => [
                    'courses' => [],
                    'total_hours' => $trail->awardBadge->hours,
                    'skills' => $trail->awardBadge->skills ?? [],
                    'generated_by_trail' => true,
                    'trail_title' => $trail->title,
                ],
                'status' => 'issued',
            ]);

            try {
                Mail::to($trailIssuance->recipient_email)->send(new BadgeIssuedMail($trailIssuance));
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }
}
