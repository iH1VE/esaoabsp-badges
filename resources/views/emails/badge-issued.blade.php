<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Badge emitida</title>
</head>
<body style="margin:0; padding:0; background:#f5f7fb; font-family:Arial, Helvetica, sans-serif; color:#111827;">
    <div style="max-width:640px; margin:0 auto; padding:32px 16px;">
        <div style="background:#ffffff; border:1px solid #e5e7eb; border-radius:18px; overflow:hidden;">
            <div style="padding:24px 24px 12px;">
                <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#6b7280; margin-bottom:10px;">
                    ESAOABSP Badges
                </div>

                <h1 style="margin:0 0 12px; font-size:28px; line-height:1.2; color:#111827;">
                    Você recebeu uma badge
                </h1>

                <p style="margin:0 0 18px; font-size:15px; line-height:1.6; color:#475569;">
                    Olá <strong>{{ $issuance->recipient_name }}</strong>,
                    sua badge foi emitida com sucesso pela Escola Superior de Advocacia — OABSP.
                </p>

                <div style="padding:18px; border:1px solid #e5e7eb; border-radius:16px; background:#fafafa;">
                    <div style="font-size:13px; color:#6b7280; margin-bottom:6px;">Badge</div>
                    <div style="font-size:20px; font-weight:700; color:#3730a3; margin-bottom:10px;">
                        {{ $issuance->badge->title }}
                    </div>

                    @if($issuance->badge->description)
                        <div style="font-size:14px; line-height:1.6; color:#475569; margin-bottom:12px;">
                            {{ $issuance->badge->description }}
                        </div>
                    @endif

                    <div style="font-size:14px; color:#111827;">
                        <strong>Data de emissão:</strong>
                        {{ optional($issuance->issued_at)->format('d/m/Y H:i') ?? '-' }}
                    </div>

                    <div style="font-size:14px; color:#111827; margin-top:6px;">
                        <strong>ID público:</strong>
                        {{ $issuance->public_id }}
                    </div>
                </div>

                <div style="margin-top:22px;">
                    <a href="{{ $publicUrl }}"
                       style="display:inline-block; background:#6366f1; color:#ffffff; text-decoration:none; padding:12px 18px; border-radius:12px; font-weight:700; margin-right:10px;">
                        Visualizar badge
                    </a>

                    <a href="{{ $pdfUrl }}"
                       style="display:inline-block; background:#ffffff; color:#111827; text-decoration:none; padding:12px 18px; border-radius:12px; border:1px solid #d1d5db; font-weight:700;">
                        Baixar PDF
                    </a>
                </div>

                <p style="margin:22px 0 0; font-size:13px; line-height:1.6; color:#6b7280;">
                    O certificado em PDF também segue anexado neste e-mail.
                </p>
            </div>

            <div style="padding:16px 24px; background:#f8fafc; border-top:1px solid #e5e7eb; font-size:12px; color:#6b7280;">
                ESAOABSP Badges • Emissão digital de credenciais
            </div>
        </div>
    </div>
</body>
</html>
