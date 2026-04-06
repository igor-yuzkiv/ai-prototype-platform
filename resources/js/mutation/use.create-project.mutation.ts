import { useMutation } from '@tanstack/vue-query'
import { useRouter } from 'vue-router'
import { projectsApi } from '@/api/projects.api'
import { useToast } from '@/composables'
import type { CreateProjectPayload } from '@/types/project.types'

export function useCreateProjectMutation(onSuccess?: () => void) {
    const router = useRouter()
    const toast = useToast()

    return useMutation({
        mutationFn: (payload: CreateProjectPayload) => projectsApi.create(payload),
        onSuccess: (project) => {
            onSuccess?.()
            router.push(`/projects/${project.id}`)
            toast.success({ detail: 'Project created successfully' })
        },
        onError: () => {
            toast.error({ detail: 'Failed to create project' })
        },
    })
}
