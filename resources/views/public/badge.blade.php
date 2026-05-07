<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $issuance->badge->title }} — Autenticação | ESAOABSP</title>

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $issuance->badge->title }} — ESAOABSP" />
    <meta property="og:description" content="Página de autenticidade da badge emitida pela ESAOABSP." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ request()->fullUrl() }}" />

    @if(!empty($issuance->badge->image_path))
        <meta property="og:image" content="{{ asset('storage/'.$issuance->badge->image_path) }}" />
    @endif

    <link rel="stylesheet" href="/assets/public.css">
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <div class="brand">
            <div class="brand-dot"></div>
            <strong>ESAOABSP Badges</strong>
        </div>

        <div class="topbar-actions">
            <button class="btn ghost" onclick="window.print()">Imprimir</button>
        </div>
    </div>
</header>

<main class="page">
    <section class="hero card">
        <div class="hero-left">
            <div class="badge-image">
                @if(!empty($issuance->badge->image_path))
                    <img
                        src="{{ asset('storage/'.$issuance->badge->image_path) }}"
                        alt="Badge {{ $issuance->badge->title }}"
                    >
                @else
                    <div class="badge-placeholder">
                        <div class="badge-ring"></div>
                        <div class="badge-title">{{ $issuance->badge->title }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="hero-mid">
            <div class="meta-label">EMITIDA PARA:</div>
            <h1 class="recipient">{{ $issuance->recipient_name ?? '—' }}</h1>

            <p class="desc">{{ $issuance->badge->description ?? '' }}</p>

            <div class="verified">
                <div class="check">✓</div>
                <div>
                    <div class="verified-title">{{ $issuance->is_revoked ? 'Revogada' : 'Verificada' }}</div>
                    <div class="verified-sub">
                        @if($issuance->is_revoked)
                            Revogada em {{ optional($issuance->revoked_at)->format('d/m/Y') ?? '-' }}
                        @else
                            Emitida em {{ optional($issuance->issued_at)->format('d/m/Y') ?? '-' }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="hero-buttons">
                <button class="btn" type="button" onclick="openVerifyModal()">Revalidar</button>
                <button class="btn ghost" type="button"
                        onclick="navigator.clipboard.writeText(window.location.href)">
                    Copiar link
                </button>
            </div>
        </div>

        <div class="hero-right">
            <div class="meta-label">EMITIDA POR:</div>
            <div class="issuer">
                <div class="issuer-logo">ESA</div>
                <div>
                    <div class="issuer-name">Escola Superior de Advocacia — OABSP</div>
                    <div class="issuer-date">
                        {{ optional($issuance->issued_at)->format('d/m/Y') ?? '-' }}
                    </div>
                </div>
            </div>

            <div class="meta-label" style="margin-top:18px;">COMPARTILHAR:</div>
            <div class="share">
                @php $url = urlencode(request()->fullUrl()); @endphp
                <a class="share-btn" target="_blank" rel="noopener"
                   href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}">in</a>
                <a class="share-btn" target="_blank" rel="noopener"
                   href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}">f</a>
                <a class="share-btn" target="_blank" rel="noopener"
                   href="https://twitter.com/intent/tweet?url={{ $url }}">x</a>
                <a class="share-btn" target="_blank" rel="noopener"
                   href="mailto:?subject=Badge%20ESAOABSP&body={{ $url }}">@</a>
            </div>
        </div>
    </section>

    <section class="card grid">
        <div class="grid-row">
            <div class="grid-label">CRITÉRIOS</div>
            <div class="grid-value">
                {!! nl2br(e($issuance->criteria ?? ($issuance->badge->criteria ?? '—'))) !!}
            </div>
        </div>

        <div class="grid-row">
            <div class="grid-label">ATRIBUTOS</div>
            <div class="grid-value pills">
                <span class="pill">
                    Carga horária: {{ ($issuance->total_hours ?? $issuance->badge->hours ?? 0) }}h
                </span>
                @if(!empty($issuance->badge->code))
                    <span class="pill">Código: {{ $issuance->badge->code }}</span>
                @endif
            </div>
        </div>

        <div class="grid-row">
            <div class="grid-label">CURSOS</div>
            <div class="grid-value">
                @php
                    $courses = $issuance->courses ?? [];
                @endphp
                @if(!empty($courses))
                    <ul class="list">
                        @foreach($courses as $c)
                            <li>{{ $c }}</li>
                        @endforeach
                    </ul>
                @else
                    —
                @endif
            </div>
        </div>

        <div class="grid-row">
            <div class="grid-label">SKILLS</div>
            <div class="grid-value pills">
                @php
                    $badgeSkills = $issuance->badge->skills ?? [];
                    if (is_string($badgeSkills)) {
                        $badgeSkills = json_decode($badgeSkills, true) ?: [];
                    }
                    $skills = $issuance->skills ?: $badgeSkills;
                @endphp

                @if(!empty($skills))
                    @foreach($skills as $s)
                        <span class="pill">{{ $s }}</span>
                    @endforeach
                @else
                    —
                @endif
            </div>
        </div>
    </section>

