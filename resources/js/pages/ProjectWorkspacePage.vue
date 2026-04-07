<script setup lang="ts">
import Button from 'primevue/button'
import ProgressSpinner from 'primevue/progressspinner'
import Skeleton from 'primevue/skeleton'
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useConfirm } from '@/composables'
import { useDeleteProjectMutation } from '@/mutation'
import { useProjectQuery } from '@/query'

const route = useRoute()
const router = useRouter()
const confirm = useConfirm()
const deleteMutation = useDeleteProjectMutation()

const projectId = computed(() => Number(route.params.id))

const projectQuery = useProjectQuery(projectId)

const project = computed(() => projectQuery.data.value)

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
</template>
