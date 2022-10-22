import {createRouter, createWebHashHistory} from 'vue-router';

import Home from './views/Home';
import Admin from './views/Admin';

const router = createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/admin',
            name: 'admin',
            component: Admin
        },
    ],
});

export default router;
