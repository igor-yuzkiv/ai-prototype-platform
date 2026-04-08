<script setup lang="ts">
import { Icon } from '@iconify/vue'

withDefaults(
    defineProps<{
        title?: string
        message?: string
        icon?: string
    }>(),
    {
        title: 'No Data Available',
        message: 'There is currently no data to display here.',
        icon: 'rivet-icons:browser-window-solid',
    }
)
</script>

<template>
    <div class="text-gray-400 flex flex-col items-center justify-center">
        <Icon :icon="icon" class="w-12 h-12" />
        <div class="gap-2 max-w-md mt-3 flex flex-col items-center">
            <h1 class="text-2xl font-bold">{{ title }}</h1>
            <p class="text-center">{{ message }}</p>
        </div>
    </div>
</template>

<style scoped></style>
