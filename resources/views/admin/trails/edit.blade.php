@extends('admin.layout')

@section('title', 'Editar trilha')
@section('subtitle', 'Atualize a trilha e as badges vinculadas.')

@section('actions')
    <a class="btn ghost" href="{{ route('admin.trails.index') }}">Voltar</a>
@endsection

@section('content')
<div class="card">
    <div class="card-hd">
        <div>
            <div style="font-size:16px; font-weight:700;">Edição</div>
            <div class="muted">Atualize os dados da trilha abaixo.</div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.trails.update', $trail) }}" style="padding:18px;">
        @csrf
        @method('PUT')

        <div style="display:grid; gap:16px;">
            <div>
                <label class="label">Título *</label>
                <input class="input" type="text" name="title" value="{{ old('title', $trail->title) }}" required>
            </div>

            <div>
                <label class="label">Descrição</label>
                <textarea class="input" name="description" rows="4">{{ old('description', $trail->description) }}</textarea>
            </div>

            <div>
                <label class="label">Badge final da trilha (opcional)</label>
                <select class="input" name="award_badge_id">
                    <option value="">Nenhuma</option>
                    @foreach($badges as $badge)
                        <option value="{{ $badge->id }}" {{ (string) old('award_badge_id', $trail->award_badge_id) === (string) $badge->id ? 'selected' : '' }}>
                            {{ $badge->title }}{{ $badge->code ? ' ('.$badge->code.')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="label">Badges da trilha</label>
                <select class="input" name="badges[]" multiple size="10">
                    @foreach($badges as $badge)
                        <option value="{{ $badge->id }}" {{ in_array($badge->id, old('badges', $selectedBadges)) ? 'selected' : '' }}>
                            {{ $badge->title }}{{ $badge->code ? ' ('.$badge->code.')' : '' }}
                        </option>
                    @endforeach
                </select>
                <div class="help">Segure Ctrl para selecionar várias badges.</div>
            </div>

            <div style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $trail->is_active) ? 'checked' : '' }}>
                <span>Trilha ativa</span>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <a class="btn ghost" href="{{ route('admin.trails.index') }}">Cancelar</a>
                <button class="btn primary" type="submit">Salvar alterações</button>
            </div>
        </div>
    </form>
</div>
@endsection
