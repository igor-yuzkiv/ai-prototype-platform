<script setup lang="ts">
import { useRouteParams } from '@vueuse/router'
import { useProjectQuery } from '@/query'

const projectId = useRouteParams<string>('id')

const { data: project } = useProjectQuery(projectId)
</script>

<template>
    <div class="flex h-full w-full flex-col overflow-hidden">
        <pre>
            {{ project?.formatted_requirements }}
        </pre>
    </div>
</template>
