<script setup lang="ts">
import Button from 'primevue/button'
import ProgressSpinner from 'primevue/progressspinner'
import Skeleton from 'primevue/skeleton'
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useConfirm } from '@/composables'
import { useDeleteProjectMutation } from '@/mutation'
import { useProjectArtifactsQuery, useProjectQuery } from '@/query'

const route = useRoute()
const router = useRouter()
const confirm = useConfirm()
const deleteMutation = useDeleteProjectMutation()

const projectId = computed(() => Number(route.params.id))

const projectQuery = useProjectQuery(projectId)
const artifactsQuery = useProjectArtifactsQuery(projectId)

const project = computed(() => projectQuery.data.value)
const artifacts = computed(() => artifactsQuery.data.value?.data ?? [])

async function deleteProject() {
    const confirmed = await confirm.requireAsync({
        message: `Delete "${project.value?.name}"?`,
        icon: 'pi pi-trash',
        acceptLabel: 'Delete',
        rejectLabel: 'Cancel',
    })
    if (confirmed) {
        deleteMutation.mutate(projectId.value, {
            onSuccess: () => router.push('/'),
        })
    }
}
</script>

<template>
    <!-- Full-page loading -->
    <div v-if="projectQuery.isPending.value" class="flex h-screen items-center justify-center">
        <ProgressSpinner />
    </div>

    <!-- Error state -->
    <div v-else-if="projectQuery.isError.value" class="flex h-screen flex-col items-center justify-center gap-4">
        <p class="text-stone-600">Failed to load project</p>
        <Button label="Retry" icon="pi pi-refresh" @click="projectQuery.refetch()" />
    </div>

    <!-- Workspace -->
    <div v-else class="flex h-screen flex-col">
        <!-- Header bar -->
        <header class="flex h-14 shrink-0 items-center gap-3 border-b border-stone-200 bg-white px-4">
            <Button icon="pi pi-arrow-left" variant="text" rounded aria-label="Back" @click="router.push('/')" />

            <div class="flex-1">
                <Skeleton v-if="projectQuery.isLoading.value" width="12rem" height="1.25rem" />
                <span v-else class="font-semibold text-stone-900">
                    {{ project?.name }}
                </span>
            </div>

            <!-- Artifact count badge -->
            <span
                v-if="artifactsQuery.isSuccess.value && artifacts.length > 0"
                class="rounded-full bg-stone-100 px-2.5 py-0.5 text-xs font-medium text-stone-600"
            >
                {{ artifacts.length }} artifact{{ artifacts.length === 1 ? '' : 's' }}
            </span>

            <Button
                icon="pi pi-trash"
                variant="text"
                severity="danger"
                rounded
                :loading="deleteMutation.isPending.value"
                aria-label="Delete project"
                @click="deleteProject"
            />
        </header>

        <!-- Main area -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Left sidebar -->
            <aside class="flex w-64 shrink-0 flex-col gap-3 overflow-y-auto border-r border-stone-200 bg-stone-50 p-4">
                <h2 class="text-sm font-semibold tracking-wide text-stone-500 uppercase">Artifacts</h2>

                <ul v-if="artifacts.length > 0" class="space-y-1">
                    <li
                        v-for="artifact in artifacts"
                        :key="artifact.id"
                        class="truncate rounded px-2 py-1.5 text-sm text-stone-700 hover:bg-stone-200"
                    >
                        {{ artifact.name }}
                    </li>
                </ul>

                <p v-else class="text-sm text-stone-400">No artifacts</p>
            </aside>

            <!-- Main content -->
            <main class="relative flex flex-1 items-center justify-center overflow-hidden">
                <iframe
                    v-if="project?.prototype_url"
                    :src="project.prototype_url"
                    class="h-full w-full border-0"
                    title="Prototype"
                />

                <div v-else class="flex flex-col items-center gap-4 text-stone-500">
                    <ProgressSpinner />
                    <p class="text-sm">Prototype is being generated...</p>
                </div>
            </main>
        </div>
    </div>
</template>
