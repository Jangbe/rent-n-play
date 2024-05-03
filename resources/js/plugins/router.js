import { setupLayouts } from 'virtual:generated-layouts';
import { createRouter, createWebHistory } from 'vue-router';
import routes from '~pages';

const isUserLoggedIn = () => !!(localStorage.getItem('userData') && localStorage.getItem('accessToken'))

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: setupLayouts(routes),
})
router.beforeEach(to => {
    const isLoggedIn = isUserLoggedIn();

    // if (isLoggedIn)
    //     return { name: 'not-authorized' }
    // else
    //     return { name: 'index', query: { to: to.name !== 'index' ? to.fullPath : undefined } }
})

export default router;
