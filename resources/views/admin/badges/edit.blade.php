@extends('admin.layout')

@section('title', 'Editar badge')
@section('subtitle', 'Atualize os dados da badge cadastrada.')

@section('actions')
    <a class="btn ghost" href="{{ route('admin.badges.index') }}">Voltar</a>
@endsection

@section('content')
<div class="card">
    <div class="card-hd">
        <div>
            <div style="font-size:16px; font-weight:700; color:#0f172a;">Edição</div>
            <div class="muted">Atualize os campos da badge abaixo.</div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.badges.update', $badge) }}" enctype="multipart/form-data" style="padding:18px;">
        @csrf
        @method('PUT')

        <div style="display:grid; gap:16px;">
            <div>
                <label class="label">Título *</label>
                <input class="input" type="text" name="title" value="{{ old('title', $badge->title) }}" required>
                @error('title') <div class="help" style="color:var(--danger)">{{ $message }}</div> @enderror
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                <div>
                    <label class="label">Código (opcional)</label>
                    <input class="input" type="text" name="code" value="{{ old('code', $badge->code) }}" placeholder="Ex: ESA-DP-INTRO-01">
                    @error('code') <div class="help" style="color:var(--danger)">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="label">Carga horária (horas) *</label>
                    <input class="input" type="number" name="hours" value="{{ old('hours', $badge->hours) }}" min="0" required>
                    @error('hours') <div class="help" style="color:var(--danger)">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label class="label">Descrição</label>
                <textarea class="input" name="description" rows="5" style="resize:vertical;">{{ old('description', $badge->description) }}</textarea>
                @error('description') <div class="help" style="color:var(--danger)">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="label">Skills (1 por linha)</label>
                <textarea class="input" name="skills" rows="5" style="resize:vertical;">{{ old('skills', is_array($badge->skills) ? implode("\n", $badge->skills) : '') }}</textarea>
                <div class="help">Essas skills aparecem na página pública da badge emitida.</div>
                @error('skills') <div class="help" style="color:var(--danger)">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="label">Imagem da badge (PNG/JPG/WebP)</label>
                <input class="input" type="file" name="image" accept="image/png,image/jpeg,image/webp" style="padding:10px;">
                <div class="help">Recomendado: quadrada (ex: 512x512) e fundo transparente.</div>
                @error('image') <div class="help" style="color:var(--danger)">{{ $message }}</div> @enderror
            </div>

            @if($badge->image_path)
                <div style="padding:16px; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
                    <div style="font-size:14px; font-weight:700; color:#0f172a; margin-bottom:12px;">Imagem atual</div>

                    <div style="display:flex; align-items:center; gap:18px; flex-wrap:wrap;">
                        <img
                            src="{{ asset('storage/'.$badge->image_path) }}"
                            alt="Badge {{ $badge->title }}"
                            style="width:86px; height:86px; object-fit:cover; border-radius:18px; border:1px solid rgba(15,23,42,.08); background:#fff;"
                        >

                        <label style="display:flex; align-items:center; gap:10px; font-size:14px; color:#334155;">
                            <input type="checkbox" name="remove_image" value="1">
                            Remover imagem atual
                        </label>
                    </div>
                </div>
            @endif

            <div style="display:flex; align-items:center; gap:10px; padding-top:4px;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $badge->is_active) ? 'checked' : '' }}>
                <span style="font-size:14px; color:#334155;">Badge ativa</span>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px; padding-top:8px;">
                <a class="btn ghost" href="{{ route('admin.badges.index') }}">Cancelar</a>
                <button class="btn primary" type="submit">Salvar alterações</button>
            </div>
        </div>
    </form>
</div>
@endsection
