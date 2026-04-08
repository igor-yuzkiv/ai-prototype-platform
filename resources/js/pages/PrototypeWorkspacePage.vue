<script setup lang="ts">
import { useRouteParams } from '@vueuse/router'
import { usePrototypeQuery } from '@/query'
import { Icon } from '@iconify/vue'
import { LoadingOverlay } from '@/shared/components/loading'
import { PrototypeWorkspaceFlow } from '@/components/prototype-workspace'

const prototypeId = useRouteParams<string>('id')
const { prototype, pages, isLoading: isLoadingPrototype } = usePrototypeQuery(prototypeId)
</script>

<template>
    <LoadingOverlay v-if="isLoadingPrototype" />
    <div v-if="prototype" class="flex h-full w-full flex-col overflow-hidden">
        <div class="p-2 flex items-center justify-between border-b">
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

        <div class="flex flex-1 flex-col p-2">
            <PrototypeWorkspaceFlow :screens="pages" />
        </div>
    </div>
</template>
