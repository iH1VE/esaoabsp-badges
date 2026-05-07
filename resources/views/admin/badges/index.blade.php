@extends('admin.layout')

@section('title', 'Badges')
@section('subtitle', 'Cadastre e gerencie as badges que a ESAOABSP pode emitir.')

@section('actions')
    <a class="btn primary" href="{{ route('admin.badges.create') }}">Nova badge</a>
@endsection

@section('content')
<div class="card">
    <div class="card-hd">
        <div class="section-header">

    <div>
        <div class="section-title">
            Badges cadastradas
        </div>

        <div class="section-subtitle">
            {{ $badges->count() }} registros
        </div>
    </div>

</div>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>Título</th>
            <th>Imagem</th>
            <th>Código</th>
            <th>Horas</th>
            <th>Status</th>
            <th>Criada</th>
            <th style="width: 180px;">Ações</th>
        </tr>
        </thead>
        <tbody>
        @forelse($badges as $badge)
            <tr>
                <td style="font-weight:600">{{ $badge->title }}</td>

                <td>
                    @if($badge->image_path)
                        <img
                            src="{{ asset('storage/'.$badge->image_path) }}"
                            alt="Badge {{ $badge->title }}"
                            style="width:42px;height:42px;object-fit:cover;border-radius:10px;border:1px solid rgba(255,255,255,.14);"
                        >
                    @else
                        —
                    @endif
                </td>

                <td>{{ $badge->code ?? '—' }}</td>

                <td>{{ $badge->hours }}</td>

                <td>
                    @if($badge->is_active)
                        <span class="badge-pill on">Ativa</span>
                    @else
                        <span class="badge-pill off">Inativa</span>
                    @endif
                </td>

                <td>{{ $badge->created_at?->format('d/m/Y H:i') }}</td>

                <td>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <a class="btn secondary" href="{{ route('admin.badges.edit', $badge) }}">Editar</a>

                        <form method="POST" action="{{ route('admin.badges.destroy', $badge) }}"
                              onsubmit="return confirm('Tem certeza que deseja excluir a badge {{ addslashes($badge->title) }}?');"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn ghost" style="border-color: rgba(239,68,68,.35); color:#b91c1c;">
                                Excluir
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="color:var(--muted); padding:18px 16px;">
                    Nenhuma badge cadastrada ainda.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:14px; color:var(--muted); font-size:12px;">
    {{ $badges->links() }}
</div>
@endsection
