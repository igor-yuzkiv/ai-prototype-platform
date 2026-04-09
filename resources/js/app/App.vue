<script setup lang="ts">
import Toast from 'primevue/toast'
import { RouterView } from 'vue-router'
import ConfirmDialog from 'primevue/confirmdialog'
import { ToggleThemeButton } from '@/shared/components/button'
import { useAppThemeStore } from '@/app/store/use.app-theme.store'
import { type ServerEvent, serverEventBus } from '@/server-event-bus'
import { useToast } from '@/shared/composables'

const themeStore = useAppThemeStore()
const toast = useToast()

themeStore.initialize()

serverEventBus.on('*', (payload: ServerEvent) => {
    console.log('Received server event:', payload)
    toast.info({
        summary: 'Server Event',
        detail: payload.message,
    })
})
</script>

<template>
    <div class="bg-secondary flex h-screen w-full flex-col overflow-hidden">
        <main class="relative h-full flex-1 flex-col overflow-hidden">
            <RouterView />
        </main>

        <div class="flex w-full items-center justify-end">
            <ToggleThemeButton :is-dark="themeStore.isDark" @click="themeStore.toggle()" />
        </div>
    </div>

    <Toast position="top-right" />
    <ConfirmDialog />
</template>
