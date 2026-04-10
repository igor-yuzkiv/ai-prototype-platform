<script setup lang="ts">
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import { useAppThemeStore } from '@/app/store/use.app-theme.store'
import { LoadingIndicator } from '@/shared/components/loading'

defineProps<{
    isGenerating: boolean
    isNormalizing: boolean
    canGenerate: boolean
}>()
defineEmits<{ (event: 'generate'): void }>()

const visible = defineModel<boolean>('visible', { default: false })
const requirements = defineModel<string>('requirements', { default: '' })
const appTheme = useAppThemeStore()
</script>

<template>
    <Dialog
        v-model:visible="visible"
        modal
        maximizable
        :draggable="false"
        :closable="!isGenerating"
        header="Prototype Requirements"
        class="dark:bg-primary lg:w-[80%] lg:h-[90vh] xl:w-[70%] h-full w-full"
    >
        <div class="flex h-full flex-col overflow-hidden">
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
        </div>

        <template #footer>
            <div class="flex w-full items-center justify-end">
                <Button
                    text
                    label="Generate"
                    :loading="isGenerating"
                    :disabled="!canGenerate"
                    @click="$emit('generate')"
                />
            </div>
        </template>
    </Dialog>
</template>
