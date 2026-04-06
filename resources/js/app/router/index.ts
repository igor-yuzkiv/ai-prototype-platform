import { createRouter, createWebHashHistory } from 'vue-router'

const router = createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: '/',
            component: () => import('@/pages/ProjectsPage.vue'),
        },
        {
            path: '/projects/:id',
            component: () => import('@/pages/ProjectWorkspacePage.vue'),
        },
    ],
})

export default router
