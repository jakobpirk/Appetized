import './bootstrap';
import { createApp } from 'vue';
import AdminPanel from './components/AdminPanel.vue';
import PublicSite from './components/PublicSite.vue';

const root = document.getElementById('app');

if (root) {
    const view =
        root.dataset.view || (window.location.pathname.startsWith('/admin') ? 'admin' : 'frontend');
    const AppComponent = view === 'admin' ? AdminPanel : PublicSite;

    createApp(AppComponent).mount(root);
}
