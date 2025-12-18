import './bootstrap';
import '../css/app.css';

const formatPrice = (price) => {
    const value = Number(price ?? 0);
    return new Intl.NumberFormat('da-DK', { style: 'currency', currency: 'DKK' }).format(value);
};

const renderMenuItems = (items) => {
    const listContainers = document.querySelectorAll('[data-menu-list]');
    const gridContainers = document.querySelectorAll('[data-menu-grid]');

    listContainers.forEach((container) => {
        container.innerHTML = '';
        if (!items.length) {
            container.innerHTML = '<p class="text-sm text-slate-600">Ingen retter er oprettet endnu.</p>';
            return;
        }

        items.forEach((item) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'flex items-start justify-between rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm';
            wrapper.innerHTML = `
                <div>
                    <p class="text-base font-semibold text-slate-900">${item.name}</p>
                    <p class="text-sm text-slate-600">${item.description ?? 'Ingen beskrivelse endnu'}</p>
                </div>
                <div class="flex items-center gap-2 text-right">
                    <span class="rounded-full bg-${item.available ? 'emerald' : 'slate'}-100 px-3 py-1 text-xs font-semibold text-${item.available ? 'emerald' : 'slate'}-800">${item.available ? 'Aktiv' : 'Skjult'}</span>
                    <span class="text-base font-semibold text-slate-900">${formatPrice(item.price)}</span>
                </div>`;
            container.appendChild(wrapper);
        });
    });

    gridContainers.forEach((grid) => {
        grid.innerHTML = '';
        if (!items.length) {
            grid.innerHTML = '<div class="rounded-xl border border-dashed border-slate-200 bg-white p-4 text-sm text-slate-500">Tilføj den første ret fra admin.</div>';
            return;
        }
        items.forEach((item) => {
            const card = document.createElement('article');
            card.className = 'rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md';
            card.innerHTML = `
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">${item.name}</h3>
                    <span class="rounded-full bg-${item.available ? 'emerald' : 'slate'}-100 px-3 py-1 text-xs font-semibold text-${item.available ? 'emerald' : 'slate'}-800">${item.available ? 'Tilgængelig' : 'Ikke tilgængelig'}</span>
                </div>
                <p class="mt-2 text-sm text-slate-600">${item.description ?? 'Ingen beskrivelse tilføjet endnu.'}</p>
                <p class="mt-4 text-base font-semibold text-slate-900">${formatPrice(item.price)}</p>
            `;
            grid.appendChild(card);
        });
    });
};

const fetchMenuItems = async () => {
    try {
        const { data } = await window.axios.get('/api/menu-items');
        renderMenuItems(data);
    } catch (error) {
        console.error('Kunne ikke hente menu', error);
        document.querySelectorAll('[data-menu-list]').forEach((container) => {
            container.innerHTML = '<p class="text-sm text-red-600">Fejl ved indlæsning af menuen.</p>';
        });
    }
};

const setupForm = () => {
    const form = document.querySelector('[data-menu-form]');
    if (!form) return;

    const status = form.querySelector('[data-form-status]');
    const refreshButton = document.querySelector('[data-refresh]');

    const setStatus = (message, variant = 'muted') => {
        const styles = {
            muted: 'text-slate-500',
            success: 'text-emerald-700',
            error: 'text-red-600',
        };
        status.textContent = message;
        status.className = `text-sm ${styles[variant] ?? styles.muted}`;
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        const payload = {
            name: formData.get('name'),
            description: formData.get('description'),
            price: formData.get('price'),
            available: formData.get('available') === 'on',
        };

        try {
            await window.axios.post('/api/menu-items', payload);
            form.reset();
            setStatus('Retten blev gemt.', 'success');
            await fetchMenuItems();
        } catch (error) {
            console.error('Gem fejlede', error);
            setStatus('Kunne ikke gemme retten.', 'error');
        }
    });

    refreshButton?.addEventListener('click', fetchMenuItems);
};

window.addEventListener('DOMContentLoaded', () => {
    fetchMenuItems();
    setupForm();
});
