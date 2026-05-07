<?php

namespace App\Http\Controllers;

use App\Models\Issuance;
use Barryvdh\DomPDF\Facade\Pdf;

class PublicIssuanceController extends Controller
{
    public function show(string $public_id)
    {
        $issuance = Issuance::with('badge')
            ->where('public_id', $public_id)
            ->firstOrFail();

        return view('public.issuances.show', compact('issuance'));
    }

    public function pdf(string $public_id)
    {
        $issuance = Issuance::with('badge')
            ->where('public_id', $public_id)
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.issuance', compact('issuance'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('badge-'.$issuance->public_id.'.pdf');
    }
}
