<script setup lang="ts">
import { Handle, Position } from '@vue-flow/core'
import type { IPrototypePage } from '@/types/prototype.types'

defineProps<{
    id: string
    data: IPrototypePage
}>()
</script>

<template>
    <Handle type="target" :position="Position.Left" />

    <div class="app-card p-2 w-[220px]">
        <p class="text-sm font-semibold mb-1 truncate">{{ data.title }}</p>
        <p class="text-xs text-surface-400 line-clamp-4">{{ data.description }}</p>
    </div>

    <Handle type="source" :position="Position.Right" />
</template>
