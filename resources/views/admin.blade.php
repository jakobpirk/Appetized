@extends('layouts.base')

@section('content')
<section class="space-y-8">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-emerald-700">Adminpanel</p>
            <h1 class="text-3xl font-semibold text-slate-900">Administrer menuen i realtid</h1>
            <p class="text-slate-600">Tilføj nye retter, opdater priser og slå retter til eller fra.</p>
        </div>
        <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm font-semibold text-emerald-800">Startet fra bunden</span>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr_1.1fr]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Opret ny ret</h2>
            <p class="text-sm text-slate-600">Alle felter valideres i Laravel.</p>

            <form class="mt-6 space-y-4" data-menu-form>
                <div>
                    <label class="block text-sm font-semibold text-slate-800" for="name">Navn</label>
                    <input id="name" name="name" type="text" required class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="Eksempel: Cremet pastaret">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-800" for="description">Beskrivelse</label>
                    <textarea id="description" name="description" rows="3" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="Kort beskrivelse af retten"></textarea>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-800" for="price">Pris</label>
                        <input id="price" name="price" type="number" step="0.01" min="0" required class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 focus:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="125.00">
                    </div>
                    <div class="flex items-center gap-3 pt-6">
                        <input id="available" name="available" type="checkbox" checked class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <label for="available" class="text-sm font-semibold text-slate-800">Tilgængelig nu</label>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Gem ret</button>
                    <p class="text-sm text-slate-500" data-form-status>Ingen ændringer gemt endnu.</p>
                </div>
            </form>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Aktuelle retter</h2>
                    <p class="text-sm text-slate-600">Data fra /api/menu-items</p>
                </div>
                <button data-refresh type="button" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-800 transition hover:border-emerald-200 hover:text-emerald-700">Genindlæs</button>
            </div>
            <div class="mt-4 space-y-3" data-menu-list>
                <p class="text-sm text-slate-600">Henter retter...</p>
            </div>
        </div>
    </div>
</section>
@endsection
