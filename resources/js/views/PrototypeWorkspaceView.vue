<script setup lang="ts">
import { useRouteParams } from '@vueuse/router'
import { usePrototypeQuery } from '@/query'
import { Icon } from '@iconify/vue'
import { LoadingOverlay } from '@/shared/components/loading'
import { PrototypeWorkspaceFlow } from '@/components/prototype-workspace'
import { PrototypePageDetailDialog, usePrototypePageDetailsDialog } from '@/components/prototype-page'
import { computed, ref } from 'vue'
import { prototypesApi } from '@/api/prototypes.api'
import { IconButton } from '@/shared/components/button'
import { serverEventBus } from '@/server-event-bus'
import { PrototypeStatusChangedEventPayload } from '@/types/prototype.types'

const prototypeId = useRouteParams<string>('id')
const { isPending, data: prototype, pages, refetch } = usePrototypeQuery(prototypeId)
const pageDialog = usePrototypePageDetailsDialog()
const isPublishing = ref(false)

const loadingState = computed<string | null>(() => {
    if (!prototype.value || isPending.value) {
        return 'Loading...'
    }

    if (prototype.value.status === 'new') {
        return 'Planning..'
    }

    return null
})

async function handlePublishPrototype() {
    isPublishing.value = true

    try {
        await prototypesApi.publish(prototypeId.value)
        await refetch()
    } finally {
        isPublishing.value = false
    }
}

serverEventBus.on<PrototypeStatusChangedEventPayload>(`project.${prototypeId.value}.status.changed`, (payload) => {
    console.log('Received prototype status changed event:', { payload })
    if (payload.data.status === 'planned') {
        refetch()
    }
})
</script>

<template>
    <LoadingOverlay v-if="loadingState" :message="loadingState" />

    <div v-if="prototype" class="p-2 gap-2 dotted-background relative flex h-full w-full flex-col overflow-hidden">
        <PrototypeWorkspaceFlow class="flex-1" :prototype="prototype" :pages="pages" @page:click="pageDialog.open" />

        <div
            class="top-3 left-0 right-0 bg-primary md:w-1/3 rounded-lg shadow-md p-2 absolute m-auto flex w-[90%] items-center justify-between"
        >
            <div class="gap-x-2 flex items-center">
                <router-link
                    to="/"
                    class="p-2 rounded bg-primary-700 text-white group flex items-center justify-center"
                >
                    <Icon icon="tdesign:app" class="w-6 h-6 group-hover:hidden" />
                    <Icon icon="material-symbols:arrow-back-rounded" class="w-6 h-6 hidden group-hover:flex" />
                </router-link>

                <h3 class="font-semibold">{{ prototype.name }}</h3>
            </div>

            <div class="gap-x-2 flex items-center">
                <IconButton
                    v-if="prototype.prototype_url"
                    icon="majesticons:open"
                    v-tooltip="{ value: 'Open Prototype' }"
                    text
                    as="a"
                    :href="prototype.prototype_url"
                />

                <IconButton
                    icon="mynaui:share-solid"
                    v-tooltip="{ value: 'Publish Prototype' }"
                    text
                    :disabled="isPublishing"
                    @click="handlePublishPrototype"
                />
            </div>
        </div>
    </div>

    <PrototypePageDetailDialog v-model:visible="pageDialog.visible.value" :page="pageDialog.currentPage.value" />
</template>
