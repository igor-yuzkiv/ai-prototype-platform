import { useMutation, useQueryClient } from '@tanstack/vue-query'
import { projectsApi } from '@/api/projects.api'
import { useToast } from '@/composables'
import { projectsKeys } from '@/config'

export function useDeleteProjectMutation() {
    const queryClient = useQueryClient()
    const toast = useToast()

    return useMutation({
        mutationFn: (projectId: number) => projectsApi.delete(projectId),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: projectsKeys.list() })
            toast.success({ detail: 'Project deleted' })
        },
        onError: () => {
            toast.error({ detail: 'Failed to delete project' })
        },
    })
}
