@extends('admin.layout')

@section('title', 'Revogar emissão')
@section('subtitle', 'Informe o motivo da revogação desta badge.')

@section('actions')
    <a class="btn ghost" href="{{ route('admin.issuances.index') }}">Voltar</a>
@endsection

@section('content')
<div class="card">
    <div class="card-hd">
        <div>
            <div style="font-size:16px; font-weight:700;">Confirmação de revogação</div>
            <div class="muted">Essa ação altera o status da emissão para revogada.</div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.issuances.revoke', $issuance) }}" style="padding:18px;">
        @csrf

        <div style="display:grid; gap:16px;">
            <div style="padding:16px; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
                <div style="font-size:14px; color:#64748b; margin-bottom:8px;">Destinatário</div>
                <div style="font-weight:700;">{{ $issuance->recipient_name }}</div>
                <div style="color:#475569; margin-top:4px;">{{ $issuance->recipient_email }}</div>
                <div style="margin-top:10px;">
                    <span class="badge-pill off">{{ $issuance->badge?->title }}</span>
                </div>
            </div>

            <div>
                <label class="label">Motivo da revogação *</label>
                <textarea class="input" name="revocation_reason" rows="5" required placeholder="Ex: emissão realizada indevidamente, dados incorretos, cancelamento do curso..."></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <a class="btn ghost" href="{{ route('admin.issuances.index') }}">Cancelar</a>
                <button class="btn danger" type="submit">Confirmar revogação</button>
            </div>
        </div>
    </form>
</div>
@endsection
