<script setup lang="ts">
import Button from 'primevue/button'
import Textarea from 'primevue/textarea'
import { formatDistanceToNow } from 'date-fns'
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { ProjectCardSkeleton } from '@/components/project'
import { useCreateProjectMutation } from '@/mutation'
import { useProjectsListQuery } from '@/query'

const requirements = ref('')
const createProjectMutation = useCreateProjectMutation(() => {
    requirements.value = ''
})

const { data, isLoading } = useProjectsListQuery()

const projects = computed(() => data.value?.data ?? [])
const recentProjects = computed(() => projects.value.slice(0, 3))
const canCreateProject = computed(() => requirements.value.trim().length > 0 && !createProjectMutation.isPending.value)

function formatDate(dateString: string): string {
    return `Updated ${formatDistanceToNow(new Date(dateString), { addSuffix: true })}`
}

function createProject() {
    const trimmedRequirements = requirements.value.trim()

    if (!trimmedRequirements || createProjectMutation.isPending.value) {
        return
    }

    createProjectMutation.mutate({ requirements: trimmedRequirements })
}
</script>

<template>
    <div class="h-full overflow-auto bg-[#f8f8f9] px-6 py-14">
        <main class="mx-auto w-full max-w-[778px]">
            <h1 class="mb-9 text-center text-[28px] leading-tight font-semibold tracking-[-0.02em] text-[#111827]">
                What would you like to build today?
            </h1>

            <form
                class="mb-[70px] overflow-hidden rounded-lg border border-[#e5e7eb] bg-white shadow-sm"
                @submit.prevent="createProject"
            >
                <Textarea
                    v-model="requirements"
                    class="min-h-[120px] w-full resize-none border-0 px-5 py-6 text-sm text-[#111827] shadow-none outline-none"
                    placeholder="Describe your prototype idea... (e.g., A real estate dashboard with a map view and property list)"
                    auto-resize
                    fluid
                    :pt="{
                        root: {
                            class: 'border-0 shadow-none rounded-none focus:ring-0 focus:outline-none',
                        },
                    }"
                />

                <div class="flex min-h-[52px] items-center justify-between border-t border-[#e5e7eb] px-5 py-3">
                    <p class="text-xs font-medium text-[#7b8794]">
                        Describe layout, features, and style in plain English.
                    </p>
                    <Button
                        type="submit"
                        label="Generate Prototype"
                        icon="pi pi-sparkles"
                        size="small"
                        class="rounded-md bg-[#1f75ff] px-4 py-2 text-sm font-medium"
                        :loading="createProjectMutation.isPending.value"
                        :disabled="!canCreateProject"
                    />
                </div>
            </form>

            <section>
                <h2 class="mb-5 text-base font-semibold text-[#1f2937]">Recent Projects</h2>

                <div v-if="isLoading" class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <ProjectCardSkeleton v-for="n in 6" :key="n" />
                </div>

                <div
                    v-else-if="recentProjects.length === 0"
                    class="rounded-lg border border-dashed border-[#d9dee7] bg-white p-8 text-center text-sm text-[#7b8794]"
                >
                    No recent projects yet.
                </div>

                <div v-else class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <RouterLink
                        v-for="(project, index) in recentProjects"
                        :key="project.id"
                        :to="`/projects/${project.id}`"
                        class="flex min-h-[190px] flex-col rounded-md border border-[#e5e7eb] bg-white p-[17px] no-underline shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <div
                            class="mb-[18px] flex h-8 w-8 items-center justify-center rounded bg-[#f3f4f6] text-[#374151]"
                        >
                            <i
                                class="pi text-base"
                                :class="{
                                    'pi-th-large': index === 0,
                                    'pi-inbox': index === 1,
                                    'pi-comment': index === 2,
                                }"
                            />
                        </div>

                        <h3 class="mb-2 text-sm leading-tight font-semibold text-[#1f2937]">
                            {{ project.name }}
                        </h3>

                        <p class="mb-5 line-clamp-3 text-xs leading-[1.45] font-medium text-[#7b8794]">
                            {{ project.requirements }}
                        </p>

                        <div class="mt-auto flex items-center gap-1.5 text-[11px] font-medium text-[#8b95a1]">
                            <i class="pi pi-clock text-[11px]" />
                            <span>{{ formatDate(project.updated_at) }}</span>
                        </div>
                    </RouterLink>
                </div>
            </section>
        </main>
    </div>
</template>
