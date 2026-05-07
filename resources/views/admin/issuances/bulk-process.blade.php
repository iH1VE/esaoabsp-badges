@extends('admin.layout')

@section('title', 'Processando CSV')
@section('subtitle', 'Acompanhe o envio das emissões em tempo real.')

@section('actions')
    <a class="btn ghost" href="{{ route('admin.issuances.bulk') }}">Voltar</a>
@endsection

@section('content')
<div class="card">
    <div class="card-hd">
        <div>
            <div class="section-title">Processamento em andamento</div>
            <div class="section-subtitle">
                {{ count($rows) }} registro(s) encontrado(s) no CSV.
            </div>
        </div>
    </div>

    <div style="padding:18px;">
        <div style="display:grid; gap:18px;">
            <div style="padding:18px; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <div style="font-size:14px; font-weight:700; color:#111827;">Progresso</div>
                    <div id="percent" style="font-size:14px; font-weight:700; color:#111827;">0%</div>
                </div>

                <div style="width:100%; height:18px; background:#e5e7eb; border-radius:999px; overflow:hidden;">
                    <div id="progress"
                         style="height:18px; width:0; background:#6366f1; border-radius:999px; transition:width .25s ease;"></div>
                </div>

                <div id="summary" class="muted" style="margin-top:10px;">
                    Iniciando processamento...
                </div>
            </div>

            <div style="padding:18px; border:1px solid rgba(15,23,42,.08); border-radius:16px; background:#fff;">
                <div style="font-size:14px; font-weight:700; color:#111827; margin-bottom:12px;">
                    Log de envio
                </div>

                <div id="log"
                     style="max-height:420px; overflow:auto; display:grid; gap:8px; padding-right:6px;">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const rows = @json($rows);
const total = rows.length;
let current = 0;
let sent = 0;
let errors = 0;
let duplicates = 0;

function appendLog(text, type = 'default') {
    const row = document.createElement('div');

    let bg = '#f8fafc';
    let color = '#334155';
    let border = '#e2e8f0';

    if (type === 'ok') {
        bg = 'rgba(22,163,74,.08)';
        color = '#166534';
        border = 'rgba(22,163,74,.18)';
    }

    if (type === 'erro') {
        bg = 'rgba(239,68,68,.08)';
        color = '#991b1b';
        border = 'rgba(239,68,68,.18)';
    }

    if (type === 'duplicado') {
        bg = 'rgba(245,158,11,.10)';
        color = '#92400e';
        border = 'rgba(245,158,11,.18)';
    }

    row.style.padding = '10px 12px';
    row.style.borderRadius = '12px';
    row.style.border = `1px solid ${border}`;
    row.style.background = bg;
    row.style.color = color;
    row.style.fontSize = '13px';
    row.textContent = text;

    document.getElementById('log').prepend(row);
}

function updateProgress() {
    const percent = total > 0 ? Math.round((current / total) * 100) : 100;
    document.getElementById('progress').style.width = percent + '%';
    document.getElementById('percent').textContent = percent + '%';
    document.getElementById('summary').textContent =
        `Processados: ${current}/${total} • Enviados: ${sent} • Duplicados: ${duplicates} • Erros: ${errors}`;
}

async function processNext() {
    if (current >= total) {
        updateProgress();
        appendLog('Processamento concluído.', 'ok');
        return;
    }

    const row = rows[current];

    try {
        const response = await fetch("{{ route('admin.issuances.bulk.process') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ row })
        });

        const data = await response.json();

        if (data.status === 'ok') {
            sent++;
            appendLog(`✔ ${data.name} — enviado com sucesso`, 'ok');
        } else if (data.status === 'duplicado') {
            duplicates++;
            appendLog(`⚠ ${data.name} — badge já emitida para este e-mail`, 'duplicado');
        } else {
            errors++;
            appendLog(`✖ ${data.name} — ${data.message ?? 'erro no envio'}`, 'erro');
        }
    } catch (e) {
        errors++;
        appendLog(`✖ ${row.recipient_name ?? 'Linha'} — falha inesperada`, 'erro');
    }

    current++;
    updateProgress();
    processNext();
}

updateProgress();
processNext();
</script>
@endsection
