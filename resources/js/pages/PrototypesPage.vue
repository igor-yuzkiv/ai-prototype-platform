<script setup lang="ts">
import Button from 'primevue/button'
import Textarea from 'primevue/textarea'
import { formatDistanceToNow } from 'date-fns'
import { computed, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useCreatePrototypeMutation, useDeletePrototypeMutation } from '@/mutation'
import { usePrototypesListQuery } from '@/query'
import { Icon } from '@iconify/vue'
import { IconButton } from '@/components/button'
import { useConfirm } from '@/composables'
import { IPrototype } from '@/types/prototype.types'

const confirm = useConfirm()
const requirements = ref('')
const createPrototypeMutation = useCreatePrototypeMutation(() => {
    requirements.value = ''
})

const { data } = usePrototypesListQuery()
const prototypes = computed(() => data.value?.data ?? [])
const recentPrototypes = computed(() => prototypes.value)
const canCreatePrototype = computed(
    () =>
        requirements.value.trim().length > 0 &&
        !createPrototypeMutation.isPending.value &&
        !deleteMutation.isPending.value
)
const deleteMutation = useDeletePrototypeMutation()

function formatDate(dateString: string): string {
    return `Updated ${formatDistanceToNow(new Date(dateString), { addSuffix: true })}`
}

function createPrototype() {
    if (!requirements.value || createPrototypeMutation.isPending.value) {
        return
    }

    createPrototypeMutation.mutate({ requirements: requirements.value })
}

async function deletePrototype(prototype: IPrototype) {
    const confirmed = await confirm.requireAsync({
        message: `Delete "${prototype.name}"?`,
        icon: 'pi pi-trash',
        acceptLabel: 'Delete',
        rejectLabel: 'Cancel',
    })
    if (confirmed) deleteMutation.mutate(prototype.id)
}
</script>

<template>
    <div class="p-2 container mx-auto flex h-full flex-col overflow-hidden">
        <h1 class="my-10 leading-tight font-semibold text-center text-[28px]">What would you like to build today?</h1>

        <form class="mb-12 app-card" @submit.prevent="createPrototype">
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
                    :loading="createPrototypeMutation.isPending.value"
                    :disabled="!canCreatePrototype"
                />
            </div>
        </form>

        <section class="flex flex-1 flex-col overflow-hidden">
            <h2 class="mb-5 text-base font-semibold">Recent Prototypes</h2>

            <div
                v-if="recentPrototypes.length === 0"
                class="app-card rounded-lg p-8 text-sm text-gray-500 border border-dashed text-center"
            >
                No recent prototypes yet.
            </div>

            <div v-else class="gap-5 sm:grid-cols-3 grid grid-cols-1 overflow-auto">
                <div
                    v-for="prototype in recentPrototypes"
                    :key="prototype.id"
                    class="app-card rounded-md p-5 shadow-sm hover:shadow-md group flex flex-col border"
                >
                    <div class="mb-3 flex items-center justify-between">
                        <RouterLink class="gap-2 flex items-center" :to="`/prototypes/${prototype.id}`">
                            <div class="h-8 w-8 rounded bg-primary-500 text-white flex items-center justify-center">
                                <Icon icon="tdesign:app" />
                            </div>

                            <h3 class="font-semibold">{{ prototype.name }}</h3>
                        </RouterLink>

                        <IconButton
                            class="opacity-0 transition group-hover:opacity-100"
                            icon="ant-design:delete-outlined"
                            severity="danger"
                            text
                            :disabled="deleteMutation.isPending.value || createPrototypeMutation.isPending.value"
                            @click.stop="deletePrototype(prototype)"
                        />
                    </div>

                    <p class="mb-5 text-xs font-medium text-gray-500 line-clamp-3">
                        {{ prototype.requirements }}
                    </p>

                    <div class="gap-1.5 text-xs font-medium text-gray-500 mt-auto flex items-center">
                        <Icon icon="mdi:clock-outline" />
                        <span>{{ formatDate(prototype.updated_at) }}</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
