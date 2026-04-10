<script setup lang="ts">
import { Handle, Position } from '@vue-flow/core'
import type { IPrototypePage } from '@/types/prototype.types'
import { Icon } from '@iconify/vue'
import { NodeResizer } from '@vue-flow/node-resizer'
import PulsingBlocksAnimation from './PulsingBlocksAnimation.vue'

defineProps<{
    id: string
    data: IPrototypePage
}>()

// line-md:loading-loop
</script>

<template>
    <NodeResizer :min-width="100" :min-height="100" />

    <Handle type="target" :position="Position.Left" />

    <div
        class="rounded-lg gap-1 shadow-md bg-white dark:bg-ebony-950 border-primary-700 flex h-full w-full flex-col overflow-hidden border-3"
    >
        <div class="gap-x-4 rounded-t-lg px-2 py-1 bg-ebony-50 dark:bg-black-pearl-950 flex items-center border-b">
            <Icon icon="wordpress:page" class="w-7 h-7 text-primary-500" />
            <div class="flex flex-col">
                <h3 class="font-bold">{{ data.title }}</h3>
                <span class="text-xs text-gray-400">{{ data.file_name }}</span>
            </div>
            <div v-if="!data.implementation" class="ml-auto">
                <Icon icon="line-md:loading-loop" class="w-5 h-5 text-primary-500" />
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
        <div v-else class="flex h-full w-full flex-col overflow-hidden">
            <PulsingBlocksAnimation />
        </div>
    </div>

    <Handle type="source" :position="Position.Right" />
</template>
