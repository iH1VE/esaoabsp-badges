@extends('admin.layout')

@section('title', 'Nova trilha')
@section('subtitle', 'Agrupe badges em uma trilha de aprendizagem.')

@section('actions')
    <a class="btn ghost" href="{{ route('admin.trails.index') }}">Voltar</a>
@endsection

@section('content')
<div class="card">
    <div class="card-hd">
        <div>
            <div style="font-size:16px; font-weight:700;">Cadastro</div>
            <div class="muted">Defina a trilha, as badges obrigatórias e a badge final opcional.</div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.trails.store') }}" style="padding:18px;">
        @csrf

        <div style="display:grid; gap:16px;">
            <div>
                <label class="label">Título *</label>
                <input class="input" type="text" name="title" required>
            </div>

            <div>
                <label class="label">Descrição</label>
                <textarea class="input" name="description" rows="4"></textarea>
            </div>

            <div>
                <label class="label">Badge final da trilha (opcional)</label>
                <select class="input" name="award_badge_id">
                    <option value="">Nenhuma</option>
                    @foreach($badges as $badge)
                        <option value="{{ $badge->id }}">
                            {{ $badge->title }}{{ $badge->code ? ' ('.$badge->code.')' : '' }}
                        </option>
                    @endforeach
                </select>
                <div class="help">Quando o participante concluir todas as badges da trilha, essa badge será emitida automaticamente.</div>
            </div>

            <div>
                <label class="label">Badges obrigatórias da trilha</label>
                <select class="input" name="badges[]" multiple size="10">
                    @foreach($badges as $badge)
                        <option value="{{ $badge->id }}">
                            {{ $badge->title }}{{ $badge->code ? ' ('.$badge->code.')' : '' }}
                        </option>
                    @endforeach
                </select>
                <div class="help">Segure Ctrl para selecionar várias badges.</div>
            </div>

            <div style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="is_active" value="1" checked>
                <span>Trilha ativa</span>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <a class="btn ghost" href="{{ route('admin.trails.index') }}">Cancelar</a>
                <button class="btn primary" type="submit">Criar trilha</button>
            </div>
        </div>
    </form>
</div>
@endsection
