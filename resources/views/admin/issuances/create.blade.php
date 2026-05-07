@extends('admin.layout')

@section('title', 'Nova emissão')
@section('subtitle', 'Emita uma badge para um participante.')

@section('actions')
<a class="btn ghost" href="{{ route('admin.issuances.index') }}">Voltar</a>
@endsection

@section('content')
<div class="card">

<div class="card-hd">
<div>
<div style="font-size:16px;font-weight:700;">Emitir badge</div>
<div class="muted">Preencha os dados do destinatário.</div>
</div>
</div>

<form method="POST" action="{{ route('admin.issuances.store') }}" style="padding:18px;">
@csrf

<div style="display:grid;gap:16px;">

<div>
<label class="label">Badge *</label>
<select class="input" name="badge_id" required>
<option value="">Selecione</option>
@foreach($badges as $badge)
<option value="{{ $badge->id }}">{{ $badge->title }}</option>
@endforeach
</select>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

<div>
<label class="label">Nome do destinatário *</label>
<input class="input" type="text" name="recipient_name" required>
</div>

<div>
<label class="label">Email *</label>
<input class="input" type="email" name="recipient_email" required>
</div>

</div>

<div>
<label class="label">Data de emissão</label>
<input class="input" type="datetime-local" name="issued_at">
<div class="help">Se vazio, usa a data atual.</div>
</div>

<div>
<label class="label">Cursos realizados (1 por linha)</label>
<textarea class="input" name="courses" rows="5"></textarea>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

<div>
<label class="label">Carga horária total</label>
<input class="input" type="number" name="total_hours">
</div>

<div>
<label class="label">Skills</label>
<textarea class="input" name="skills" rows="4"></textarea>
</div>

</div>

<div style="display:flex;justify-content:flex-end;gap:10px;">
<a class="btn ghost" href="{{ route('admin.issuances.index') }}">Cancelar</a>
<button class="btn primary" type="submit">Emitir badge</button>
</div>

</div>

</form>
</div>
@endsection
