import { createRouter, createWebHashHistory } from 'vue-router'

const router = createRouter({
    history: createWebHashHistory(),
    routes: [
        {
            path: '/',
            component: () => import('@/views/PrototypesView.vue'),
        },
        {
            path: '/prototypes/:id',
            component: () => import('@/views/PrototypeWorkspaceView.vue'),
        }
    ],
})

export default router
