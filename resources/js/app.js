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

        createApp(AppComponent).mount(root);
    } catch (error) {
        console.error('Fejl ved indlæsning af Vue app:', error);
        // Sørg for at statisk indhold stadig vises hvis Vue fejler
        if (!root.innerHTML.trim()) {
            root.innerHTML = '<div class="mx-auto max-w-5xl p-6"><p class="text-red-600">Der opstod en fejl ved indlæsning af applikationen.</p></div>';
        }
    }
}
