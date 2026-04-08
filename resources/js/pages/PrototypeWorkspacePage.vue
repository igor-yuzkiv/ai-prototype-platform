<script setup lang="ts">
import { useRouteParams } from '@vueuse/router'
import { usePrototypeQuery } from '@/query'
import { useThemeStore } from '@/store/use.theme.store'
import { Icon } from '@iconify/vue'
import Button from 'primevue/button'
import Splitter from 'primevue/splitter'
import SplitterPanel from 'primevue/splitterpanel'
import Tabs from 'primevue/tabs'
import TabList from 'primevue/tablist'
import Tab from 'primevue/tab'
import TabPanel from 'primevue/tabpanel'
import { httpClient } from '@/api'
import { useToast } from '@/composables'
import { fetchEventSource, EventStreamContentType } from '@microsoft/fetch-event-source'
import { ref, toValue } from 'vue'

const prototypeId = useRouteParams<string>('id')

const { data: prototype } = usePrototypeQuery(prototypeId)
const appTheme = useThemeStore()
const toast = useToast()

const generatedHtml = ref<string>('')
const isGenerating = ref(false)

type StreamEventPayload = {
    type?: string
    delta?: string
    message?: string
    errorText?: string
}

function parseStreamPayload(data: string): StreamEventPayload | null {
    if (data === '[DONE]') {
        return null
    }

    try {
        return JSON.parse(data) as StreamEventPayload
    } catch {
        return null
    }
}

function appendStreamEvent(data: string) {
    const payload = parseStreamPayload(data)

    if (!payload) {
        return
    }

    if ((payload.type === 'text_delta' || payload.type === 'text-delta') && payload.delta) {
        generatedHtml.value += payload.delta
        return
    }

    if (payload.type === 'error') {
        throw new Error(payload.errorText ?? payload.message ?? 'Generation failed.')
    }
}

async function generate() {
    generatedHtml.value = ''
    isGenerating.value = true

    try {
        await fetchEventSource(httpClient.getUri({ url: `/prototypes/${toValue(prototypeId)}/prototype/generate` }), {
            method: 'POST',
            credentials: 'include',
            headers: {
                Accept: EventStreamContentType,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            openWhenHidden: true,
            async onopen(response) {
                if (!response.ok) {
                    throw new Error(`Generation failed with status ${response.status}.`)
                }

                const contentType = response.headers.get('content-type')

                if (!contentType?.startsWith(EventStreamContentType)) {
                    throw new Error(`Expected ${EventStreamContentType} response.`)
                }
            },
            onmessage(message) {
                appendStreamEvent(message.data)
            },
            onerror(error) {
                throw error
            },
        })
    } catch (error) {
        if (error instanceof DOMException && error.name === 'AbortError') {
            return
        }

        toast.error({
            detail: error instanceof Error ? error.message : 'Generation failed.',
        })
    } finally {
        isGenerating.value = false
    }
}
</script>

<template>
    <div v-if="prototype" class="p-3 gap-2 flex h-full w-full flex-col overflow-hidden">
        <div class="flex items-center justify-between">
            <div class="gap-x-2 flex items-center">
                <router-link
                    to="/"
                    class="h-10 w-10 rounded bg-primary-500 text-white group flex items-center justify-center"
                >
                    <Icon icon="tdesign:app" class="w-6 h-6 group-hover:hidden" />
                    <Icon icon="material-symbols:arrow-back-rounded" class="w-6 h-6 hidden group-hover:flex" />
                </router-link>

                <h3 class="text-xl font-semibold">{{ prototype.name }}</h3>
            </div>

            <div class="gap-x-2 flex items-center">
                <Button text :loading="isGenerating" :disabled="isGenerating" @click="generate">Generate</Button>
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
                            <Tab value="Formatted Requirements">Formatted Requirements</Tab>
                            <Tab value="Code">Code</Tab>
                            <Tab value="Preview">Preview</Tab>
                        </TabList>

                        <TabPanel value="Requirements" class="gap-2 p-2 h-full w-full overflow-hidden">
                            <vue-monaco-editor
                                language="markdown"
                                :value="prototype?.requirements ?? ''"
                                :theme="appTheme.isDark ? 'vs-dark' : 'vs'"
                            />
                        </TabPanel>

                        <TabPanel value="Formatted Requirements" class="gap-2 p-2 h-full w-full overflow-hidden">
                            <vue-monaco-editor
                                language="markdown"
                                :value="prototype?.formatted_requirements ?? ''"
                                :theme="appTheme.isDark ? 'vs-dark' : 'vs'"
                            />
                        </TabPanel>

                        <TabPanel value="Preview" class="gap-2 p-2 h-full w-full overflow-hidden">
                            <iframe
                                v-if="generatedHtml"
                                :srcdoc="generatedHtml"
                                class="rounded h-full w-full border-none"
                            />
                        </TabPanel>

                        <TabPanel value="Code" class="gap-2 p-2 h-full w-full overflow-hidden">
                            <vue-monaco-editor
                                language="html"
                                :value="generatedHtml"
                                :theme="appTheme.isDark ? 'vs-dark' : 'vs'"
                            />
                        </TabPanel>
                    </Tabs>
                </div>
            </SplitterPanel>
        </Splitter>
    </div>
</template>
