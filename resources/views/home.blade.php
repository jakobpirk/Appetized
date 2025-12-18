@extends('layouts.base')

@section('content')
<section class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
    <div class="space-y-6">
        <p class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-800">
            Helt ny start · Laravel + Vite
        </p>
        <div class="space-y-4">
            <h1 class="text-4xl font-semibold leading-tight text-slate-900 sm:text-5xl">
                Byg din menu på få minutter – klar til bestilling med det samme.
            </h1>
            <p class="text-lg text-slate-700">
                Den offentlige side er strømlinet, API'et er nystartet med Sanctum, og adminværktøjet er klar til at tilføje retter
                direkte fra browseren.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-4">
            <a href="#menu" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-base font-semibold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700">Se dagens menu</a>
            <a href="/admin" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-5 py-3 text-base font-semibold text-slate-800 transition hover:border-emerald-200 hover:text-emerald-700">Gå til admin</a>
        </div>
        <dl class="grid grid-cols-2 gap-6 text-sm text-slate-700 sm:grid-cols-4">
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                <dt class="text-xs uppercase tracking-wide text-slate-500">API</dt>
                <dd class="mt-1 text-lg font-semibold">Laravel Sanctum</dd>
            </div>
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                <dt class="text-xs uppercase tracking-wide text-slate-500">Frontend</dt>
                <dd class="mt-1 text-lg font-semibold">Tailwind + Vite</dd>
            </div>
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                <dt class="text-xs uppercase tracking-wide text-slate-500">Test</dt>
                <dd class="mt-1 text-lg font-semibold">PHPUnit</dd>
            </div>
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                <dt class="text-xs uppercase tracking-wide text-slate-500">Status</dt>
                <dd class="mt-1 text-lg font-semibold text-emerald-700">Genopbygget</dd>
            </div>
        </dl>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-emerald-700">Live data</p>
                <p class="text-base font-semibold text-slate-900">Menuoversigt</p>
                <p class="text-sm text-slate-600">Hentes direkte fra API'et.</p>
            </div>
            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Automatisk</span>
        </div>
        <div class="mt-6 space-y-4" data-menu-list>
            <p class="text-sm text-slate-600">Menuen indlæses...</p>
        </div>
    </div>
</section>

<section id="menu" class="mt-14 space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Signaturretter</h2>
            <p class="text-slate-600">Alle retter kan styres fra adminpanelet.</p>
        </div>
        <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-700">Opdateres live</span>
    </div>
    <div class="grid gap-4 sm:grid-cols-2" data-menu-grid>
        <div class="rounded-xl border border-dashed border-slate-200 bg-white p-4 text-sm text-slate-500">Vent på at API'et svarer...</div>
    </div>
</section>
@endsection
