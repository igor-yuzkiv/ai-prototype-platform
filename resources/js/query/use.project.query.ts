import { useQuery } from '@tanstack/vue-query'
import { computed, toValue } from 'vue'
import type { MaybeRefOrGetter } from 'vue'
import { projectsApi } from '@/api/projects.api'
import { projectsKeys } from '@/config'

export function useProjectQuery(projectId: MaybeRefOrGetter<string>) {
    return useQuery({
        queryKey: computed(() => projectsKeys.detail(toValue(projectId))),
        queryFn: () => projectsApi.getById(toValue(projectId)),
        enabled: computed(() => !!toValue(projectId)),
    })
}