{{-- Modal de verificação (Revalidar) --}}
<div id="verifyModal" class="modal" aria-hidden="true">
  <div class="modal-backdrop" onclick="closeVerifyModal()"></div>

  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="verifyTitle">
    <div class="modal-header">
      <div class="modal-title">
        <div class="modal-icon">✓</div>
        <div>
          <div class="modal-h1" id="verifyTitle">Verificada</div>
          <div class="modal-sub">{{ $issuance->badge->title }}</div>
          <div class="modal-desc">A badge foi verificada e as informações abaixo são válidas.</div>
        </div>
      </div>
      <button class="modal-close" type="button" onclick="closeVerifyModal()" aria-label="Fechar">×</button>
    </div>

    <div class="modal-list">
      <div class="modal-row">
        <div class="modal-label">EMITIDA POR:</div>
        <div class="modal-value">Escola Superior de Advocacia — OABSP</div>
        <div class="modal-ok">✓</div>
      </div>

      <div class="modal-row">
        <div class="modal-label">EMITIDA PARA:</div>
        <div class="modal-value">{{ $issuance->recipient_name ?? '-' }}</div>
        <div class="modal-ok">✓</div>
      </div>

      <div class="modal-row">
        <div class="modal-label">STATUS:</div>
        <div class="modal-value">{{ $issuance->is_revoked ? 'Revogada' : 'Ativa' }}</div>
        <div class="modal-ok">✓</div>
      </div>

      <div class="modal-row">
        <div class="modal-label">EMITIDA EM:</div>
        <div class="modal-value">{{ optional($issuance->issued_at)->format('d/m/Y') ?? '-' }}</div>
        <div class="modal-ok">✓</div>
      </div>

      @if($issuance->is_revoked)
      <div class="modal-row">
        <div class="modal-label">REVOGADA EM:</div>
        <div class="modal-value">{{ optional($issuance->revoked_at)->format('d/m/Y') ?? '-' }}</div>
        <div class="modal-ok">✓</div>
      </div>
      @endif

      <div class="modal-row">
        <div class="modal-label">ID PÚBLICO:</div>
        <div class="modal-value" style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;">
          {{ $issuance->public_id }}
        </div>
        <div class="modal-ok">✓</div>
      </div>
    </div>

    <div class="modal-actions">
      <button class="btn modal-btn" type="button" onclick="closeVerifyModal()">Concluir</button>
    </div>
  </div>
</div>

<script>
  function openVerifyModal() {
    const m = document.getElementById('verifyModal');
    m.classList.add('is-open');
    m.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-open');
  }

  function closeVerifyModal() {
    const m = document.getElementById('verifyModal');
    m.classList.remove('is-open');
    m.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('modal-open');
  }

  // ESC fecha
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeVerifyModal();
  });
</script>

    <footer class="footer">
        <small>
            Esta página comprova a autenticidade da badge.
            ID público: <strong>{{ $issuance->public_id }}</strong>
        </small>
    </footer>
</main>
</body>
</html>
