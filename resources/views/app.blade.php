<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @php
        $viteAssetsPresent = file_exists(public_path('build/manifest.json')) ||
            file_exists(public_path('hot')) ||
            file_exists(storage_path('framework/vite.hot'));
    @endphp

    @if ($viteAssetsPresent)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div id="app" data-view="{{ $view }}">
        <noscript>Aktivér JavaScript for at bruge siden.</noscript>

        <section class="mx-auto max-w-5xl space-y-6 p-6" aria-live="polite">
            @if ($view === 'frontend')
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="mb-4 flex items-center gap-3">
                        <h1 class="text-2xl font-semibold">Bestil lækker mad hurtigt</h1>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700">Appetized</span>
                    </div>
                    <p class="text-slate-700">Frontend indlæses… hvis du ikke ser menuen, mangler JavaScript eller assets.</p>
                    <ul class="mt-3 list-disc pl-5 text-sm text-slate-600">
                        <li>Menukortet vises automatisk, når Vue er bundet til siden.</li>
                        <li>Du kan altid genindlæse for at prøve igen.</li>
                    </ul>
                </div>
            @else
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="mb-4 flex items-center gap-3">
                        <h1 class="text-2xl font-semibold">Menu styring</h1>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700">Admin</span>
                    </div>
                    <p class="text-slate-700">Adminpanelet initialiseres… Hvis du ikke ser formularerne, er assets ikke tilgængelige.</p>
                    <ul class="mt-3 list-disc pl-5 text-sm text-slate-600">
                        <li>Kontroller at Vite-kørslen er aktiv.</li>
                        <li>Genindlæs siden for at hente data fra /api/menu-items.</li>
                    </ul>
                </div>
            @endif
        </section>

        @unless ($viteAssetsPresent)
            <div class="mx-auto max-w-4xl rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                Assets til frontend mangler. Kør <code>npm install</code> efterfulgt af <code>npm run dev</code> eller
                <code>npm run build</code>, og genindlæs derefter siden for at se {{ $view === 'admin' ? 'adminpanelet' : 'forsiden' }}.
            </div>
        @endunless
    </div>
</body>
</html>
