<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ESAOABSP Badges</title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body>
<div class="container container-split">
    <aside class="sidebar">
        <div>
            <div class="brand">
                <small>Issuer</small>
                <strong>ESAOABSP Badges</strong>
            </div>

            <nav class="nav">
                <a href="{{ route('admin.home') }}"
                   class="{{ request()->routeIs('admin.home') ? 'active' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('admin.badges.index') }}"
                   class="{{ request()->routeIs('admin.badges.*') ? 'active' : '' }}">
                    Badges
                </a>

                <a href="{{ route('admin.issuances.index') }}"
                   class="{{ request()->routeIs('admin.issuances.*') ? 'active' : '' }}">
                    Emissões
                </a>

                <a href="{{ route('admin.trails.index') }}"
                   class="{{ request()->routeIs('admin.trails.*') ? 'active' : '' }}">
                    Trilhas
                </a>
            </nav>
        </div>

        <div class="userbox">
            Logado como: <strong>{{ auth()->user()->name }}</strong>

            <form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
                @csrf
                <button class="btn ghost" type="submit">Sair</button>
            </form>
        </div>
    </aside>

    <main class="main main-light">
        <div class="main-inner">
            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">@yield('title')</h1>
                    <p class="page-subtitle">@yield('subtitle')</p>
                </div>

                <div class="page-header-actions">
                    @yield('actions')
                </div>
            </div>

            @if(session('status'))
                <div class="alert alert-light">{{ session('status') }}</div>
            @endif

            @yield('content')
        </div>
    </main>
</div>
</body>
</html>
