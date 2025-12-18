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
    <div id="app" data-view="{{ $view }}" style="min-height: 100vh;">
        <noscript>
            <div style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
                <h1 style="font-size: 1.875rem; font-weight: 600; color: #0f172a; margin-bottom: 1rem;">Bestil lækker mad hurtigt</h1>
                <p style="color: #475569;">Aktivér JavaScript for at bruge siden.</p>
            </div>
        </noscript>

        <section class="mx-auto max-w-5xl space-y-6 p-6" aria-live="polite" style="max-width: 1280px; margin: 0 auto; padding: 1.5rem; min-height: 100vh;">
            @if ($view === 'frontend')
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200" style="background-color: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                    <div class="mb-4 flex items-center gap-3" style="margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                        <h1 class="text-2xl font-semibold" style="font-size: 1.5rem; font-weight: 600; color: #0f172a; margin: 0;">Bestil lækker mad hurtigt</h1>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700" style="background-color: #ecfdf5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-weight: 600; font-size: 0.875rem;">Appetized</span>
                    </div>
                    <p class="text-slate-700" style="color: #334155; margin-bottom: 0.75rem;">Frontend indlæses… hvis du ikke ser menuen, mangler JavaScript eller assets.</p>
                    <div class="mt-4 rounded-lg bg-slate-50 p-4" style="margin-top: 1rem; background-color: #f8fafc; padding: 1rem; border-radius: 0.5rem;">
                        <h2 style="font-weight: 600; color: #0f172a; margin-bottom: 0.5rem;">Menu</h2>
                        <p style="color: #64748b; font-size: 0.875rem;">Henter menu fra API...</p>
                    </div>
                    <ul class="mt-3 list-disc pl-5 text-sm text-slate-600" style="margin-top: 0.75rem; padding-left: 1.25rem; font-size: 0.875rem; color: #475569;">
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
