<script setup lang="ts">
import { useRouteParams } from '@vueuse/router'
import { useProjectQuery } from '@/query'
import { useThemeStore } from '@/store/use.theme.store'
import { Icon } from '@iconify/vue'
import Button from 'primevue/button'
import Splitter from 'primevue/splitter'
import SplitterPanel from 'primevue/splitterpanel'
import Tabs from 'primevue/tabs'
import TabList from 'primevue/tablist'
import Tab from 'primevue/tab'
import TabPanel from 'primevue/tabpanel'

const projectId = useRouteParams<string>('id')

const { data: project } = useProjectQuery(projectId)
const appTheme = useThemeStore()
</script>

<template>
    <div v-if="project" class="p-3 gap-2 flex h-full w-full flex-col overflow-hidden">
        <div class="flex items-center justify-between">
            <div class="gap-x-2 flex items-center">
                <router-link
                    to="/"
                    class="h-10 w-10 rounded bg-primary-500 text-white group flex items-center justify-center"
                >
                    <Icon icon="tdesign:app" class="w-6 h-6 group-hover:hidden" />
                    <Icon icon="material-symbols:arrow-back-rounded" class="w-6 h-6 hidden group-hover:flex" />
                </router-link>

                <h3 class="text-xl font-semibold">{{ project.name }}</h3>
            </div>

            <div class="gap-x-2 flex items-center">
                <Button text>Generate</Button>
            </div>
        </div>

        <Splitter
            class="flex h-full w-full overflow-hidden border-none bg-transparent"
            :pt="{ gutter: { class: 'bg-transparent' } }"
        >
            <SplitterPanel class="flex h-full overflow-hidden" :size="5" style="min-width: 10rem; max-width: 50rem">
                <div class="app-card flex h-full w-full flex-col overflow-hidden"></div>
            </SplitterPanel>

            <SplitterPanel class="flex h-full w-full flex-col overflow-hidden">
                <div class="app-card flex h-full w-full flex-col overflow-hidden">
                    <Tabs value="Requirements" class="flex h-full w-full flex-col overflow-hidden">
                        <TabList class="bg-transparent">
                            <Tab value="Requirements">Requirements</Tab>
                            <Tab value="Preview">Preview</Tab>
                        </TabList>

                        <TabPanel value="Requirements" class="gap-2 p-2 h-full w-full overflow-hidden">
                            <vue-monaco-editor
                                language="markdown"
                                :value="project?.formatted_requirements ?? ''"
                                :theme="appTheme.isDark ? 'vs-dark' : 'vs'"
                            />
                        </TabPanel>
                        <TabPanel value="Preview" class="gap-2 p-2 h-full w-full overflow-hidden">
                            <iframe
                                v-if="project?.prototype_url"
                                :src="project.prototype_url"
                                class="rounded h-full w-full border-none"
                            />
                        </TabPanel>
                    </Tabs>
                </div>
            </SplitterPanel>
        </Splitter>
    </div>
</template>
