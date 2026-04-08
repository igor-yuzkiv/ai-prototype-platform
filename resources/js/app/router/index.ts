import { createRouter, createWebHashHistory } from 'vue-router'

const router = createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: '/',
            component: () => import('@/pages/PrototypesPage.vue'),
        },
        {
            path: '/prototypes/:id',
            component: () => import('@/pages/PrototypeWorkspacePage.vue'),
        },
    ],
})

export default router
