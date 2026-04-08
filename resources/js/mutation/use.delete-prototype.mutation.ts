import { useMutation, useQueryClient } from '@tanstack/vue-query'
import { prototypesApi } from '@/api/prototypes.api'
import { useToast } from '@/shared/composables'
import { prototypesKeys } from '@/config'

export function useDeletePrototypeMutation() {
    const queryClient = useQueryClient()
    const toast = useToast()

    return useMutation({
        mutationFn: (prototypeId: string) => prototypesApi.delete(prototypeId),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: prototypesKeys.list() })
            toast.success({ detail: 'Prototype deleted' })
        },
        onError: () => {
            toast.error({ detail: 'Failed to delete prototype' })
        },
    })
}
