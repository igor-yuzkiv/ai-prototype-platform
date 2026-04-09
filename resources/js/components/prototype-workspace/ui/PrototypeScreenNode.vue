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
        class="rounded-lg gap-1 shadow-md bg-white dark:bg-shark-900 flex h-[883px] w-[1280px] flex-col overflow-hidden border border-dashed"
    >
        <div class="gap-x-2 bg-primary rounded-t-lg px-2 py-1 flex items-center">
            <Icon :icon="data?.icon ?? 'icon-park-twotone:web-page'" class="text-primary-500" />
            <div class="flex flex-col">
                <h3 class="font-bold">{{ data.title }}</h3>
                <span class="text-xs text-gray-400">{{ data.file_name }}</span>
            </div>
        </div>

        <div v-if="data?.implementation" class="relative flex h-full w-full flex-col">
            <iframe
                :srcdoc="data.implementation"
                class="rounded-b-lg h-full w-full border-none"
                sandbox="allow-scripts"
            />

            <div class="inset-0 absolute" />
        </div>
        <div v-else class="flex h-full w-full flex-col overflow-auto p-2">
            <pre>{{ data.description }}</pre>
        </div>
    </div>

    <Handle type="source" :position="Position.Right" />
</template>
