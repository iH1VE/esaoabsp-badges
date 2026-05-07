<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $trail->title }} • ESAOABSP Trilhas</title>
    <link rel="stylesheet" href="/assets/admin.css">
    <style>
        .public-wrap{
            max-width:1100px;
            margin:0 auto;
            padding:32px 16px 48px;
        }

        .public-top{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:16px;
            margin-bottom:22px;
        }

        .public-brand{
            font-size:20px;
            font-weight:800;
            color:#111827;
        }

        .public-back{
            text-decoration:none;
        }

        .hero{
            background:#fff;
            border:1px solid rgba(15,23,42,.08);
            border-radius:20px;
            box-shadow:0 10px 30px rgba(15,23,42,.08);
            padding:24px;
            margin-bottom:20px;
        }

        .hero-title{
            margin:0;
            font-size:30px;
            line-height:1.15;
            font-weight:800;
            color:#111827;
        }

        .hero-subtitle{
            margin:10px 0 0;
            font-size:15px;
            color:#6b7280;
            max-width:800px;
        }

        .award-box{
            margin-top:18px;
            display:flex;
            align-items:center;
            gap:14px;
            padding:14px;
            border:1px solid rgba(99,102,241,.16);
            background:#eef2ff;
            border-radius:16px;
        }

        .award-box img{
            width:56px;
            height:56px;
            object-fit:cover;
            border-radius:14px;
            border:1px solid rgba(15,23,42,.08);
            background:#fff;
        }

        .lookup-card{
            background:#fff;
            border:1px solid rgba(15,23,42,.08);
            border-radius:20px;
            box-shadow:0 10px 30px rgba(15,23,42,.08);
            padding:20px;
            margin-bottom:20px;
        }

        .lookup-form{
            display:grid;
            grid-template-columns:1fr auto auto;
            gap:12px;
            align-items:end;
        }

        .stats-grid{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:16px;
            margin-bottom:20px;
        }

        .stat-card{
            background:#fff;
            border:1px solid rgba(15,23,42,.08);
            border-radius:18px;
            padding:18px;
            box-shadow:0 10px 30px rgba(15,23,42,.08);
        }

        .stat-label{
            font-size:12px;
            color:#6b7280;
        }

        .stat-value{
            margin-top:8px;
            font-size:28px;
            font-weight:800;
            color:#111827;
        }

        .trail-grid{
            display:grid;
            grid-template-columns:repeat(2, minmax(0,1fr));
            gap:18px;
        }

        .badge-card{
            background:#fff;
            border:1px solid rgba(15,23,42,.08);
            border-radius:20px;
            box-shadow:0 10px 30px rgba(15,23,42,.08);
            padding:18px;
            display:grid;
            grid-template-columns:96px 1fr;
            gap:16px;
            align-items:start;
        }

        .badge-thumb{
            width:96px;
            height:96px;
            object-fit:cover;
            border-radius:18px;
            border:1px solid rgba(15,23,42,.08);
            background:#fff;
        }

        .badge-fallback{
            width:96px;
            height:96px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:18px;
            border:1px dashed rgba(15,23,42,.14);
            color:#94a3b8;
            font-size:12px;
            background:#fff;
        }

        .badge-title{
            margin:0;
            font-size:18px;
            font-weight:700;
            color:#111827;
        }

        .badge-desc{
            margin:8px 0 12px;
            font-size:14px;
            color:#6b7280;
        }

        .meta{
            display:grid;
            gap:8px;
            font-size:13px;
            color:#475569;
        }

        .status-pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:6px 10px;
            border-radius:999px;
            font-size:12px;
            font-weight:700;
            width:max-content;
            margin-bottom:10px;
        }

        .status-pill.on{
            background:rgba(22,163,74,.10);
            color:#166534;
            border:1px solid rgba(22,163,74,.22);
        }

        .status-pill.off{
            background:#f8fafc;
            color:#475467;
            border:1px solid #d0d5dd;
        }

        .empty-state{
            background:#fff;
            border:1px solid rgba(15,23,42,.08);
            border-radius:20px;
            box-shadow:0 10px 30px rgba(15,23,42,.08);
            padding:24px;
            color:#6b7280;
        }

        @media (max-width: 900px){
            .lookup-form{
                grid-template-columns:1fr;
            }

            .stats-grid{
                grid-template-columns:repeat(2,1fr);
            }

            .trail-grid{
                grid-template-columns:1fr;
            }
        }

        @media (max-width: 640px){
            .stats-grid{
                grid-template-columns:1fr;
            }

            .badge-card{
                grid-template-columns:1fr;
            }
        }
    </style>
