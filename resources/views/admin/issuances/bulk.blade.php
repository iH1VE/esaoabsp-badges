@extends('admin.layout')

@section('title', 'Emissão em massa')
@section('subtitle', 'Importe um arquivo CSV para emitir badges em lote.')

@section('actions')
    <a class="btn ghost" href="{{ route('admin.issuances.index') }}">Voltar</a>
@endsection

@section('content')
<div class="card">
    <div class="card-hd">
        <div>
            <div class="section-title">Importação por CSV</div>
            <div class="section-subtitle">
                Baixe o modelo, preencha os dados e envie o arquivo para processar as emissões.
            </div>
        </div>
    </div>

    <div style="padding:18px;">
        <div style="display:grid; gap:18px;">
            <div style="padding:16px; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
                <div style="font-size:14px; font-weight:700; color:#111827; margin-bottom:8px;">
                    Modelo do arquivo
                </div>
                <div class="muted" style="margin-bottom:12px;">
                    O CSV deve conter as colunas:
                    <strong>badge_id</strong>,
                    <strong>recipient_name</strong>,
                    <strong>recipient_email</strong>,
                    <strong>issued_at</strong>.
                </div>

                <a class="btn secondary" href="{{ route('admin.issuances.bulk.template') }}">
                    Baixar modelo CSV
                </a>
            </div>

            <form method="POST"
                  action="{{ route('admin.issuances.bulk.upload') }}"
                  enctype="multipart/form-data"
                  style="padding:18px; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
                @csrf

                <div style="display:grid; gap:14px;">
                    <div>
                        <label class="label">Arquivo CSV</label>
                        <input class="input" type="file" name="csv" accept=".csv,text/csv" required>
                        <div class="help">Selecione o arquivo CSV preenchido com as emissões.</div>
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:10px;">
                        <a class="btn ghost" href="{{ route('admin.issuances.index') }}">Cancelar</a>
                        <button class="btn primary" type="submit">Processar arquivo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
