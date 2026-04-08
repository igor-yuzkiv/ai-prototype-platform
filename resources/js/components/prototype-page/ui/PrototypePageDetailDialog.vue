<script setup lang="ts">
import Dialog from 'primevue/dialog'
import { Maybe } from '@/shared/types/result.types'
import { IPrototypePage } from '@/types/prototype.types'
import Tabs from 'primevue/tabs'
import TabList from 'primevue/tablist'
import Tab from 'primevue/tab'
import TabPanel from 'primevue/tabpanel'
import { useAppThemeStore } from '@/app/store/use.app-theme.store'
import { DisplayField, NoDataMessage } from '@/shared/components/typography'

defineProps<{ page: Maybe<IPrototypePage> }>()
const visible = defineModel<boolean>('visible')

const appTheme = useAppThemeStore()
</script>

<template>
    <Dialog
        v-model:visible="visible"
        class="bg-primary h-[95vh] w-[98%] border-none"
        modal
        :header="page?.title ?? 'Page Details'"
    >
        <div v-if="page" class="flex h-full w-full flex-col overflow-hidden">
            <Tabs value="overview" class="flex h-full w-full flex-col overflow-hidden">
                <TabList class="bg-transparent">
                    <Tab class="px-3 py-1" value="overview">Overview</Tab>
                    <Tab class="px-3 py-1" value="preview">Preview</Tab>
                    <Tab class="px-3 py-1" value="code">Code</Tab>
                </TabList>

                <TabPanel value="overview" class="gap-2 p-2 h-full w-full overflow-hidden">
                    <div class="gap-4 p-3 md:grid-cols-2 grid">
                        <DisplayField label="title" :value="page.title" />
                        <DisplayField label="file_name" :value="page.file_name" />
                        <DisplayField label="deep_index" :value="page.deep_index" />
                    </div>

                    <vue-monaco-editor
                        language="markdown"
                        :value="page.description"
                        :theme="appTheme.isDark ? 'vs-dark' : 'vs'"
                    />
                </TabPanel>
                <TabPanel value="preview" class="gap-2 p-2 h-full w-full overflow-hidden">
                    <NoDataMessage
                        v-if="!page.implementation"
                        title="No implementation yet"
                        message="This page does not have an implementation yet, so preview is not available."
                        class="h-full w-full"
                    />

                    <iframe v-else :srcdoc="page.implementation" class="rounded h-full w-full border-none" />
                </TabPanel>
                <TabPanel value="code" class="gap-2 p-2 h-full w-full overflow-hidden">
                    <NoDataMessage
                        v-if="!page.implementation"
                        title="No implementation yet"
                        message="No implementation code available for this page."
                        class="h-full w-full"
                    />

                    <vue-monaco-editor
                        v-else
                        language="html"
                        :value="page.implementation"
                        :theme="appTheme.isDark ? 'vs-dark' : 'vs'"
                    />
                </TabPanel>
            </Tabs>
        </div>
    </Dialog>
</template>

<style scoped></style>
