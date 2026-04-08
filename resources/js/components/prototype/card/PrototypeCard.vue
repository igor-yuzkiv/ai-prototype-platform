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
    <component :is="is" class="app-card rounded-md p-5 shadow-sm hover:shadow-md group flex flex-col border">
        <div class="mb-3 flex items-center justify-between">
            <div class="gap-2 flex items-center">
                <div class="h-8 w-8 rounded bg-primary-500 text-white flex items-center justify-center">
                    <Icon icon="tdesign:app" />
                </div>

                <h3 class="font-semibold">{{ prototype.name }}</h3>
            </div>

            <slot name="actions" :prototype="prototype" />
        </div>

        <p class="mb-5 text-xs font-medium text-gray-500 line-clamp-3">
            {{ prototype.project_overview ?? 'New prototype' }}
        </p>

        <div class="gap-1.5 text-xs font-medium text-gray-500 mt-auto flex items-center">
            <Icon icon="mdi:clock-outline" />
            <span>{{ updatedAt }}</span>
        </div>
    </component>
</template>

<style scoped></style>
