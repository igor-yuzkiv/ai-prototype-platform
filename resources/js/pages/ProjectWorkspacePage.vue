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
import { httpClient } from '@/api'
import { onBeforeUnmount, ref, toValue } from 'vue'

const projectId = useRouteParams<string>('id')

const { data: project } = useProjectQuery(projectId)
const appTheme = useThemeStore()

const generatedHtml = ref<string>('')
const isGenerating = ref(false)
const generationError = ref<string | null>(null)

let abortController: AbortController | null = null

type StreamEventPayload = {
    type?: string
    delta?: string
    message?: string
    errorText?: string
}

function getCookieValue(name: string): string | null {
    const encodedValue = document.cookie
        .split('; ')
        .find((cookie) => cookie.startsWith(`${name}=`))
        ?.split('=')
        .slice(1)
        .join('=')

    if (!encodedValue) {
        return null
    }

    try {
        return decodeURIComponent(encodedValue)
    } catch {
        return encodedValue
    }
}

function getStreamRequestHeaders(): Record<string, string> {
    const headers: Record<string, string> = {
        Accept: 'text/event-stream',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    }

    const xsrfToken = getCookieValue('XSRF-TOKEN')

    if (xsrfToken) {
        headers['X-XSRF-TOKEN'] = xsrfToken
    }

    return headers
}

function readStreamEventData(eventChunk: string): string | null {
    const data = eventChunk
        .split('\n')
        .map((line) => line.trimEnd())
        .filter((line) => line.startsWith('data:'))
        .map((line) => line.slice(5).trimStart())
        .join('\n')

    return data || null
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

function appendStreamEvent(eventChunk: string) {
    const data = readStreamEventData(eventChunk)

    if (!data) {
        return
    }

    const payload = parseStreamPayload(data)

    if (!payload) {
        return
    }

    if ((payload.type === 'text_delta' || payload.type === 'text-delta') && payload.delta) {
        generatedHtml.value += payload.delta
        return
    }

    if (payload.type === 'error') {
        generationError.value = payload.errorText ?? payload.message ?? 'Generation failed.'
    }
}

async function generate() {
    abortController?.abort()

    const controller = new AbortController()
    abortController = controller

    generatedHtml.value = ''
    generationError.value = null
    isGenerating.value = true

    try {
        const response = await fetch(
            httpClient.getUri({
                url: `/projects/${toValue(projectId)}/prototype/generate`,
            }),
            {
                method: 'POST',
                credentials: 'include',
                headers: getStreamRequestHeaders(),
                signal: controller.signal,
            }
        )

        if (!response.ok) {
            throw new Error(`Generation failed with status ${response.status}.`)
        }

        const reader = response.body?.getReader()

        if (!reader) {
            throw new Error('Streaming is not supported by this browser.')
        }

        const decoder = new TextDecoder()
        let buffer = ''

        while (true) {
            const { done, value } = await reader.read()

            if (done) {
                break
            }

            buffer += decoder.decode(value, { stream: true })

            const events = buffer.split('\n\n')
            buffer = events.pop() ?? ''

            events.forEach(appendStreamEvent)
        }

        buffer += decoder.decode()

        if (buffer) {
            appendStreamEvent(buffer)
        }
    } catch (error) {
        if (error instanceof DOMException && error.name === 'AbortError') {
            return
        }

        generationError.value = error instanceof Error ? error.message : 'Generation failed.'
    } finally {
        if (abortController === controller) {
            isGenerating.value = false
            abortController = null
        }
    }
}

onBeforeUnmount(() => {
    abortController?.abort()
})
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
                <span v-if="generationError" class="text-sm text-red-500">{{ generationError }}</span>
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
                            <Tab value="Preview">Preview</Tab>
                            <Tab value="Code">Code</Tab>
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
