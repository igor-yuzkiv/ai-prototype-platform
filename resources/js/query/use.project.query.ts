import { useQuery } from '@tanstack/vue-query'
import { computed, toValue } from 'vue'
import type { MaybeRefOrGetter } from 'vue'
import { projectsApi } from '@/api/projects.api'
import { projectsKeys } from '@/config'
import { Maybe } from '@/types/result.types'

export function useProjectQuery(projectId: MaybeRefOrGetter<Maybe<string>>) {
    return useQuery({
        queryKey: computed(() => projectsKeys.detail(toValue(projectId)!)),
        queryFn: () => projectsApi.getById(toValue(projectId)!),
        enabled: computed(() => !!toValue(projectId)),
    })
}
