@extends('admin.layout')

@section('title', 'Nova badge')
@section('subtitle', 'Defina os dados da nova badge.')

@section('actions')
<a class="btn ghost" href="{{ route('admin.badges.index') }}">Voltar</a>
@endsection

@section('content')
<div class="card">

<div class="card-hd">
<div>
<div style="font-size:16px;font-weight:700;">Cadastro</div>
<div class="muted">Preencha as informações da badge.</div>
</div>
</div>

<form method="POST" action="{{ route('admin.badges.store') }}" enctype="multipart/form-data" style="padding:18px;">
@csrf

<div style="display:grid;gap:16px;">

<div>
<label class="label">Título *</label>
<input class="input" type="text" name="title" required>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

<div>
<label class="label">Código (opcional)</label>
<input class="input" type="text" name="code">
</div>

<div>
<label class="label">Carga horária *</label>
<input class="input" type="number" name="hours" min="0" required>
</div>

</div>

<div>
<label class="label">Descrição</label>
<textarea class="input" name="description" rows="4"></textarea>
</div>

<div>
<label class="label">Skills (1 por linha)</label>
<textarea class="input" name="skills" rows="5"></textarea>
<div class="help">Essas skills aparecem na página pública da badge.</div>
</div>

<div>
<label class="label">Imagem da badge</label>
<input class="input" type="file" name="image" accept="image/png,image/jpeg,image/webp">
<div class="help">Recomendado: 512x512 PNG transparente.</div>
</div>

<div style="display:flex;align-items:center;gap:8px;">
<input type="checkbox" name="is_active" value="1" checked>
<span>Badge ativa</span>
</div>

<div style="display:flex;justify-content:flex-end;gap:10px;">
<a class="btn ghost" href="{{ route('admin.badges.index') }}">Cancelar</a>
<button class="btn primary" type="submit">Criar badge</button>
</div>

</div>

</form>
</div>
@endsection
