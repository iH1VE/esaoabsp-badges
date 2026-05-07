@extends('admin.layout')

@section('title', 'Progresso da trilha')
@section('subtitle', 'Consulte o andamento de um participante nesta trilha.')

@section('actions')
    <a class="btn ghost" href="{{ route('admin.trails.index') }}">Voltar</a>
@endsection

@section('content')
<div class="card" style="margin-bottom:18px;">
    <div class="card-hd">
        <div>
            <div style="font-size:18px; font-weight:700;">{{ $trail->title }}</div>
            <div class="muted">{{ $trail->description ?: 'Sem descrição cadastrada.' }}</div>
        </div>
    </div>

    <div style="padding:18px;">
        <form method="GET" action="{{ route('admin.trails.show', $trail) }}" style="display:grid; grid-template-columns: 1fr auto; gap:12px; align-items:end;">
            <div>
                <label class="label">Pesquisar progresso por e-mail</label>
                <input
                    class="input"
                    type="email"
                    name="email"
                    value="{{ request('email') }}"
                    placeholder="exemplo@dominio.com"
                >
            </div>

            <div style="display:flex; gap:10px;">
                <button class="btn primary" type="submit">Consultar</button>
                <a class="btn ghost" href="{{ route('admin.trails.show', $trail) }}">Limpar</a>
            </div>
        </form>
    </div>
</div>

@if($progress)
    <div class="card" style="margin-bottom:18px;">
        <div class="card-hd">
            <div>
                <div style="font-size:16px; font-weight:700;">Resultado</div>
                <div class="muted">{{ $progress['email'] }}</div>
            </div>
        </div>

        <div style="padding:18px;">
            <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:12px; margin-bottom:18px;">
                <div style="padding:16px; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
                    <div class="muted" style="font-size:12px;">BADGES EXIGIDAS</div>
                    <div style="font-size:24px; font-weight:800; margin-top:4px;">{{ $progress['required_count'] }}</div>
                </div>

                <div style="padding:16px; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
                    <div class="muted" style="font-size:12px;">CONCLUÍDAS</div>
                    <div style="font-size:24px; font-weight:800; margin-top:4px;">{{ $progress['completed_count'] }}</div>
                </div>

                <div style="padding:16px; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
                    <div class="muted" style="font-size:12px;">PENDENTES</div>
                    <div style="font-size:24px; font-weight:800; margin-top:4px;">{{ $progress['missing_count'] }}</div>
                </div>

                <div style="padding:16px; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
                    <div class="muted" style="font-size:12px;">PROGRESSO</div>
                    <div style="font-size:24px; font-weight:800; margin-top:4px;">{{ $progress['completed_percentage'] }}%</div>
                </div>
            </div>

            <div style="margin-bottom:16px;">
                @if($progress['is_completed'])
                    <span class="badge-pill on" style="font-size:13px;">Trilha concluída</span>
                @else
                    <span class="badge-pill off" style="font-size:13px;">Trilha em andamento</span>
                @endif
            </div>

            <div style="overflow:hidden; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
                <table class="table" style="margin:0;">
                    <thead>
                    <tr>
                        <th>Badge</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($trail->badges as $badge)
                        @php
                            $done = in_array($badge->id, $progress['completed_badge_ids']);
                        @endphp
                        <tr>
                            <td style="font-weight:600;">{{ $badge->title }}</td>
                            <td>
                                @if($done)
                                    <span class="badge-pill on">Concluída</span>
                                @else
                                    <span class="badge-pill off">Pendente</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<div class="card">
    <div class="card-hd">
        <div>
            <div style="font-size:16px; font-weight:700;">Badges da trilha</div>
            <div class="muted">{{ $trail->badges->count() }} badge(s)</div>
        </div>
    </div>

    <div style="padding:18px;">
        @if($trail->badges->count())
            <div style="display:flex; flex-wrap:wrap; gap:10px;">
                @foreach($trail->badges as $badge)
                    <span class="badge-pill off">{{ $badge->title }}</span>
                @endforeach
            </div>
        @else
            <div style="color:#64748b;">Nenhuma badge vinculada.</div>
        @endif
    </div>
</div>
@endsection
