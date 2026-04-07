<script setup lang="ts">
import Button from 'primevue/button'
import Card from 'primevue/card'
import Textarea from 'primevue/textarea'
import { format } from 'date-fns'
import { computed, ref } from 'vue'
import { ProjectCardSkeleton } from '@/components/project'
import { useConfirm } from '@/composables'
import { useCreateProjectMutation, useDeleteProjectMutation } from '@/mutation'
import { useProjectsListQuery } from '@/query'
import type { IProjectSummary } from '@/types/project.types'

const requirements = ref('')
const confirm = useConfirm()
const createProjectMutation = useCreateProjectMutation(() => {
    requirements.value = ''
})
const deleteMutation = useDeleteProjectMutation()

const { data, isLoading } = useProjectsListQuery()

const projects = computed(() => data.value?.data ?? [])
const canCreateProject = computed(() => requirements.value.trim().length > 0 && !createProjectMutation.isPending.value)

function formatDate(dateString: string): string {
    return format(new Date(dateString), 'MMM d, yyyy')
}

function createProject() {
    const trimmedRequirements = requirements.value.trim()

    if (!trimmedRequirements || createProjectMutation.isPending.value) {
        return
    }

    createProjectMutation.mutate({ requirements: trimmedRequirements })
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
    <div class="h-full overflow-auto bg-[#f8f8fb] px-4 py-6">
        <!-- Header -->
        <div class="mx-auto flex min-h-full w-full max-w-6xl flex-col">
            <div class="mb-6">
                <p class="text-sm font-medium text-slate-400">Prototype platform</p>
                <h1 class="text-3xl font-semibold text-slate-950">Projects</h1>
            </div>

            <form
                class="mb-8 rounded-[28px] border border-slate-200 bg-white p-4 shadow-sm"
                @submit.prevent="createProject"
            >
                <label for="project-requirements" class="mb-3 block text-sm font-semibold text-slate-950">
                    Describe what you want to prototype
                </label>

                <Textarea
                    id="project-requirements"
                    v-model="requirements"
                    class="w-full"
                    placeholder="Write the requirements for your prototype..."
                    :rows="4"
                    fluid
                />

                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-400">Project name will be generated automatically.</p>
                    <Button
                        type="submit"
                        label="Create prototype"
                        icon="pi pi-arrow-up-right"
                        :loading="createProjectMutation.isPending.value"
                        :disabled="!canCreateProject"
                    />
                </div>
            </form>

            <!-- Loading skeleton -->
            <div v-if="isLoading" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <ProjectCardSkeleton v-for="n in 6" :key="n" />
            </div>

            <!-- Empty state -->
            <div
                v-else-if="projects.length === 0"
                class="flex min-h-[420px] flex-1 flex-col items-center justify-center rounded-[28px] border border-dashed border-slate-200 bg-[#fbfbfd] px-6 py-16 text-center"
            >
                <div class="mb-12 flex h-28 w-28 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                    <i class="pi pi-folder text-4xl" />
                </div>

                <h2 class="mb-6 text-3xl font-semibold text-slate-950">
                    No projects yet &mdash; create your first prototype
                </h2>

                <p class="max-w-2xl text-2xl leading-relaxed text-slate-400">
                    Your archived and completed prototypes will appear here for future reference.
                </p>
            </div>

            <!-- Projects grid -->
            <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
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
                            {{ project.requirements }}
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
    </div>
</template>
