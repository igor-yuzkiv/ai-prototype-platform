<script setup lang="ts">
import Button from 'primevue/button'
import Textarea from 'primevue/textarea'
import { computed, ref } from 'vue'
import { CreatePrototypeDialog, useCreatePrototypeDialog } from '@/components/create-prototype-dialog'
import { PrototypeCard } from '@/components/prototype-card'
import { useDeletePrototypeMutation } from '@/mutation'
import { usePrototypesListQuery } from '@/query'
import { IconButton } from '@/shared/components/button'
import { useConfirm } from '@/shared/composables'
import { LoadingOverlay } from '@/shared/components/loading'
import { NoDataMessage } from '@/shared/components/typography'
import type { IPrototypeSummary } from '@/types/prototype.types'

const confirm = useConfirm()

const { prototypes, isLoading: isLoadingPrototypes } = usePrototypesListQuery()

const createPrototypeDialog = useCreatePrototypeDialog({
    onCreated: () => {
        initialRequirements.value = ''
    },
})

const initialRequirements = ref('')

const isLoading = computed(() => isLoadingPrototypes.value)

const canCreatePrototype = computed(
    () =>
        initialRequirements.value.trim().length > 0 &&
        !createPrototypeDialog.isBusy.value &&
        !deleteMutation.isPending.value
)

const deleteMutation = useDeletePrototypeMutation()

function createPrototype() {
    if (canCreatePrototype.value) {
        void createPrototypeDialog.open(initialRequirements.value)
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
    <div class="flex h-full w-full overflow-auto">
        <div class="p-2 container mx-auto flex h-full flex-col">
            <div class="my-15 leading-tight flex flex-col items-center text-center">
                <h1 class="font-semibold text-5xl">
                    Turn ideas <br />
                    into working prototypes in seconds
                </h1>
                <p class="mt-10 text-xl text-gray-500">
                    Describe your application flow, page layout, or dashboard. <br />
                    Our AI will instantly generate production-ready UI mockups you can <br />
                    refine and export.
                </p>
            </div>

            <form
                class="mb-12 border-primary-700 shadow-md bg-white dark:bg-black-pearl-950 rounded-lg border-3"
                @submit.prevent="createPrototype"
            >
                <Textarea
                    v-model.trim="initialRequirements"
                    class="p-6 text-lg border-none bg-transparent shadow-none"
                    rows="4"
                    placeholder="Describe your prototype idea... (e.g., A real estate dashboard with a map view and property list)"
                    fluid
                    style="resize: none"
                />

                <div class="py-2 px-4 bg-ebony-50 dark:bg-ebony-950 rounded-b-lg flex items-center justify-end">
                    <Button
                        type="submit"
                        label="Let`s cook!"
                        icon="pi pi-sparkles"
                        size="small"
                        :loading="createPrototypeDialog.isNormalizing.value"
                        :disabled="!canCreatePrototype"
                    />
                </div>
            </form>

            <section class="flex flex-1 flex-col">
                <h2 class="mb-5 text-base font-semibold">Recent Prototypes</h2>

                <NoDataMessage
                    v-if="prototypes.length === 0"
                    class="p-2 flex-1"
                    title="No Prototypes Yet"
                    message="Your recently generated prototypes will appear here."
                    icon="ant-design:experiment-outlined"
                />

                <div v-else class="gap-5 lg:grid-cols-3 grid grid-cols-1">
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
                                severity="secondary"
                                text
                                :disabled="deleteMutation.isPending.value || createPrototypeDialog.isBusy.value"
                                @click.prevent="deletePrototype(prototype)"
                            />
                        </template>
                    </PrototypeCard>
                </div>
            </section>
        </div>
    </div>

    <CreatePrototypeDialog
        v-model:visible="createPrototypeDialog.visible.value"
        v-model:requirements="createPrototypeDialog.normalizedRequirements.value"
        :is-normalizing="createPrototypeDialog.isNormalizing.value"
        :is-generating="createPrototypeDialog.isGenerating.value"
        :can-generate="createPrototypeDialog.canGenerate.value"
        @close="createPrototypeDialog.close()"
        @generate="createPrototypeDialog.generate()"
    />
</template>
