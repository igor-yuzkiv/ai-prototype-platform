<script setup lang="ts">
import { ref } from 'vue'
import { Icon } from '@iconify/vue'
import Textarea from 'primevue/textarea'
import Button from 'primevue/button'
import type { IPrototypePage } from '@/types/prototype.types'

defineProps<{
    screen: IPrototypePage
}>()

const emit = defineEmits<{
    close: []
}>()

const instructions = ref('')
</script>

<template>
    <div class="w-80 bg-surface-0 flex h-full flex-col overflow-hidden border-l">
        <!-- Header -->
        <div class="gap-x-2 p-3 flex shrink-0 items-start justify-between border-b">
            <div class="gap-x-2 flex items-center overflow-hidden">
                <Icon icon="material-symbols:phone-iphone-outline" class="h-5 w-5 text-primary-500 shrink-0" />
                <div class="overflow-hidden">
                    <h2 class="text-base font-bold truncate">{{ screen.title }}</h2>
                    <p class="text-xs text-surface-400 truncate">{{ screen.file_name }}</p>
                </div>
            </div>
            <button
                class="mt-0.5 rounded p-1 text-surface-400 hover:bg-surface-100 hover:text-surface-600 shrink-0"
                @click="emit('close')"
            >
                <Icon icon="material-symbols:close-rounded" class="h-4 w-4" />
            </button>
        </div>

        <!-- Scrollable content -->
        <div class="p-3 flex-1 overflow-y-auto">
            <div class="gap-x-4 gap-y-4 grid grid-cols-2">
                <div class="col-span-2">
                    <p class="mb-1 font-semibold tracking-wider text-surface-400 text-[10px] uppercase">DESCRIPTION</p>
                    <pre class="text-sm">{{ screen.description }}</pre>
                </div>

                <div>
                    <p class="mb-1 font-semibold tracking-wider text-surface-400 text-[10px] uppercase">FILE</p>
                    <p class="text-sm font-mono truncate">{{ screen.file_name }}</p>
                </div>

                <div>
                    <p class="mb-1 font-semibold tracking-wider text-surface-400 text-[10px] uppercase">DEPTH</p>
                    <p class="text-sm">{{ screen.deep_index ?? 0 }}</p>
                </div>

                <div v-if="screen.parent_page_id" class="col-span-2">
                    <p class="mb-1 font-semibold tracking-wider text-surface-400 text-[10px] uppercase">PARENT ID</p>
                    <p class="font-mono text-xs text-surface-400 truncate">{{ screen.parent_page_id }}</p>
                </div>

                <div class="col-span-2">
                    <p class="mb-1 font-semibold tracking-wider text-surface-400 text-[10px] uppercase">
                        IMPLEMENTATION
                    </p>
                    <span
                        :class="[
                            'px-2 py-0.5 text-xs font-medium rounded-full',
                            screen.implementation
                                ? 'bg-green-500/20 text-green-400'
                                : 'bg-surface-200 text-surface-400',
                        ]"
                    >
                        {{ screen.implementation ? 'Generated' : 'Not generated' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Fixed bottom: instructions -->
        <div class="p-3 shrink-0 border-t">
            <p class="mb-2 font-semibold tracking-wider text-surface-400 text-[10px] uppercase">INSTRUCTIONS</p>
            <Textarea
                v-model="instructions"
                :auto-resize="false"
                rows="3"
                placeholder="Additional instructions for generation..."
                class="text-sm w-full"
            />
            <Button label="Generate" icon="pi pi-bolt" class="mt-2 w-full" size="small" />
        </div>
    </div>
</template>
