import { setupLayouts } from 'virtual:generated-layouts';
import { createRouter, createWebHistory } from 'vue-router';
import routes from '~pages';

const isUserLoggedIn = () => !!(localStorage.getItem('accessToken'))

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: setupLayouts(routes),
})
router.beforeEach(to => {
    const isLoggedIn = isUserLoggedIn();

    if (to.meta.redirectIfLoggedIn && isLoggedIn) {
        return '/home';
    } else if (!isLoggedIn) {
        // return { name: 'login', query: { to: to.name !== 'home' ? to.fullPath : undefined } }
    }
})

export default router;
