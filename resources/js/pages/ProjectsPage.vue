<script setup lang="ts">
import Button from 'primevue/button'
import Textarea from 'primevue/textarea'
import { formatDistanceToNow } from 'date-fns'
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useCreateProjectMutation, useDeleteProjectMutation } from '@/mutation'
import { useProjectsListQuery } from '@/query'
import { Icon } from '@iconify/vue'
import { IconButton } from '@/components/button'
import { useConfirm } from '@/composables'
import { IProject } from '@/types/project.types'

const confirm = useConfirm()
const requirements = ref('')
const createProjectMutation = useCreateProjectMutation(() => {
    requirements.value = ''
})

const { data } = useProjectsListQuery()
const projects = computed(() => data.value?.data ?? [])
const recentProjects = computed(() => projects.value)
const canCreateProject = computed(() => requirements.value.trim().length > 0 && !createProjectMutation.isPending.value && !deleteMutation.isPending.value)
const deleteMutation = useDeleteProjectMutation()

function formatDate(dateString: string): string {
    return `Updated ${formatDistanceToNow(new Date(dateString), { addSuffix: true })}`
}

function createProject() {
    if (!requirements.value || createProjectMutation.isPending.value) {
        return
    }

    createProjectMutation.mutate({ requirements: requirements.value })
}

async function deleteProject(project: IProject) {
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
    <div class="p-2 container mx-auto flex h-full flex-col overflow-hidden">
        <h1 class="my-10 leading-tight font-semibold text-center text-[28px]">What would you like to build today?</h1>

        <form class="mb-12 app-card" @submit.prevent="createProject">
            <Textarea
                v-model.trim="requirements"
                class="border-none bg-transparent shadow-none"
                rows="10"
                placeholder="Describe your prototype idea... (e.g., A real estate dashboard with a map view and property list)"
                fluid
                style="resize: none"
            />

            <div class="p-1 flex items-center justify-end">
                <Button
                    text
                    type="submit"
                    label="Generate Prototype"
                    icon="pi pi-sparkles"
                    size="small"
                    :loading="createProjectMutation.isPending.value"
                    :disabled="!canCreateProject"
                />
            </div>
        </form>

        <section class="flex flex-1 flex-col overflow-hidden">
            <h2 class="mb-5 text-base font-semibold">Recent Projects</h2>

            <div
                v-if="recentProjects.length === 0"
                class="app-card rounded-lg p-8 text-sm text-gray-500 border border-dashed text-center"
            >
                No recent projects yet.
            </div>

            <div v-else class="gap-5 sm:grid-cols-3 grid grid-cols-1 overflow-auto">
                <div
                    v-for="project in recentProjects"
                    :key="project.id"
                    class="app-card rounded-md p-5 shadow-sm hover:shadow-md group flex flex-col border"
                >
                    <div class="mb-3 flex items-center justify-between">
                        <RouterLink class="gap-2 flex items-center" :to="`/projects/${project.id}`">
                            <div class="h-8 w-8 rounded bg-primary-500 text-white flex items-center justify-center">
                                <Icon icon="tdesign:app" />
                            </div>

                            <h3 class="font-semibold">{{ project.name }}</h3>
                        </RouterLink>

                        <IconButton
                            class="opacity-0 transition group-hover:opacity-100"
                            icon="ant-design:delete-outlined"
                            severity="danger"
                            text
                            :disabled="deleteMutation.isPending.value || createProjectMutation.isPending.value"
                            @click.stop="deleteProject(project)"
                        />
                    </div>

                    <p class="mb-5 text-xs font-medium text-gray-500 line-clamp-3">
                        {{ project.requirements }}
                    </p>

                    <div class="gap-1.5 text-xs font-medium text-gray-500 mt-auto flex items-center">
                        <Icon icon="mdi:clock-outline" />
                        <span>{{ formatDate(project.updated_at) }}</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
