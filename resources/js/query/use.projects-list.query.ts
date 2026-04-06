import { useQuery } from '@tanstack/vue-query'
import { projectsApi } from '@/api/projects.api'
import { projectsKeys } from '@/config'

export function useProjectsListQuery() {
    return useQuery({
        queryKey: projectsKeys.list(),
        queryFn: () => projectsApi.list(),
    })
}
