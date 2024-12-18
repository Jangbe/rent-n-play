import { setupLayouts } from 'virtual:generated-layouts';
import { createRouter, createWebHistory } from 'vue-router';
import routes from '~pages';

const isUserLoggedIn = () => !!(localStorage.getItem('accessToken'))

const router = createRouter({
    history: createWebHistory(),
    routes: setupLayouts(routes),
})
router.beforeEach(to => {
    const isLoggedIn = isUserLoggedIn();

    if (to.meta.redirectIfLoggedIn && isLoggedIn) {
        return '/home';
    } else if (!isLoggedIn && to.meta.layout == 'admin') {
        return { name: 'login', query: { to: to.fullPath } }
    }
})

export default router;
