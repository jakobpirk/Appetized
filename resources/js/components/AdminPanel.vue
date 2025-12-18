<script setup>
import { onMounted, reactive, ref } from 'vue';
import axios from 'axios';

const items = ref([]);
const loading = ref(true);
const saving = ref(false);
const message = ref('');
const error = ref('');

const form = reactive({
    name: '',
    description: '',
    price: '',
    available: true,
});

function resetForm() {
    form.name = '';
    form.description = '';
    form.price = '';
    form.available = true;
}

async function loadItems() {
    loading.value = true;
    error.value = '';

    try {
        const { data } = await axios.get('/api/menu-items');
        items.value = data.map((item) => ({ ...item }));
    } catch (err) {
        error.value = 'Kunne ikke hente listen.';
        console.error(err);
    } finally {
        loading.value = false;
    }
}

async function createItem() {
    saving.value = true;
    message.value = '';
    error.value = '';

    try {
        const payload = { ...form, price: Number(form.price) };
        const { data } = await axios.post('/api/menu-items', payload);
        items.value.unshift(data);
        message.value = 'Retten er gemt.';
        resetForm();
    } catch (err) {
        error.value = 'Gemningen fejlede. Tjek felterne.';
        console.error(err);
    } finally {
        saving.value = false;
    }
}

async function updateItem(item) {
    saving.value = true;
    message.value = '';
    error.value = '';

    try {
        const payload = {
            name: item.name,
            description: item.description,
            price: Number(item.price),
            available: Boolean(item.available),
        };

        const { data } = await axios.put(`/api/menu-items/${item.id}`, payload);
        Object.assign(item, data);
        message.value = 'Opdateret.';
    } catch (err) {
        error.value = 'Kunne ikke opdatere posten.';
        console.error(err);
        await loadItems();
    } finally {
        saving.value = false;
    }
}

async function deleteItem(id) {
    saving.value = true;
    message.value = '';
    error.value = '';

    try {
        await axios.delete(`/api/menu-items/${id}`);
        items.value = items.value.filter((item) => item.id !== id);
        message.value = 'Slettet.';
    } catch (err) {
        error.value = 'Kunne ikke slette posten.';
        console.error(err);
    } finally {
        saving.value = false;
    }
}

onMounted(loadItems);
</script>

<template>
    <main class="mx-auto flex max-w-6xl flex-col gap-10 px-6 py-10">
        <header class="flex flex-col gap-3 rounded-2xl bg-emerald-700 p-8 text-white shadow-sm">
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full bg-white/20 px-3 py-1 text-sm font-semibold">Admin</span>
                <span class="text-sm text-emerald-50">Styr menuen på /api/menu-items</span>
            </div>
            <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                <div class="space-y-2">
                    <h1 class="text-3xl font-semibold">Menu styring</h1>
                    <p class="max-w-2xl text-emerald-50">Opret nye retter, opdater priser eller luk for bestilling.</p>
                </div>
                <a
                    href="/"
                    class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-emerald-800 shadow hover:bg-emerald-50"
                >
                    Tilbage til forsiden
                </a>
            </div>
        </header>

        <section class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Ny ret</h2>
                    <span class="text-xs text-slate-500">Felter er påkrævet</span>
                </div>
                <form class="space-y-4" @submit.prevent="createItem">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-800" for="name">Navn</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                        />
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-800" for="description">Beskrivelse</label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                        ></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-800" for="price">Pris (DKK)</label>
                        <input
                            id="price"
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            min="0"
                            required
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                        />
                    </div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                        <input v-model="form.available" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-emerald-600" />
                        Aktiv på menuen
                    </label>
                    <button
                        type="submit"
                        :disabled="saving"
                        class="w-full rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-700 disabled:opacity-60"
                    >
                        {{ saving ? 'Arbejder...' : 'Gem' }}
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                    <div class="space-y-1">
                        <h2 class="text-lg font-semibold text-slate-900">Menu items</h2>
                        <p class="text-sm text-slate-600">Redigér direkte i listen.</p>
                    </div>
                    <button
                        type="button"
                        class="rounded-lg px-4 py-2 text-sm font-semibold text-emerald-700 ring-1 ring-emerald-200 hover:bg-emerald-50"
                        @click="loadItems"
                    >
                        Genindlæs
                    </button>
                </div>

                <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                    <div v-if="loading" class="p-4 text-slate-600">Henter data...</div>
                    <div v-else-if="error" class="p-4 text-rose-700">{{ error }}</div>
                    <div v-else-if="items.length === 0" class="p-4 text-slate-600">Ingen retter endnu.</div>
                    <div v-else class="divide-y divide-slate-100">
                        <article v-for="item in items" :key="item.id" class="grid gap-4 py-4 md:grid-cols-6 md:items-center">
                            <div class="md:col-span-2 space-y-2">
                                <input
                                    v-model="item.name"
                                    type="text"
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                                />
                                <textarea
                                    v-model="item.description"
                                    rows="2"
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                                ></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-600">Pris</label>
                                <input
                                    v-model.number="item.price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-600">Status</label>
                                <label class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                                    <input
                                        v-model="item.available"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-slate-300 text-emerald-600"
                                    />
                                    På menuen
                                </label>
                            </div>
                            <div class="flex flex-wrap gap-2 md:justify-end">
                                <button
                                    type="button"
                                    :disabled="saving"
                                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-700 disabled:opacity-60"
                                    @click="updateItem(item)"
                                >
                                    Opdater
                                </button>
                                <button
                                    type="button"
                                    :disabled="saving"
                                    class="rounded-lg px-4 py-2 text-sm font-semibold text-rose-700 ring-1 ring-rose-200 hover:bg-rose-50 disabled:opacity-60"
                                    @click="deleteItem(item.id)"
                                >
                                    Slet
                                </button>
                            </div>
                        </article>
                    </div>
                </div>

                <div v-if="message" class="mt-3 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ message }}</div>
                <div v-if="error && !loading" class="mt-3 rounded-lg bg-rose-50 px-4 py-3 text-sm text-rose-800">{{ error }}</div>
            </div>
        </section>
    </main>
</template>
