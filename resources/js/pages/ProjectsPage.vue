<script setup lang="ts">
import Button from 'primevue/button'
import Card from 'primevue/card'
import { format } from 'date-fns'
import { computed, ref } from 'vue'
import { CreateProjectModal } from '@/components/project'
import { useConfirm } from '@/composables'
import { useDeleteProjectMutation } from '@/mutation'
import { useProjectsListQuery } from '@/query'
import type { IProjectSummary } from '@/types/project.types'

const showCreateModal = ref(false)
const confirm = useConfirm()
const deleteMutation = useDeleteProjectMutation()

const { data, isLoading } = useProjectsListQuery()

const projects = computed(() => data.value?.data ?? [])

function formatDate(dateString: string): string {
    return format(new Date(dateString), 'MMM d, yyyy')
}

async function deleteProject(project: IProjectSummary) {
    const confirmed = await confirm.requireAsync({
        message: `Delete "${project.name}"?`,
        icon: 'pi pi-trash',
        acceptLabel: 'Delete',
        rejectLabel: 'Cancel',
    })
    if (confirmed) deleteMutation.mutate(project.id)
}
</script>

<template>
    <div class="flex h-full w-full flex-col overflow-hidden bg-stone-50">
        <div class="mx-auto max-w-7xl px-6 py-10">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <h1 class="text-3xl font-semibold text-stone-900">Projects</h1>
                <Button label="New Project" icon="pi pi-plus" @click="showCreateModal = true" />
            </div>

            <!-- Loading skeleton -->
            <div v-if="isLoading" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="n in 6" :key="n" class="animate-pulse rounded-xl border border-stone-200 bg-white p-6">
                    <div class="mb-3 h-5 w-2/3 rounded bg-stone-200"></div>
                    <div class="mb-2 h-4 w-full rounded bg-stone-100"></div>
                    <div class="mb-6 h-4 w-4/5 rounded bg-stone-100"></div>
                    <div class="flex items-center justify-between">
                        <div class="h-4 w-24 rounded bg-stone-100"></div>
                        <div class="h-8 w-16 rounded bg-stone-200"></div>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div v-else-if="projects.length === 0" class="flex flex-col items-center justify-center py-24 text-center">
                <p class="mb-2 text-lg font-medium text-stone-600">No projects yet</p>
                <p class="mb-6 text-sm text-stone-400">Get started by creating your first project.</p>
                <Button label="Create your first project" icon="pi pi-plus" @click="showCreateModal = true" />
            </div>

            <!-- Projects grid -->
            <div v-else class="grid grid-cols-1 gap-6 overflow-auto sm:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="project in projects"
                    :key="project.id"
                    class="border border-stone-200 bg-white shadow-sm transition-shadow hover:shadow-md"
                >
                    <template #title>
                        <span class="text-base font-semibold text-stone-900">{{ project.name }}</span>
                    </template>
                    <template #content>
                        <p class="line-clamp-2 text-sm text-stone-500">
                            {{ project.description }}
                        </p>
                    </template>
                    <template #footer>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-stone-400">{{ formatDate(project.created_at) }}</span>
                            <div class="flex items-center gap-1">
                                <Button
                                    icon="pi pi-trash"
                                    variant="text"
                                    severity="danger"
                                    size="small"
                                    :loading="
                                        deleteMutation.isPending.value && deleteMutation.variables.value === project.id
                                    "
                                    @click="deleteProject(project)"
                                />
                                <Button
                                    as="router-link"
                                    :to="`/projects/${project.id}`"
                                    label="Open"
                                    size="small"
                                    variant="outlined"
                                />
                            </div>
                        </div>
                    </template>
                </Card>
            </div>
        </div>

        <CreateProjectModal v-model:visible="showCreateModal" />
    </div>
</template>
