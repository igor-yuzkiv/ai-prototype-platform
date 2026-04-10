<script setup lang="ts">
import { useRouteParams } from '@vueuse/router'
import { usePrototypeQuery } from '@/query'
import { Icon } from '@iconify/vue'
import { LoadingOverlay } from '@/shared/components/loading'
import { PrototypeWorkspaceFlow } from '@/components/prototype-workspace'
import { PrototypePageDetailDialog, usePrototypePageDetailsDialog } from '@/components/prototype-page'
import { ref } from 'vue'
import { prototypesApi } from '@/api/prototypes.api'
import { IconButton } from '@/shared/components/button'

const prototypeId = useRouteParams<string>('id')
const { prototype, pages, isLoading: isLoadingPrototype, refetch } = usePrototypeQuery(prototypeId)
const pageDialog = usePrototypePageDetailsDialog()
const isPublishing = ref(false)

async function publish() {
    isPublishing.value = true

    try {
        await prototypesApi.publish(prototypeId.value)
        await refetch()
    } finally {
        isPublishing.value = false
    }
}
</script>

<template>
    <LoadingOverlay v-if="isLoadingPrototype" />
    <div v-if="prototype" class="p-2 gap-2 flex h-full w-full flex-col overflow-hidden relative">
        <PrototypeWorkspaceFlow class="flex-1" :prototype="prototype" :pages="pages" @page:click="pageDialog.open" />

        <div
            class="absolute absolute m-auto bottom-3 left-0 right-0 bg-primary w-[90%] md:w-1/3 rounded-lg shadow-md flex items-center justify-between p-2">
            <div class="flex items-center gap-x-2">
                <router-link
                    to="/"
                    class="p-1 rounded bg-primary-500 text-white group flex items-center justify-center"
                >
                    <Icon icon="tdesign:app" class="w-6 h-6 group-hover:hidden" />
                    <Icon icon="material-symbols:arrow-back-rounded" class="w-6 h-6 hidden group-hover:flex" />
                </router-link>

                <h3 class="font-semibold">{{ prototype.name }}</h3>
            </div>

            <div class="flex items-center gap-x-2">
                <IconButton
                    v-if="prototype.prototype_url"
                    icon="majesticons:open"
                    v-tooltip="{value: 'Open Prototype'}"
                    text as="a" :href="prototype.prototype_url"
                />

                <IconButton
                    icon="mynaui:share-solid"
                    v-tooltip="{value: 'Publish Prototype'}"
                    text
                    :disabled="isPublishing"
                    @click="publish"
                />
            </div>
        </div>
    </div>

    <PrototypePageDetailDialog v-model:visible="pageDialog.visible.value" :page="pageDialog.currentPage.value" />
</template>
