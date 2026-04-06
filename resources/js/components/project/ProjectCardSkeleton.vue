<template>
    <div class="rounded-xl border border-stone-200 bg-white p-6">
        <Skeleton class="mb-3" width="60%" height="1.25rem" />
        <Skeleton class="mb-2" width="100%" height="1rem" />
        <Skeleton class="mb-6" width="80%" height="1rem" />
        <div class="flex items-center justify-between">
            <Skeleton width="6rem" height="1rem" />
            <Skeleton width="4rem" height="2rem" border-radius="6px" />
        </div>
    </div>
</template>

<script setup lang="ts">
import Skeleton from 'primevue/skeleton'
</script>
