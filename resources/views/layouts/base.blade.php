<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @php
        $viteReady = file_exists(public_path('build/manifest.json'))
            || file_exists(public_path('hot'))
            || file_exists(storage_path('framework/vite.hot'));
    @endphp
    @if ($viteReady)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900">
    <header class="border-b border-slate-200 bg-white/70 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="/" class="flex items-center gap-2 font-semibold text-slate-900">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">🍽️</span>
                <span>Appetized</span>
            </a>
            <nav class="flex items-center gap-4 text-sm font-medium text-slate-700">
                <a class="hover:text-emerald-700" href="/">Forside</a>
                <a class="hover:text-emerald-700" href="/admin">Admin</a>
                <a class="hidden sm:inline-flex items-center gap-1 rounded-full bg-emerald-600 px-3 py-1 text-white shadow-sm" href="#menu">Se menu</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-10">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white/60 backdrop-blur">
        <div class="mx-auto flex max-w-6xl flex-col items-start justify-between gap-2 px-6 py-6 text-sm text-slate-600 sm:flex-row sm:items-center">
            <p>© {{ date('Y') }} Appetized. Bygget fra bunden med Laravel.</p>
            <div class="flex items-center gap-4">
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-emerald-700">Sanctum API</span>
                <span class="rounded-full bg-slate-100 px-3 py-1">Vite + Tailwind</span>
            </div>
        </div>
    </footer>
</body>
</html>
