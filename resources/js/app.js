import './bootstrap';
import { createApp } from 'vue';
import AdminPanel from './components/AdminPanel.vue';
import PublicSite from './components/PublicSite.vue';

const root = document.getElementById('app');

if (root) {
    try {
        const view =
            root.dataset.view || (window.location.pathname.startsWith('/admin') ? 'admin' : 'frontend');
        const AppComponent = view === 'admin' ? AdminPanel : PublicSite;

        // Gem fallback indhold hvis det eksisterer
        const fallbackContent = root.innerHTML;
        
        // Mount Vue app - dette erstatter indholdet i root
        const app = createApp(AppComponent);
        
        // Tilføj error handler til Vue app
        app.config.errorHandler = (err, instance, info) => {
            console.error('Vue fejl:', err, info);
            // Vis fejlbesked hvis Vue fejler
            if (root && !root.querySelector('.vue-error-message')) {
                root.innerHTML = `
                    <div class="mx-auto max-w-5xl p-6">
                        <div class="vue-error-message rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">
                            <h2 class="font-semibold">Der opstod en fejl ved indlæsning af applikationen</h2>
                            <p class="mt-2 text-sm">${fallbackContent || 'Prøv at genindlæse siden.'}</p>
                        </div>
                    </div>
                `;
            }
        };
        
        app.mount(root);
        
        // Verificer at Vue faktisk har mounted og vist indhold
        setTimeout(() => {
            if (root && (!root.innerHTML.trim() || root.innerHTML.trim().length < 50)) {
                console.warn('Vue mountede, men indholdet ser tomt ud. Viser fallback.');
                root.innerHTML = fallbackContent || `
                    <div class="mx-auto max-w-5xl p-6">
                        <h1 class="text-2xl font-semibold">Bestil lækker mad hurtigt</h1>
                        <p class="mt-4 text-slate-700">Frontend indlæses… hvis du ikke ser menuen, mangler JavaScript eller assets.</p>
                    </div>
                `;
            }
        }, 1000);
        
    } catch (error) {
        console.error('Fejl ved indlæsning af Vue app:', error);
        // Sørg for at statisk indhold stadig vises hvis Vue fejler
        const fallbackContent = root.innerHTML || `
            <div class="mx-auto max-w-5xl p-6">
                <h1 class="text-2xl font-semibold">Bestil lækker mad hurtigt</h1>
                <p class="mt-4 text-red-600">Der opstod en fejl ved indlæsning af applikationen.</p>
                <p class="mt-2 text-sm text-slate-600">Prøv at genindlæse siden eller kontakt support.</p>
            </div>
        `;
        
        if (!root.innerHTML.trim() || root.innerHTML.trim().length < 50) {
            root.innerHTML = fallbackContent;
        }
    }
} else {
    console.error('Kunne ikke finde #app element');
    // Opret app element hvis det ikke findes
    const body = document.body;
    if (body) {
        const appDiv = document.createElement('div');
        appDiv.id = 'app';
        appDiv.innerHTML = `
            <div class="mx-auto max-w-5xl p-6">
                <h1 class="text-2xl font-semibold">Bestil lækker mad hurtigt</h1>
                <p class="mt-4 text-red-600">App container mangler. Kontroller HTML strukturen.</p>
            </div>
        `;
        body.appendChild(appDiv);
    }
}
