@extends('admin.layout')

@section('title', 'Dashboard')
@section('subtitle', 'Visão geral do emissor ESAOABSP.')

@section('actions')
    <a class="btn primary" href="{{ route('admin.issuances.create') }}">Nova emissão</a>
@endsection

@section('content')
<div style="display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:16px; margin-bottom:24px;">
    <div class="card">
        <div style="padding:20px;">
            <div class="muted" style="font-size:12px; letter-spacing:.02em;">BADGES CADASTRADAS</div>
            <div style="font-size:34px; font-weight:800; margin-top:10px; color:#111827;">
                {{ $stats['badges_total'] }}
            </div>
        </div>
    </div>

    <div class="card">
        <div style="padding:20px;">
            <div class="muted" style="font-size:12px; letter-spacing:.02em;">EMISSÕES TOTAIS</div>
            <div style="font-size:34px; font-weight:800; margin-top:10px; color:#111827;">
                {{ $stats['issuances_total'] }}
            </div>
        </div>
    </div>

    <div class="card">
        <div style="padding:20px;">
            <div class="muted" style="font-size:12px; letter-spacing:.02em;">TRILHAS CADASTRADAS</div>
            <div style="font-size:34px; font-weight:800; margin-top:10px; color:#111827;">
                {{ $stats['trails_total'] }}
            </div>
        </div>
    </div>

    <div class="card">
        <div style="padding:20px;">
            <div class="muted" style="font-size:12px; letter-spacing:.02em;">EMISSÕES ATIVAS</div>
            <div style="font-size:34px; font-weight:800; margin-top:10px; color:#027a48;">
                {{ $stats['issuances_issued'] }}
            </div>
        </div>
    </div>

    <div class="card">
        <div style="padding:20px;">
            <div class="muted" style="font-size:12px; letter-spacing:.02em;">EMISSÕES REVOGADAS</div>
            <div style="font-size:34px; font-weight:800; margin-top:10px; color:#b42318;">
                {{ $stats['issuances_revoked'] }}
            </div>
        </div>
    </div>

    <div class="card">
        <div style="padding:20px;">
            <div class="muted" style="font-size:12px; letter-spacing:.02em;">EMISSÕES NO MÊS</div>
            <div style="font-size:34px; font-weight:800; margin-top:10px; color:#111827;">
                {{ $stats['issuances_this_month'] }}
            </div>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns: minmax(0, 1.5fr) minmax(320px, .9fr); gap:20px; align-items:start;">
    <div class="card">
        <div class="card-hd">
            <div>
                <div style="font-size:18px; font-weight:700; color:#111827;">Emissões por mês</div>
                <div class="muted" style="margin-top:4px;">Distribuição anual</div>
            </div>
        </div>

        <div style="padding:20px;">
            <canvas id="issuancesChart"></canvas>
        </div>
    </div>

    <div style="display:grid; gap:20px;">
        <div class="card">
            <div class="card-hd">
                <div>
                    <div style="font-size:18px; font-weight:700; color:#111827;">Ações rápidas</div>
                    <div class="muted" style="margin-top:4px;">Atalhos mais usados</div>
                </div>
            </div>

            <div style="padding:18px; display:grid; gap:10px;">
                <a class="btn primary" href="{{ route('admin.badges.create') }}">Nova badge</a>
                <a class="btn primary" href="{{ route('admin.issuances.create') }}">Nova emissão</a>
                <a class="btn primary" href="{{ route('admin.trails.create') }}">Nova trilha</a>
            </div>
        </div>

        <div class="card">
            <div class="card-hd">
                <div>
                    <div style="font-size:18px; font-weight:700; color:#111827;">Gerenciamento</div>
                    <div class="muted" style="margin-top:4px;">Acesse os módulos do sistema</div>
                </div>
            </div>

            <div style="padding:18px; display:grid; gap:10px;">
                <a class="btn secondary" href="{{ route('admin.badges.index') }}">Gerenciar badges</a>
                <a class="btn secondary" href="{{ route('admin.issuances.index') }}">Gerenciar emissões</a>
                <a class="btn secondary" href="{{ route('admin.trails.index') }}">Gerenciar trilhas</a>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-top:24px;">
    <div class="card-hd">
        <div>
            <div style="font-size:18px; font-weight:700; color:#111827;">Últimas emissões</div>
            <div class="muted" style="margin-top:4px;">Registros mais recentes do sistema</div>
        </div>
    </div>

    <div style="padding:0 18px 18px;">
        <div style="overflow:hidden; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
            <table class="table" style="margin:0;">
                <thead>
                <tr>
                    <th>Destinatário</th>
                    <th>Badge</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
                </thead>
                <tbody>
                @forelse($recentIssuances as $issuance)
                    <tr>
                        <td>
                            <div style="font-weight:700; color:#111827;">
                                {{ $issuance->recipient_name ?? '—' }}
                            </div>
                            <div style="font-size:12px; color:#64748b; margin-top:3px;">
                                {{ $issuance->recipient_email }}
                            </div>
                        </td>

                        <td>
                            <div style="font-weight:600; color:#111827;">
                                {{ $issuance->badge?->title ?? '—' }}
                            </div>
                        </td>

                        <td>
                            @if($issuance->status === 'issued')
                                <span class="badge-pill on">Emitida</span>
                            @else
                                <span class="badge-pill off">Revogada</span>
                            @endif
                        </td>

                        <td style="color:#475569;">
                            {{ optional($issuance->issued_at)->format('d/m/Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:28px 16px; text-align:center; color:#64748b;">
                            Nenhuma emissão encontrada.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('issuancesChart');

if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartMonths) !!},
            datasets: [{
                label: 'Emissões',
                data: {!! json_encode($chartTotals) !!},
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.10)',
                fill: true,
                tension: 0.35,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
}
</script>
@endsection
