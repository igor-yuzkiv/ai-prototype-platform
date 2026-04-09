<script setup lang="ts">
import { useRouteParams } from '@vueuse/router'
import { usePrototypeQuery } from '@/query'
import { Icon } from '@iconify/vue'
import { LoadingOverlay } from '@/shared/components/loading'
import { PrototypeWorkspaceFlow } from '@/components/prototype-workspace'
import { PrototypePageDetailDialog, usePrototypePageDetailsDialog } from '@/components/prototype-page'
import { MdPreview } from 'md-editor-v3'
import { useAppThemeStore } from '@/app/store/use.app-theme.store'
import { computed } from 'vue'
import Splitter from 'primevue/splitter'
import SplitterPanel from 'primevue/splitterpanel'

const prototypeId = useRouteParams<string>('id')
const { prototype, pages, isLoading: isLoadingPrototype } = usePrototypeQuery(prototypeId)
const appTheme = useAppThemeStore()
const pageDialog = usePrototypePageDetailsDialog()

const textView = computed(() => {
    const parts = []

    if (prototype.value?.project_overview) {
        parts.push('## Project Overview')
        parts.push(prototype.value.project_overview)
    }

    if (prototype.value?.global_rules) {
        parts.push('## Global Rules')
        parts.push(prototype.value.global_rules)
    }

    if (prototype.value?.formatted_requirements) {
        parts.push('## Formatted Requirements')
        parts.push(prototype.value.formatted_requirements)
    }

    if (prototype.value?.initial_requirements) {
        parts.push('## Initial Requirements')
        parts.push(prototype.value.initial_requirements)
    }

    return parts.join('\n')
})
</script>

<template>
    <LoadingOverlay v-if="isLoadingPrototype" />
    <div v-if="prototype" class="p-2 gap-2 flex h-full w-full flex-col overflow-hidden">
        <div class="flex items-center justify-between">
            <div class="gap-x-2 flex items-center">
                <router-link
                    to="/"
                    class="h-10 w-10 rounded bg-primary-500 text-white group flex items-center justify-center"
                >
                    <Icon icon="tdesign:app" class="w-6 h-6 group-hover:hidden" />
                    <Icon icon="material-symbols:arrow-back-rounded" class="w-6 h-6 hidden group-hover:flex" />
                </router-link>

                <h3 class="font-semibold">{{ prototype.name }}</h3>
            </div>
        </div>

        <Splitter
            class="flex h-full w-full flex-1 overflow-hidden border-none bg-transparent"
            :pt="{ gutter: { class: 'bg-transparent' } }"
        >
            <SplitterPanel
                class="flex h-full w-full overflow-hidden"
                :size="5"
                style="min-width: 10rem; max-width: 50rem"
            >
                <div class="bg-white dark:bg-primary rounded-lg p-2 flex h-full w-full flex-col overflow-auto">
                    <MdPreview
                        :modelValue="textView"
                        :theme="appTheme.isDark ? 'dark' : 'light'"
                        language="en-US"
                        :codeFoldable="false"
                        previewTheme="github"
                        codeTheme="github"
                    />
                </div>
            </SplitterPanel>
            <SplitterPanel class="flex h-full w-full flex-col overflow-hidden">
                <PrototypeWorkspaceFlow :pages="pages" @page:click="pageDialog.open" />
            </SplitterPanel>
        </Splitter>
    </div>

    <PrototypePageDetailDialog v-model:visible="pageDialog.visible.value" :page="pageDialog.currentPage.value" />
</template>
