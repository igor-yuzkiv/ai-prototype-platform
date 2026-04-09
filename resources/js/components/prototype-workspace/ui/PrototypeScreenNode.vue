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

    <div
        class="rounded-lg gap-1 shadow-md bg-white dark:bg-shark-900 flex w-[420px] flex-col overflow-hidden border border-dashed"
    >
        <div class="gap-x-2 bg-primary rounded-t-lg px-2 py-1 flex items-center">
            <Icon :icon="data?.icon ?? 'icon-park-twotone:web-page'" class="text-primary-500" />
            <div class="flex flex-col">
                <h3 class="font-bold">{{ data.title }}</h3>
                <span class="text-xs text-gray-400">{{data.file_name}}</span>
            </div>
        </div>

        <p class="text-xs text-surface-400 p-2 line-clamp-5">{{ data.description }}</p>
    </div>

    <Handle type="source" :position="Position.Right" />
</template>
