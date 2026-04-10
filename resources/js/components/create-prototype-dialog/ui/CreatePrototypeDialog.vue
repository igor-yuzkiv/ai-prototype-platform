<script setup lang="ts">
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import { computed } from 'vue'
import { useAppThemeStore } from '@/app/store/use.app-theme.store'
import { LoadingIndicator } from '@/shared/components/loading'

const props = defineProps<{
    isGenerating: boolean
    isNormalizing: boolean
    canGenerate: boolean
}>()

const emit = defineEmits<{
    (event: 'close'): void
    (event: 'generate'): void
}>()

const visible = defineModel<boolean>('visible', { default: false })
const requirements = defineModel<string>('requirements', { default: '' })

const appTheme = useAppThemeStore()

const statusText = computed(() => {
    if (props.isGenerating) {
        return 'Creating prototype...'
    }

    if (props.isNormalizing) {
        return 'Normalizing requirements...'
    }

    return 'Review normalized requirements before generating the prototype.'
})
</script>

<template>
    <Dialog
        v-model:visible="visible"
        modal
        maximizable
        :draggable="false"
        :closable="!isGenerating"
        header="Prototype Requirements"
        class="dark:bg-primary max-w-6xl h-[95vh] w-[96vw] border-none"
        @hide="emit('close')"
    >
        <div class="flex h-full flex-col overflow-hidden">
            <div class="gap-4 px-4 py-3 flex items-center justify-between border-b">
                <p class="text-sm text-surface-600 dark:text-surface-300">
                    {{ statusText }}
                </p>

                <span
                    v-if="isNormalizing || isGenerating"
                    class="text-xs font-medium text-primary tracking-[0.18em] uppercase"
                >
                    In progress
                </span>
            </div>

            <div class="relative flex-1 overflow-hidden">
                <vue-monaco-editor
                    v-model:value="requirements"
                    class="h-full"
                    language="markdown"
                    :theme="appTheme.isDark ? 'vs-dark' : 'vs'"
                    :options="{
                        readOnly: isNormalizing || isGenerating,
                        automaticLayout: true,
                        minimap: { enabled: false },
                        wordWrap: 'on',
                        scrollBeyondLastLine: false,
                        lineNumbers: 'off',
                    }"
                />

                <LoadingIndicator v-if="isNormalizing" position="absolute right-6 top-6" />
            </div>

            <div class="gap-3 px-4 py-3 flex items-center justify-between border-t">
                <Button text label="Cancel" severity="secondary" :disabled="isGenerating" @click="emit('close')" />

                <Button
                    label="Generate"
                    icon="pi pi-arrow-right"
                    icon-pos="right"
                    :loading="isGenerating"
                    :disabled="!canGenerate"
                    @click="emit('generate')"
                />
            </div>
        </div>
    </Dialog>
</template>
