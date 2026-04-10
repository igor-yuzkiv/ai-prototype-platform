<script setup lang="ts">
import { Handle, Position } from '@vue-flow/core'
import { NodeResizer } from '@vue-flow/node-resizer'

defineProps<{
    id: string
    data: {
        title: string
        content: string
    }
}>()
</script>

<template>
    <NodeResizer :min-width="100" :min-height="100" />

    <Handle type="source" :position="Position.Right" />
    <div
        class="rounded-lg shadow-md bg-white dark:bg-shark-900 border-gray-300 dark:border-shark-700 flex w-full h-full flex-col overflow-hidden border-2"
    >
        <div class="px-3 py-2 border-b bg-gray-50 dark:bg-shark-800">
            <h3 class="font-bold text-sm text-gray-700 dark:text-gray-300">{{ data.title }}</h3>
        </div>

        <div class="p-3 max-h-[400px] overflow-auto">
            <pre class="text-xs text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ data.content }}</pre>
        </div>
    </div>
</template>
