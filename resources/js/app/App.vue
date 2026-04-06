<script setup lang="ts">
import Toast from 'primevue/toast'
import { RouterView } from 'vue-router'
import ConfirmDialog from 'primevue/confirmdialog'
</script>

<template>
    <div class="flex h-screen w-full flex-col overflow-hidden">
        <RouterView />
        <Toast position="top-right" />
        <ConfirmDialog />
    </div>
</template>
