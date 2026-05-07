<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BadgeIssuedMail;
use App\Models\Issuance;
use App\Models\Trail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BulkIssuanceController extends Controller
{
    public function index()
    {
        return view('admin.issuances.bulk');
    }

    public function template()
    {
        $csv = "badge_id,recipient_name,recipient_email,issued_at\n";
        $csv .= "1,João Silva,joao@email.com,2026-03-10 20:30:00\n";
        $csv .= "1,Maria Souza,maria@email.com,2026-03-10 20:30:00\n";

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename=badge_import_template.csv');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = $request->file('csv')->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return back()->with('status', 'Não foi possível abrir o arquivo CSV.');
        }

        $header = fgetcsv($handle);

        if (!$header) {
            fclose($handle);
            return back()->with('status', 'CSV vazio ou inválido.');
        }

        $required = ['badge_id', 'recipient_name', 'recipient_email', 'issued_at'];

        foreach ($required as $column) {
            if (!in_array($column, $header, true)) {
                fclose($handle);
                return back()->with('status', 'CSV inválido. Coluna obrigatória ausente: '.$column);
            }
        }

        $rows = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) !== count($header)) {
                continue;
            }

            $rows[] = array_combine($header, $row);
        }

        fclose($handle);

        return view('admin.issuances.bulk-process', [
            'rows' => $rows,
        ]);
    }

    public function process(Request $request)
    {
        $row = $request->input('row', []);

        try {
            $request->validate([
                'row.badge_id' => ['required', 'integer', 'exists:badges,id'],
                'row.recipient_name' => ['required', 'string', 'max:255'],
                'row.recipient_email' => ['required', 'email', 'max:255'],
                'row.issued_at' => ['nullable', 'date'],
            ]);

            $email = strtolower(trim($row['recipient_email']));

            $alreadyIssued = Issuance::query()
                ->where('badge_id', $row['badge_id'])
                ->where('recipient_email', $email)
                ->exists();

            if ($alreadyIssued) {
                return response()->json([
                    'status' => 'duplicado',
                    'name' => $row['recipient_name'],
                    'message' => 'Badge já emitida para este e-mail.',
                ]);
            }

            $issuance = Issuance::create([
                'badge_id' => $row['badge_id'],
                'recipient_name' => $row['recipient_name'],
                'recipient_email' => $email,
                'issued_at' => !empty($row['issued_at']) ? $row['issued_at'] : now(),
                'status' => 'issued',
                'evidence' => [
                    'courses' => [],
                    'total_hours' => 0,
                    'skills' => [],
                    'generated_by_csv' => true,
                ],
            ]);

            try {
                Mail::to($issuance->recipient_email)->send(new BadgeIssuedMail($issuance));
            } catch (\Throwable $e) {
                report($e);
            }

            // VERIFICA SE COMPLETOU ALGUMA TRILHA
            $this->issueCompletedTrailBadges(
                recipientName: $issuance->recipient_name,
                recipientEmail: $issuance->recipient_email
            );

            return response()->json([
                'status' => 'ok',
                'name' => $row['recipient_name'],
                'message' => 'Enviado com sucesso.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'status' => 'erro',
                'name' => $row['recipient_name'] ?? 'Linha',
                'message' => $e->getMessage(),
            ], 422);
        }
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
                    'generated_by_csv' => true,
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
