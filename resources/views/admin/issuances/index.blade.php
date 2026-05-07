@extends('admin.layout')

@section('title', 'Emissões')
@section('subtitle', 'Emita badges manualmente e acompanhe os registros gerados.')

@section('actions')
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a class="btn secondary" href="{{ route('admin.issuances.bulk') }}">Emissão em massa</a>
        <a class="btn primary" href="{{ route('admin.issuances.create') }}">Nova emissão</a>
    </div>
@endsection

@section('content')
<div class="card" style="margin-bottom:18px;">
    <div class="card-hd" style="padding-bottom:10px;">
        <div>
            <div style="font-size:16px; font-weight:700; color:#0f172a;">Filtros</div>
            <div class="muted">Refine a busca por participante, badge ou data de emissão.</div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.issuances.index') }}" style="padding:0 18px 18px;">
        <div style="display:grid; grid-template-columns: 2fr 1fr 1fr auto auto; gap:12px; align-items:end;">
            <div>
                <label class="label">Pesquisar</label>
                <input class="input" type="text" name="q" value="{{ request('q') }}" placeholder="Nome, e-mail ou ID público">
            </div>

            <div>
                <label class="label">Badge</label>
                <select class="input" name="badge_id">
                    <option value="">Todas</option>
                    @foreach($badges as $badge)
                        <option value="{{ $badge->id }}" {{ (string) request('badge_id') === (string) $badge->id ? 'selected' : '' }}>
                            {{ $badge->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="label">Data de emissão</label>
                <input class="input" type="date" name="issued_date" value="{{ request('issued_date') }}">
            </div>

            <div>
                <button class="btn primary" type="submit" style="width:100%;">Filtrar</button>
            </div>

            <div>
                <a class="btn ghost" href="{{ route('admin.issuances.index') }}" style="width:100%; text-align:center;">Limpar</a>
            </div>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-hd">
        <div>
            <div style="font-size:16px; font-weight:700; color:#0f172a;">Lista de emissões</div>
            <div class="muted">{{ $issuances->total() }} registro(s)</div>
        </div>
    </div>

    <div style="padding:0 18px 18px;">
        <div style="overflow:hidden; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
            <table class="table" style="margin:0;">
                <thead>
                <tr>
                    <th>Destinatário</th>
                    <th>E-mail</th>
                    <th>Badge</th>
                    <th>Status</th>
                    <th>Emitida em</th>
                    <th>ID público</th>
                    <th style="width:220px;">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse($issuances as $issuance)
                    <tr>
                        <td>
                            <div style="font-weight:700; color:#0f172a;">
                                {{ $issuance->recipient_name ?? '—' }}
                            </div>
                        </td>

                        <td style="color:#475569;">
                            {{ $issuance->recipient_email }}
                        </td>

                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                @if($issuance->badge?->image_path)
                                    <img
                                        src="{{ asset('storage/'.$issuance->badge->image_path) }}"
                                        alt="Badge {{ $issuance->badge?->title }}"
                                        style="width:34px; height:34px; object-fit:cover; border-radius:10px; border:1px solid rgba(15,23,42,.08); background:#fff;"
                                    >
                                @endif
                                <span style="font-weight:600;">{{ $issuance->badge?->title ?? '—' }}</span>
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
                            {{ optional($issuance->issued_at)->format('d/m/Y H:i') }}
                        </td>

                        <td>
                            <span style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; font-size:12px; color:#334155;">
                                {{ \Illuminate\Support\Str::limit($issuance->public_id, 22) }}
                            </span>
                        </td>

                        <td>
                            <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                                <a
                                    class="btn secondary"
                                    href="{{ route('public.issuances.show', $issuance->public_id) }}"
                                    target="_blank"
                                    rel="noopener"
                                    style="padding:8px 12px; font-size:13px;"
                                >
                                    Visualizar
                                </a>

                                @if($issuance->status === 'issued')
                                    <a
                                        class="btn danger"
                                        href="{{ route('admin.issuances.revoke.form', $issuance) }}"
                                        style="padding:8px 12px; font-size:13px;"
                                    >
                                        Revogar
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding:28px 16px; text-align:center; color:#64748b;">
                            Nenhuma emissão encontrada com os filtros atuais.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top:14px; color:var(--muted); font-size:12px;">
    {{ $issuances->links() }}
</div>
@endsection
