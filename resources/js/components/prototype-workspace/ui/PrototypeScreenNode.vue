<script setup lang="ts">
import { Handle, Position } from '@vue-flow/core'
import type { IPrototypePage } from '@/types/prototype.types'
import { Icon } from '@iconify/vue'

defineProps<{
    id: string
    data: IPrototypePage
}>()

// line-md:loading-loop
</script>

<template>
    <Handle type="target" :position="Position.Left" />

    <div class="app-card-bordered gap-1 p-2 shadow-md flex w-[420px] flex-col overflow-hidden">
        <div class="gap-x-2 flex items-center">
            <Icon :icon="data?.icon ?? 'icon-park-twotone:web-page'" class="text-primary-500" />
            <h3 class="font-bold">{{ data.title }}</h3>
        </div>
        <p class="text-xs text-surface-400 line-clamp-5">{{ data.description }}</p>
    </div>

    <Handle type="source" :position="Position.Right" />
</template>
