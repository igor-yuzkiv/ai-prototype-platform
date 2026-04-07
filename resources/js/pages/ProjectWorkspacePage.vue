<script setup lang="ts">
import { useRouteParams } from '@vueuse/router'
import { useProjectQuery } from '@/query'
import { useThemeStore } from '@/store/use.theme.store'

const projectId = useRouteParams<string>('id')

const { data: project } = useProjectQuery(projectId)
const appTheme = useThemeStore()
</script>

<template>
    <div class="flex h-full w-full flex-col overflow-hidden">
        <pre>
            {{ project?.formatted_requirements }}
        </pre>

        <vue-monaco-editor
            language="javascript"
            :value="project?.formatted_requirements ?? ''"
            :theme="appTheme.isDark ? 'vs-dark' : 'vs'"
        />
    </div>
</template>
