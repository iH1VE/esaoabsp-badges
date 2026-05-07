<?php

namespace App\Http\Controllers;

use App\Models\Issuance;

class PublicBadgeController extends Controller
{
    public function show(string $publicId)
    {
        $issuance = Issuance::with('badge')
            ->where('public_id', $publicId)
            ->firstOrFail();

        // evidence pode ser array (cast) OU string (legado)
        $evidence = [];

        if (!empty($issuance->evidence)) {
            if (is_array($issuance->evidence)) {
                $evidence = $issuance->evidence;
            } elseif (is_string($issuance->evidence)) {
                $decoded = json_decode($issuance->evidence, true);
                $evidence = is_array($decoded) ? $decoded : [];
            }
        }

        // compat: às vezes skills veio como string JSON dentro do evidence (legado)
        if (isset($evidence['skills']) && is_string($evidence['skills'])) {
            $skillsDecoded = json_decode($evidence['skills'], true);
            if (is_array($skillsDecoded)) {
                $evidence['skills'] = $skillsDecoded;
            }
        }

        return view('public.badge', [
            'issuance' => $issuance,
            'evidence' => $evidence,
        ]);
    }
}
