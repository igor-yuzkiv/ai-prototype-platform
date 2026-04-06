<script setup lang="ts">
import Button from 'primevue/button';
import Card from 'primevue/card';
import ConfirmDialog from 'primevue/confirmdialog';
import InputText from 'primevue/inputtext';
import Toast from 'primevue/toast';
import { ref } from 'vue';
import { useConfirm } from '@/composables/useConfirm';
import { useToast } from '@/composables/useToast';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const projectName = ref('CRMOZ PrimeVue');
const toast = useToast();
const confirm = useConfirm();

function showSuccessToast() {
    toast.success({
        detail: `PrimeVue is active for ${projectName.value}.`,
    });
}

async function confirmAction() {
    const accepted = await confirm.requireAsync({
        message: 'Run the example confirmation action?',
        icon: 'pi pi-exclamation-triangle',
        acceptLabel: 'Continue',
        rejectLabel: 'Cancel',
    });

    if (accepted) {
        toast.info({
            summary: 'Confirmed',
            detail: 'The confirmation helper resolved successfully.',
        });

        return;
    }

    toast.warn({
        summary: 'Cancelled',
        detail: 'The confirmation dialog was closed without accepting.',
    });
}
</script>

<template>
    <div class="min-h-screen bg-linear-to-br from-stone-100 via-orange-50 to-white text-stone-900">
        <main class="mx-auto flex min-h-screen max-w-6xl items-center px-6 py-16">
            <section class="grid w-full gap-8 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="space-y-6">
                    <div class="space-y-4">
                        <p
                            class="inline-flex rounded-full border border-orange-300 bg-orange-100 px-3 py-1 text-xs font-medium uppercase tracking-[0.28em] text-orange-700"
                        >
                            PrimeVue Installed
                        </p>
                        <h1
                            class="max-w-3xl text-4xl font-semibold tracking-tight text-stone-950 lg:text-6xl"
                        >
                            {{ appName }}
                        </h1>
                        <p class="max-w-2xl text-base leading-7 text-stone-600 lg:text-lg">
                            Налаштування взяте з локального
                            <code>zoho-studio/packages/ui-kit</code>, але
                            перенесене тільки як чистий PrimeVue setup без Nx,
                            workspaces чи іншого монорепо-коду.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <Button
                            label="Show Toast"
                            icon="pi pi-check"
                            @click="showSuccessToast"
                        />
                        <Button
                            label="Open Confirm"
                            icon="pi pi-question-circle"
                            variant="outlined"
                            @click="confirmAction"
                        />
                    </div>
                </div>

                <Card class="border border-stone-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.12)]">
                    <template #title>Quick Check</template>
                    <template #content>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label
                                    for="project-name"
                                    class="block text-sm text-stone-600"
                                >
                                    PrimeVue input
                                </label>
                                <InputText
                                    id="project-name"
                                    v-model="projectName"
                                    fluid
                                    placeholder="Type here"
                                />
                            </div>

                            <p class="text-sm text-stone-600">
                                Current value:
                                <span class="font-medium text-stone-950">
                                    {{ projectName }}
                                </span>
                            </p>
                        </div>
                    </template>
                </Card>
            </section>
        </main>
    </div>

    <Toast position="bottom-right" />
    <ConfirmDialog />
</template>
