<?php

namespace App\Mail;

use App\Models\Issuance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BadgeIssuedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Issuance $issuance;

    public function __construct(Issuance $issuance)
    {
        $this->issuance = $issuance->load('badge');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Você recebeu uma badge da ESAOABSP'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.badge-issued',
            with: [
                'issuance' => $this->issuance,
                'publicUrl' => route('public.issuances.show', $this->issuance->public_id),
                'pdfUrl' => route('public.issuances.pdf', $this->issuance->public_id),
            ]
        );
    }

    public function build()
    {
        $pdf = Pdf::loadView('pdf.issuance', [
            'issuance' => $this->issuance,
        ])->setPaper('a4', 'portrait');

        return $this->view('emails.badge-issued')
            ->with([
                'issuance' => $this->issuance,
                'publicUrl' => route('public.issuances.show', $this->issuance->public_id),
                'pdfUrl' => route('public.issuances.pdf', $this->issuance->public_id),
            ])
            ->attachData(
                $pdf->output(),
                'badge-'.$this->issuance->public_id.'.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
