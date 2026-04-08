<script setup lang="ts">
import type { Primitive } from '@zoho-studio/utils'

defineProps<{
    label?: string
    value?: Primitive
}>()
</script>

<template>
    <div class="gap-1 flex flex-col">
        <div class="text-gray-600 dark:text-gray-400 uppercase">
            <slot name="label">{{ label }}</slot>
        </div>
        <slot name="value">
            <span class="text-black dark:text-white">{{ value }}</span>
        </slot>
    </div>
</template>

<style scoped></style>
