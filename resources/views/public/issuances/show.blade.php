<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $issuance->badge->title }} • ESAOABSP Badges</title>
    <meta name="description" content="Verificação pública de badge emitida pela ESAOABSP.">
    <link rel="stylesheet" href="/assets/app.css">
    <style>
        .verify-page{
            min-height:100vh;
            background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 55%, #f1f5f9 100%);
            color:#0f172a;
            padding:32px 16px 48px;
        }
        .verify-wrap{
            max-width: 980px;
            margin: 0 auto;
        }
        .verify-top{
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:16px;
            margin-bottom:20px;
        }
        .verify-brand{
            font-weight:700;
            font-size:20px;
        }
        .verify-back{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:10px 14px;
            border-radius:12px;
            border:1px solid rgba(15,23,42,.12);
            background:#fff;
            color:#0f172a;
            text-decoration:none;
        }
        .verify-grid{
            display:grid;
            grid-template-columns: 320px 1fr;
            gap:20px;
        }
        .verify-card{
            background: rgba(255,255,255,.86);
            border:1px solid rgba(15,23,42,.08);
            border-radius:20px;
            box-shadow: 0 18px 45px rgba(2,6,23,.10);
            overflow:hidden;
        }
        .verify-card-body{
            padding:22px;
        }
        .badge-image{
            width:100%;
            max-width:220px;
            aspect-ratio:1/1;
            object-fit:cover;
            border-radius:20px;
            border:1px solid rgba(15,23,42,.08);
            background:#fff;
            margin:0 auto 18px;
            display:block;
        }
        .badge-fallback{
            width:100%;
            max-width:220px;
            aspect-ratio:1/1;
            margin:0 auto 18px;
            border-radius:20px;
            border:1px dashed rgba(15,23,42,.16);
            display:flex;
            align-items:center;
            justify-content:center;
            color:rgba(15,23,42,.45);
            background:#fff;
        }
        .verify-title{
            font-size:30px;
            line-height:1.1;
            margin:0 0 8px;
            font-weight:800;
        }
        .verify-subtitle{
            margin:0 0 18px;
            color:rgba(15,23,42,.65);
        }
        .pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:8px 12px;
            border-radius:999px;
            font-size:12px;
            font-weight:700;
            border:1px solid rgba(16,185,129,.25);
            background:rgba(16,185,129,.10);
            color:#065f46;
        }
        .detail-list{
            display:grid;
            gap:14px;
        }
        .detail-item{
            border-bottom:1px solid rgba(15,23,42,.06);
            padding-bottom:12px;
        }
        .detail-label{
            font-size:12px;
            letter-spacing:.04em;
            color:rgba(15,23,42,.55);
            margin-bottom:4px;
        }
        .detail-value{
            font-size:15px;
            color:#0f172a;
            font-weight:600;
        }
        .section-title{
            font-size:16px;
            font-weight:700;
            margin:0 0 12px;
        }
        .tag-list{
            display:flex;
            flex-wrap:wrap;
            gap:8px;
        }
        .tag{
            padding:8px 12px;
            border-radius:999px;
            background:#fff;
            border:1px solid rgba(15,23,42,.10);
            font-size:13px;
        }
        .course-list{
            display:grid;
            gap:10px;
        }
        .course-item{
            padding:12px 14px;
            border-radius:14px;
            background:#fff;
            border:1px solid rgba(15,23,42,.08);
            font-size:14px;
        }
        .verify-footer{
            margin-top:18px;
            text-align:center;
            color:rgba(15,23,42,.55);
            font-size:13px;
        }
        .action-grid{
            display:grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap:10px;
            margin-top:18px;
        }
        .action-btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:10px 12px;
            border-radius:12px;
            border:1px solid rgba(15,23,42,.10);
            background:#fff;
            color:#0f172a;
            text-decoration:none;
            font-size:13px;
            font-weight:600;
            cursor:pointer;
        }
        .action-btn:hover{
            background:#f8fafc;
        }
        .qr-wrap{
            margin-top:18px;
            text-align:center;
            padding-top:18px;
            border-top:1px solid rgba(15,23,42,.06);
        }
        .qr-wrap img{
            width:160px;
            height:160px;
            border-radius:16px;
            background:#fff;
            border:1px solid rgba(15,23,42,.08);
            padding:8px;
        }
        .flash-copy{
            position:fixed;
            right:18px;
            bottom:18px;
            background:#0f172a;
            color:#fff;
            padding:10px 14px;
            border-radius:12px;
            box-shadow:0 12px 30px rgba(15,23,42,.25);
            opacity:0;
            transform:translateY(8px);
            pointer-events:none;
            transition:.2s ease;
            z-index:9999;
            font-size:13px;
        }
        .flash-copy.show{
            opacity:1;
            transform:translateY(0);
        }

