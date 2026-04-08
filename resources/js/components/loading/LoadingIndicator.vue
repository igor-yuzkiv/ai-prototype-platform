<script setup>
import { Icon } from '@iconify/vue'

defineProps({
    position: {
        type: String,
        default: 'absolute bottom-5 right-5',
    },
})
</script>

<template>
    <div :class="position">
        <div class="flex items-center justify-center">
            <Icon icon="gg:spinner-two" class="mr-2 h-12 w-12 animate-spin text-blue-600" />
        </div>
    </div>
</template>

<style scoped></style>
