<script setup lang="ts">
import Button from 'primevue/button'
import Textarea from 'primevue/textarea'
import { computed, ref } from 'vue'
import { useCreatePrototypeMutation, useDeletePrototypeMutation } from '@/mutation'
import { usePrototypesListQuery } from '@/query'
import { IconButton } from '@/components/button'
import { useConfirm } from '@/composables'
import { IPrototypeSummary } from '@/types/prototype.types'
import { PrototypeCard } from '@/components/prototype'
import { NoDataMessage } from '@/components/typography'
import { LoadingOverlay } from '@/components/loading'

const confirm = useConfirm()

const { prototypes, isLoading: isLoadingPrototypes } = usePrototypesListQuery()

const createPrototypeMutation = useCreatePrototypeMutation(() => (initialRequirements.value = ''))

const initialRequirements = ref('')

const isLoading = computed(() => isLoadingPrototypes.value || createPrototypeMutation.isPending.value)

const canCreatePrototype = computed(
    () =>
        initialRequirements.value.trim().length > 0 &&
        !createPrototypeMutation.isPending.value &&
        !deleteMutation.isPending.value
)

const deleteMutation = useDeletePrototypeMutation()

function createPrototype() {
    if (canCreatePrototype.value) {
        createPrototypeMutation.mutate({ initial_requirements: initialRequirements.value })
    }
}

async function deletePrototype(prototype: IPrototypeSummary) {
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
    <LoadingOverlay v-if="isLoading" message="Loading" />
    <div class="p-2 container mx-auto flex h-full flex-col overflow-hidden">
        <h1 class="my-10 leading-tight font-semibold text-center text-[28px]">What would you like to build today?</h1>

        <form class="mb-12 app-card" @submit.prevent="createPrototype">
            <Textarea
                v-model.trim="initialRequirements"
                class="border-none bg-transparent shadow-none"
                rows="5"
                placeholder="Describe your prototype idea... (e.g., A real estate dashboard with a map view and property list)"
                fluid
                style="resize: none"
            />

            <div class="p-1 flex items-center justify-end">
                <Button
                    text
                    type="submit"
                    label="Let`s cook!"
                    icon="pi pi-sparkles"
                    size="small"
                    :loading="createPrototypeMutation.isPending.value"
                    :disabled="!canCreatePrototype"
                />
            </div>
        </form>

        <section class="flex flex-1 flex-col overflow-hidden">
            <h2 class="mb-5 text-base font-semibold">Recent Prototypes</h2>

            <NoDataMessage
                v-if="prototypes.length === 0"
                class="app-card p-2 flex-1 border border-dashed"
                title="No Prototypes Yet"
                message="Your recently generated prototypes will appear here."
                icon="ant-design:experiment-outlined"
            />

            <div v-else class="gap-5 sm:grid-cols-3 grid grid-cols-1 overflow-auto">
                <PrototypeCard
                    v-for="prototype in prototypes"
                    :key="prototype.id"
                    :prototype="prototype"
                    is="router-link"
                    :to="`/prototypes/${prototype.id}`"
                >
                    <template #actions>
                        <IconButton
                            class="opacity-0 transition group-hover:opacity-100"
                            icon="ant-design:delete-outlined"
                            severity="danger"
                            text
                            :disabled="deleteMutation.isPending.value || createPrototypeMutation.isPending.value"
                            @click.prevent="deletePrototype(prototype)"
                        />
                    </template>
                </PrototypeCard>
            </div>
        </section>
    </div>
</template>