.action-btn svg{
margin-right:6px;
vertical-align:middle;
}

        @media (max-width: 860px){
            .verify-grid{
                grid-template-columns: 1fr;
            }
            .action-grid{
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
@php
    $evidence = $issuance->evidence ?? [];
    $courses = $evidence['courses'] ?? [];
    $skills = $evidence['skills'] ?? [];
    $totalHours = $evidence['total_hours'] ?? $issuance->badge->hours;
    $publicUrl = route('public.issuances.show', $issuance->public_id);
    $shareText = 'Confira minha badge "'.$issuance->badge->title.'" emitida pela ESAOABSP';
    $linkedinUrl = 'https://www.linkedin.com/sharing/share-offsite/?url='.urlencode($publicUrl);
    $whatsUrl = 'https://wa.me/?text='.urlencode($shareText.' '.$publicUrl);
    $xUrl = 'https://twitter.com/intent/tweet?text='.urlencode($shareText).'&url='.urlencode($publicUrl);
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data='.urlencode($publicUrl);
@endphp

<div class="verify-page">
    <div class="verify-wrap">
        <div class="verify-top">
            <div class="verify-brand">ESAOABSP Badges</div>
            <a class="verify-back" href="/">Voltar</a>
        </div>

        <div class="verify-grid">
            <div class="verify-card">
                <div class="verify-card-body" style="text-align:center;">
                    @if($issuance->badge->image_path)
                        <img
                            class="badge-image"
                            src="{{ asset('storage/'.$issuance->badge->image_path) }}"
                            alt="Badge {{ $issuance->badge->title }}"
                        >
                    @else
                        <div class="badge-fallback">Sem imagem</div>
                    @endif

                    <div class="pill" style="{{ $issuance->status === 'issued' ? '' : 'border-color: rgba(239,68,68,.25); background: rgba(239,68,68,.10); color:#991b1b;' }}">
                    {{ $issuance->status === 'issued' ? 'Verificada' : 'Revogada' }}
                    </div>

@if($issuance->status === 'revoked')
    <div style="margin:0 0 18px; padding:14px 16px; border-radius:14px; background:rgba(239,68,68,.10); border:1px solid rgba(239,68,68,.18); color:#991b1b;">
        <div style="font-weight:700; margin-bottom:6px;">Esta badge foi revogada.</div>
        <div><strong>Motivo:</strong> {{ $issuance->revocation_reason ?: 'Não informado.' }}</div>
        <div style="margin-top:4px;"><strong>Data da revogação:</strong> {{ optional($issuance->revoked_at)->format('d/m/Y H:i') ?? '-' }}</div>
    </div>
@endif

                    <div style="margin-top:18px;" class="detail-list">
                        <div class="detail-item">
                            <div class="detail-label">EMITIDA POR</div>
                            <div class="detail-value">Escola Superior de Advocacia — OABSP</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">EMITIDA PARA</div>
                            <div class="detail-value">{{ $issuance->recipient_name ?? '-' }}</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">E-MAIL</div>
                            <div class="detail-value">{{ $issuance->recipient_email }}</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">EMITIDA EM</div>
                            <div class="detail-value">{{ optional($issuance->issued_at)->format('d/m/Y H:i') ?? '-' }}</div>
                        </div>

@if($issuance->status === 'revoked')
    <div class="detail-item">
        <div class="detail-label">REVOGADA EM</div>
        <div class="detail-value">{{ optional($issuance->revoked_at)->format('d/m/Y H:i') ?? '-' }}</div>
    </div>
@endif

                        <div class="detail-item" style="border-bottom:none; padding-bottom:0;">
                            <div class="detail-label">ID PÚBLICO</div>
                            <div class="detail-value" style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; font-size:13px;">
                                {{ $issuance->public_id }}
                            </div>
                        </div>
                    </div>

<div class="action-grid">

<button type="button" class="action-btn" onclick="copyText('{{ $publicUrl }}', 'Link copiado!')">
<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
<path d="M3.9 12a5 5 0 015-5h3v2h-3a3 3 0 000 6h3v2h-3a5 5 0 01-5-5zm7-1h2v2h-2v-2zm4.1-4h-3v2h3a3 3 0 010 6h-3v2h3a5 5 0 000-10z"/>
</svg>
Copiar link
</button>

<button type="button" class="action-btn" onclick="copyText('{{ $issuance->public_id }}', 'ID público copiado!')">
<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
<path d="M16 1H4a2 2 0 00-2 2v12h2V3h12V1zm3 4H8a2 2 0 00-2 2v14a2 2 0 002 2h11a2 2 0 002-2V7a2 2 0 00-2-2zm0 16H8V7h11v14z"/>
</svg>
Copiar ID
</button>

<a class="action-btn" href="{{ $linkedinUrl }}" target="_blank">
<svg width="16" height="16" viewBox="0 0 24 24" fill="#0A66C2">
<path d="M20.45 20.45h-3.56v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.13 1.45-2.13 2.95v5.66H9.35V9h3.42v1.56h.05c.48-.9 1.66-1.85 3.41-1.85 3.64 0 4.31 2.39 4.31 5.5v6.24zM5.34 7.43a2.07 2.07 0 110-4.14 2.07 2.07 0 010 4.14zM7.12 20.45H3.56V9h3.56v11.45z"/>
</svg>
LinkedIn
</a>

<a class="action-btn" href="{{ $whatsUrl }}" target="_blank">
<svg width="16" height="16" viewBox="0 0 24 24" fill="#25D366">
<path d="M20.52 3.48A11.9 11.9 0 0012.04 0C5.42 0 .02 5.4.02 12c0 2.11.55 4.17 1.6 5.98L0 24l6.19-1.61A11.94 11.94 0 0012.04 24C18.66 24 24 18.6 24 12c0-3.19-1.24-6.19-3.48-8.52zM12.04 21.8c-1.81 0-3.57-.49-5.11-1.41l-.37-.22-3.67.95.98-3.57-.24-.37A9.74 9.74 0 012.3 12c0-5.38 4.36-9.74 9.74-9.74 2.6 0 5.04 1.01 6.87 2.85A9.66 9.66 0 0121.78 12c0 5.38-4.36 9.8-9.74 9.8z"/>
</svg>
WhatsApp
</a>

<a class="action-btn" href="{{ $xUrl }}" target="_blank">
<svg width="16" height="16" viewBox="0 0 24 24" fill="#000">
<path d="M18.244 2H21l-6.6 7.54L22 22h-6.828l-5.35-6.98L3.9 22H1.144l7.064-8.07L2 2h6.828l4.84 6.33L18.244 2z"/>
</svg>
Compartilhar
</a>

<a class="action-btn"
href="{{ route('public.issuances.pdf',$issuance->public_id) }}"
target="_blank">

Baixar PDF

</a>

</div>
                    <div class="qr-wrap">
                        <div class="detail-label" style="margin-bottom:10px;">QR CODE DE VERIFICAÇÃO</div>
                        <img src="{{ $qrUrl }}" alt="QR Code da badge">
                    </div>
                </div>
            </div>

            <div class="verify-card">
                <div class="verify-card-body">
                    <h1 class="verify-title">{{ $issuance->badge->title }}</h1>
                    <p class="verify-subtitle">
                        {{ $issuance->badge->description ?: 'Badge emitida e validada pela ESAOABSP.' }}
                    </p>

                    <div class="detail-list" style="margin-bottom:22px;">
                        <div class="detail-item">
                            <div class="detail-label">CARGA HORÁRIA TOTAL</div>
                            <div class="detail-value">{{ $totalHours }} hora(s)</div>
                        </div>

                        <div class="detail-item" style="border-bottom:none;">
                            <div class="detail-label">CÓDIGO DA BADGE</div>
                            <div class="detail-value">{{ $issuance->badge->code ?: '—' }}</div>
                        </div>
                    </div>

                    <div style="margin-bottom:20px;">
                        <h2 class="section-title">Skills</h2>
                        @if(!empty($skills))
                            <div class="tag-list">
                                @foreach($skills as $skill)
                                    <span class="tag">{{ $skill }}</span>
                                @endforeach
                            </div>
                        @else
                            <div style="color:rgba(15,23,42,.55);">Nenhuma skill informada.</div>
                        @endif
                    </div>

                    <div>
                        <h2 class="section-title">Cursos realizados</h2>
                        @if(!empty($courses))
                            <div class="course-list">
                                @foreach($courses as $course)
                                    <div class="course-item">
                                        {{ is_array($course) ? ($course['title'] ?? 'Curso') : $course }}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="color:rgba(15,23,42,.55);">Nenhum curso informado.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="verify-footer">
            Esta página comprova a autenticidade da badge emitida pela ESAOABSP.
        </div>
    </div>
</div>

<div id="flashCopy" class="flash-copy">Copiado!</div>

<script>
    function copyText(text, message = 'Copiado!') {
        navigator.clipboard.writeText(text).then(function () {
            const flash = document.getElementById('flashCopy');
            flash.textContent = message;
            flash.classList.add('show');
            setTimeout(() => flash.classList.remove('show'), 1800);
        });
    }
</script>
</body>
</html>
