<script setup lang="ts">
import Button from 'primevue/button'
import Textarea from 'primevue/textarea'
import { formatDistanceToNow } from 'date-fns'
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useCreateProjectMutation } from '@/mutation'
import { useProjectsListQuery } from '@/query'
import { Icon } from '@iconify/vue'

const requirements = ref('')
const createProjectMutation = useCreateProjectMutation(() => {
    requirements.value = ''
})

const { data } = useProjectsListQuery()

const projects = computed(() => data.value?.data ?? [])
const recentProjects = computed(() => projects.value)
const canCreateProject = computed(() => requirements.value.trim().length > 0 && !createProjectMutation.isPending.value)

function formatDate(dateString: string): string {
    return `Updated ${formatDistanceToNow(new Date(dateString), { addSuffix: true })}`
}

function createProject() {
    if (!requirements.value || createProjectMutation.isPending.value) {
        return
    }

    createProjectMutation.mutate({ requirements: requirements.value })
}
</script>

<template>
    <div class="px-6 py-14 flex h-full w-full flex-col overflow-hidden">
        <main class="container mx-auto flex h-full flex-col overflow-hidden">
            <h1 class="mb-9 leading-tight font-semibold text-center text-[28px]">
                What would you like to build today?
            </h1>

            <form class="mb-12 rounded-lg shadow-sm app-card border" @submit.prevent="createProject">
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
                    <RouterLink
                        v-for="project in recentProjects"
                        :key="project.id"
                        :to="`/projects/${project.id}`"
                        class="app-card rounded-md p-5 shadow-sm hover:shadow-md flex flex-col border no-underline transition"
                    >
                        <div class="mb-3 gap-2 flex items-center">
                            <div class="h-8 w-8 rounded bg-primary-500 text-white flex items-center justify-center">
                                <Icon icon="tdesign:app" />
                            </div>

                            <h3 class="font-semibold">{{ project.name }}</h3>
                        </div>

                        <p class="mb-5 text-xs font-medium text-gray-500 line-clamp-3">
                            {{ project.requirements }}
                        </p>

                        <div class="gap-1.5 text-xs font-medium text-gray-500 mt-auto flex items-center">
                            <Icon icon="mdi:clock-outline" />
                            <span>{{ formatDate(project.updated_at) }}</span>
                        </div>
                    </RouterLink>
                </div>
            </section>
        </main>
    </div>
</template>
