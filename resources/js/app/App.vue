<script setup lang="ts">
import Toast from 'primevue/toast'
import { RouterView } from 'vue-router'
import ConfirmDialog from 'primevue/confirmdialog'
import { ToggleThemeButton } from '@/components/button'
import { useThemeStore } from '@/store/use.theme.store'

const themeStore = useThemeStore()

themeStore.initialize()
</script>

<template>
    <div class="flex h-screen w-full flex-col overflow-hidden">
        <main class="bg-secondary relative flex h-screen w-full flex-col overflow-hidden">
            <RouterView />
        </main>

        <div class="flex w-full items-center justify-end border-t">
            <ToggleThemeButton :is-dark="themeStore.isDark" @click="themeStore.toggle()" />
        </div>
    </div>

    <Toast position="top-right" />
    <ConfirmDialog />
</template>
