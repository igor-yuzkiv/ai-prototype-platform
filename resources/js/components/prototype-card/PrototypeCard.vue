<script setup lang="ts">
import { IPrototypeSummary } from '@/types/prototype.types'
import { type Component, computed } from 'vue'
import { Icon } from '@iconify/vue'
import { formatDistanceToNow } from 'date-fns'

const props = withDefaults(
    defineProps<{
        prototype: IPrototypeSummary
        is?: Component | string
    }>(),
    {
        is: 'div',
    }
)

const updatedAt = computed<string>(() => {
    return `Updated ${formatDistanceToNow(new Date(props.prototype.updated_at), { addSuffix: true })}`
})
</script>

<template>
    <component :is="is" class="rounded-lg p-5 group bg-white dark:bg-primary hover:shadow-sm flex flex-col">
        <div class="mb-3 flex items-center justify-between">
            <div class="gap-2 flex items-center">
                <div class="h-8 w-8 rounded bg-primary-500 text-white flex items-center justify-center">
                    <Icon icon="tdesign:app" />
                </div>

                <h3 class="font-semibold">{{ prototype.name }}</h3>
            </div>

            <slot name="actions" :prototype="prototype" />
        </div>

        <div class="mb-5 text-xs font-medium text-gray-500 line-clamp-3 h-full">
            {{ prototype.project_overview ?? 'New prototype' }}
        </div>

        <div class="text-xs font-medium text-gray-500 flex items-center justify-between">
            <div class="gap-1.5 mt-auto flex items-center">
                <Icon icon="hugeicons:status" />
                <span>{{ prototype.status ?? 'new' }}</span>
            </div>
            <div class="gap-1.5 mt-auto flex items-center">
                <Icon icon="mdi-light:clock" />
                <span>{{ updatedAt }}</span>
            </div>
        </div>
    </component>
</template>

<style scoped></style>
