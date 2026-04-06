<script setup lang="ts">
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import { ref, watch } from 'vue'
import { useCreateProjectMutation } from '@/mutation'

const props = defineProps<{
    visible: boolean
}>()

const emit = defineEmits<{
    'update:visible': [value: boolean]
}>()

const name = ref('')
const description = ref('')

watch(
    () => props.visible,
    (value) => {
        if (value) {
            name.value = ''
            description.value = ''
        }
    }
)

const mutation = useCreateProjectMutation(() => emit('update:visible', false))

function handleSubmit() {
    mutation.mutate({ name: name.value, description: description.value })
}
</script>

<template>
    <Dialog
        :visible="visible"
        header="New Project"
        :modal="true"
        style="width: 480px"
        @update:visible="emit('update:visible', $event)"
    >
        <form class="flex flex-col gap-4 pt-2" @submit.prevent="handleSubmit">
            <div class="flex flex-col gap-1">
                <label for="project-name" class="text-sm font-medium text-stone-700"> Project Name </label>
                <InputText id="project-name" v-model="name" placeholder="Enter project name" fluid required />
            </div>

            <div class="flex flex-col gap-1">
                <label for="project-description" class="text-sm font-medium text-stone-700"> Description </label>
                <Textarea
                    id="project-description"
                    v-model="description"
                    placeholder="Enter project description"
                    :rows="4"
                    fluid
                    required
                />
            </div>
        </form>

        <template #footer>
            <div class="flex justify-end gap-2">
                <Button label="Cancel" variant="outlined" @click="emit('update:visible', false)" />
                <Button
                    label="Create"
                    icon="pi pi-check"
                    :loading="mutation.isPending.value"
                    :disabled="mutation.isPending.value"
                    @click="handleSubmit"
                />
            </div>
        </template>
    </Dialog>
</template>
