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
    <component
        :is="is"
        class="rounded-lg p-5 group bg-white dark:bg-black-pearl-950 hover:shadow-lg gap-4 flex flex-col border"
    >
        <div class="gap-x-1 flex items-center justify-between truncate">
            <div class="gap-x-1 flex items-center truncate">
                <div class="p-1 rounded bg-primary-700 text-white flex items-center justify-center">
                    <Icon icon="tdesign:app" />
                </div>

                <h3 class="font-medium truncate">
                    {{ prototype.name }}
                </h3>
            </div>
            <div class="gap-x-1 text-sm flex items-center">
                <div class="rounded-lg dark:bg-black-pearl-800 px-2 py-0.5 border capitalize">
                    {{ prototype.status }}
                </div>

                <Icon v-if="prototype.is_published" icon="material-symbols:public" v-tooltip="{ value: 'Shared' }" />
            </div>
        </div>

        <div class="mb-5 text-xs text-gray-500 line-clamp-4 flex-1">
            {{ prototype.project_overview ?? 'New prototype' }}
        </div>

        <div class="text-xs text-gray-500 flex items-center justify-between">
            <div class="gap-1 mt-auto flex items-center">
                <Icon icon="mdi-light:clock" />
                <span>{{ updatedAt }}</span>
            </div>

            <div class="gap-x-1 flex items-center">
                <slot name="actions" :prototype="prototype" />
            </div>
        </div>
    </component>
</template>

<style scoped></style>
