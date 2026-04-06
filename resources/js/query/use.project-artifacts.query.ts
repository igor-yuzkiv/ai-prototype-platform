import { useQuery } from '@tanstack/vue-query'
import { computed, toValue } from 'vue'
import type { MaybeRefOrGetter } from 'vue'
import { projectArtifactsApi } from '@/api/project_artifacts.api'
import { projectArtifactsKeys } from '@/config'
import type { PaginationParams } from '@/types/api.types'

export function useProjectArtifactsQuery(projectId: MaybeRefOrGetter<number>) {
    return useQuery({
        queryKey: computed(() => projectArtifactsKeys.list(toValue(projectId))),
        queryFn: () =>
            projectArtifactsApi.list({
                project_id: toValue(projectId),
            } as PaginationParams & { project_id: number }),
    })
}
