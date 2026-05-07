@extends('admin.layout')

@section('title', 'Trilhas')
@section('subtitle', 'Gerencie trilhas de aprendizagem e certificação.')

@section('actions')
    <a class="btn primary" href="{{ route('admin.trails.create') }}">Nova trilha</a>
@endsection

@section('content')
<div class="card">
    <div class="card-hd">
        <div>
            <div style="font-size:16px; font-weight:700; color:#0f172a;">Lista de trilhas</div>
            <div class="muted">{{ $trails->total() }} registro(s)</div>
        </div>
    </div>

    <div style="padding:0 18px 18px;">
        <div style="overflow:hidden; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
            <table class="table" style="margin:0;">
                <thead>
                <tr>
                    <th>Título</th>
                    <th>Badges vinculadas</th>
                    <th>Badge final</th>
                    <th>Status</th>
                    <th style="width:280px;">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse($trails as $trail)
                    <tr>
                        <td>
                            <div style="font-weight:700;">{{ $trail->title }}</div>
                            @if($trail->description)
                                <div style="font-size:13px; color:#64748b; margin-top:4px;">
                                    {{ \Illuminate\Support\Str::limit($trail->description, 90) }}
                                </div>
                            @endif
                        </td>

                        <td>
                            @if($trail->badges->count())
                                <div style="display:flex; flex-wrap:wrap; gap:8px;">
                                    @foreach($trail->badges as $badge)
                                        <span class="badge-pill off">{{ $badge->title }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span style="color:#64748b;">Nenhuma badge</span>
                            @endif
                        </td>

                        <td>
                            @if($trail->awardBadge)
                                <span class="badge-pill on">{{ $trail->awardBadge->title }}</span>
                            @else
                                <span style="color:#64748b;">Nenhuma</span>
                            @endif
                        </td>

                        <td>
                            @if($trail->is_active)
                                <span class="badge-pill on">Ativa</span>
                            @else
                                <span class="badge-pill off">Inativa</span>
                            @endif
                        </td>

                        <td>
                            <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                                <a class="btn secondary" href="{{ route('admin.trails.show', $trail) }}">Ver progresso</a>
                                <a class="btn secondary" href="{{ route('public.trails.show', $trail) }}" target="_blank">Página pública</a>
                                <a class="btn secondary" href="{{ route('admin.trails.edit', $trail) }}">Editar</a>

                                <form method="POST"
                                      action="{{ route('admin.trails.destroy', $trail) }}"
                                      onsubmit="return confirm('Tem certeza que deseja excluir a trilha {{ addslashes($trail->title) }}?');"
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn danger">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:28px 16px; text-align:center; color:#64748b;">
                            Nenhuma trilha cadastrada ainda.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top:14px; color:var(--muted); font-size:12px;">
    {{ $trails->links() }}
</div>
@endsection