</head>
<body style="background:#f5f7fb;">
<div class="public-wrap">
    <div class="public-top">
        <div class="public-brand">ESAOABSP Trilhas</div>
        <a class="btn ghost public-back" href="/">Voltar</a>
    </div>

    <div class="hero">
        <h1 class="hero-title">{{ $trail->title }}</h1>
        <p class="hero-subtitle">
            {{ $trail->description ?: 'Trilha pública de aprendizagem da ESAOABSP.' }}
        </p>

        @if($trail->awardBadge)
            <div class="award-box">
                @if($trail->awardBadge->image_path)
                    <img src="{{ asset('storage/'.$trail->awardBadge->image_path) }}" alt="Badge final">
                @else
                    <div class="badge-fallback" style="width:56px;height:56px;">Sem imagem</div>
                @endif

                <div>
                    <div style="font-size:12px; color:#6b7280;">BADGE FINAL DA TRILHA</div>
                    <div style="font-size:16px; font-weight:700; color:#111827;">
                        {{ $trail->awardBadge->title }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="lookup-card">
        <form method="GET" action="{{ route('public.trails.show', $trail) }}" class="lookup-form">
            <div>
                <label class="label">Consultar progresso por e-mail</label>
                <input
                    class="input"
                    type="email"
                    name="email"
                    value="{{ request('email') }}"
                    placeholder="exemplo@dominio.com"
                >
            </div>

            <button class="btn primary" type="submit">Consultar</button>
            <a class="btn ghost" href="{{ route('public.trails.show', $trail) }}">Limpar</a>
        </form>
    </div>

    @if($progress)
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">BADGES EXIGIDAS</div>
                <div class="stat-value">{{ $progress['required_count'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">CONQUISTADAS</div>
                <div class="stat-value">{{ $progress['completed_count'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">PENDENTES</div>
                <div class="stat-value">{{ $progress['missing_count'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">PROGRESSO</div>
                <div class="stat-value">{{ $progress['completed_percentage'] }}%</div>
            </div>
        </div>

        <div style="margin-bottom:16px;">
            @if($progress['is_completed'])
                <span class="status-pill on">Trilha concluída</span>
            @else
                <span class="status-pill off">Trilha em andamento</span>
            @endif
        </div>
    @endif

    @if($progress)
        <div class="trail-grid">
            @foreach($progress['items'] as $item)
                @php $badge = $item['badge']; @endphp
                <div class="badge-card">
                    <div>
                        @if($badge->image_path)
                            <img class="badge-thumb" src="{{ asset('storage/'.$badge->image_path) }}" alt="{{ $badge->title }}">
                        @else
                            <div class="badge-fallback">Sem imagem</div>
                        @endif
                    </div>

                    <div>
                        @if($item['earned'])
                            <span class="status-pill on">Conquistada</span>
                        @else
                            <span class="status-pill off">Pendente</span>
                        @endif

                        <h2 class="badge-title">{{ $badge->title }}</h2>
                        <p class="badge-desc">{{ $badge->description ?: 'Badge integrante da trilha.' }}</p>

                        <div class="meta">
                            <div><strong>Código:</strong> {{ $badge->code ?: '—' }}</div>
                            <div><strong>Carga horária:</strong> {{ $badge->hours }} hora(s)</div>
                            <div>
                                <strong>Data da conquista:</strong>
                                {{ $item['issued_at'] ? $item['issued_at']->format('d/m/Y H:i') : 'Ainda não conquistada' }}
                            </div>

                            @if($item['public_id'])
                                <div>
                                    <strong>Ver badge:</strong>
                                    <a class="link" href="{{ route('public.issuances.show', $item['public_id']) }}" target="_blank">
                                        abrir verificação pública
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            Informe um e-mail para consultar as badges já conquistadas dentro desta trilha.
        </div>
    @endif
</div>
</body>
</html>
