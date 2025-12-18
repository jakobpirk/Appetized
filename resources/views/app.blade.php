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

        @unless ($viteAssetsPresent)
            <div class="mx-auto max-w-4xl rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                Assets til frontend mangler. Kør <code>npm install</code> efterfulgt af <code>npm run dev</code> eller
                <code>npm run build</code>, og genindlæs derefter siden for at se {{ $view === 'admin' ? 'adminpanelet' : 'forsiden' }}.
            </div>
        @endunless
    </div>
</body>
</html>
