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
    <div class="flex h-full w-full flex-col overflow-auto bg-gray-50 px-6 py-14">
        <main class="container mx-auto flex h-full flex-col overflow-hidden">
            <h1 class="mb-9 text-center text-[28px] leading-tight font-semibold">
                What would you like to build today?
            </h1>

            <form class="mb-12 overflow-hidden rounded-lg border bg-white shadow-sm" @submit.prevent="createProject">
                <Textarea
                    v-model.trim="requirements"
                    class="w-full resize-none border-0 px-5 py-6 text-sm shadow-none outline-none"
                    placeholder="Describe your prototype idea... (e.g., A real estate dashboard with a map view and property list)"
                    auto-resize
                    fluid
                    :pt="{
                        root: {
                            class: 'border-0 shadow-none rounded-none focus:ring-0 focus:outline-none',
                        },
                    }"
                />

                <div class="flex items-center justify-between border-t px-5 py-3">
                    <p class="text-xs font-medium text-gray-500">
                        Describe layout, features, and style in plain English.
                    </p>

                    <Button
                        type="submit"
                        label="Generate Prototype"
                        icon="pi pi-sparkles"
                        size="small"
                        :loading="createProjectMutation.isPending.value"
                        :disabled="!canCreateProject"
                    />
                </div>
            </form>

            <section>
                <h2 class="mb-5 text-base font-semibold">Recent Projects</h2>

                <div
                    v-if="recentProjects.length === 0"
                    class="rounded-lg border border-dashed bg-white p-8 text-center text-sm text-gray-500"
                >
                    No recent projects yet.
                </div>

                <div v-else class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <RouterLink
                        v-for="project in recentProjects"
                        :key="project.id"
                        :to="`/projects/${project.id}`"
                        class="flex flex-col rounded-md border bg-white p-5 no-underline shadow-sm transition hover:shadow-md"
                    >
                        <div class="mb-3 flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded bg-primary-500 text-white">
                                <Icon icon="tdesign:app" />
                            </div>

                            <h3 class="font-semibold">{{ project.name }}</h3>
                        </div>

                        <p class="mb-5 line-clamp-3 text-xs font-medium text-gray-500">
                            {{ project.requirements }}
                        </p>

                        <div class="mt-auto flex items-center gap-1.5 text-xs font-medium text-gray-500">
                            <Icon icon="mdi:clock-outline" />
                            <span>{{ formatDate(project.updated_at) }}</span>
                        </div>
                    </RouterLink>
                </div>
            </section>
        </main>
    </div>
</template>
