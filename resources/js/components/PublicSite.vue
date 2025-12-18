<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';

const items = ref([]);
const loading = ref(true);
const error = ref('');

const formatter = new Intl.NumberFormat('da-DK', {
    style: 'currency',
    currency: 'DKK',
    minimumFractionDigits: 2,
});

async function loadItems() {
    loading.value = true;
    error.value = '';

    try {
        const { data } = await axios.get('/api/menu-items');
        items.value = data;
    } catch (err) {
        error.value = 'Kunne ikke hente menuen lige nu.';
        console.error(err);
    } finally {
        loading.value = false;
    }
}

onMounted(loadItems);
</script>

<template>
    <main class="mx-auto flex max-w-5xl flex-col gap-12 px-6 py-10">
        <header class="flex flex-col gap-4 rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700">Appetized</span>
                <span class="text-sm text-slate-600">Laravel + Vue demo</span>
            </div>
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div class="space-y-2">
                    <h1 class="text-3xl font-semibold text-slate-900">Bestil lækker mad hurtigt</h1>
                    <p class="max-w-2xl text-slate-600">
                        Backend eksponerer API-endpoints, og Vue-renderede komponenter viser menuen.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a
                        href="/admin"
                        class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-700"
                    >
                        Gå til admin
                    </a>
                    <button
                        type="button"
                        class="rounded-lg px-4 py-2 text-sm font-semibold text-emerald-700 ring-1 ring-emerald-200 hover:bg-emerald-50"
                        @click="loadItems"
                    >
                        Opdater menu
                    </button>
                </div>
            </div>
        </header>

        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-slate-900">Menu</h2>
                <span class="text-sm text-slate-600">Data hentes fra /api/menu-items</span>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div v-if="loading" class="text-slate-600">Henter menu...</div>
                <div v-else-if="error" class="text-rose-700">{{ error }}</div>
                <div v-else-if="items.length === 0" class="text-slate-600">Ingen retter er oprettet endnu.</div>
                <div v-else class="grid gap-4 md:grid-cols-2">
                    <article
                        v-for="item in items"
                        :key="item.id"
                        class="flex flex-col gap-3 rounded-xl border border-slate-100 bg-slate-50 p-4"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ item.name }}</h3>
                                <p class="text-sm text-slate-600">{{ item.description }}</p>
                            </div>
                            <span
                                class="rounded-full px-3 py-1 text-xs font-semibold"
                                :class="item.available ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'"
                            >
                                {{ item.available ? 'Tilgængelig' : 'Ikke på lager' }}
                            </span>
                        </div>
                        <div class="text-lg font-semibold text-emerald-700">
                            {{ formatter.format(Number(item.price)) }}
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </main>
</template>
