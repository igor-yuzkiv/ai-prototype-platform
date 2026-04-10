import { useMutation } from '@tanstack/vue-query'
import { useQueryClient } from '@tanstack/vue-query'
import { prototypesApi } from '@/api/prototypes.api'
import { prototypesKeys } from '@/config'
import { useToast } from '@/shared/composables'
import type { CreatePrototypePayload, IPrototype } from '@/types/prototype.types'

export function useCreatePrototypeMutation(onSuccess?: (prototype: IPrototype) => void) {
    const queryClient = useQueryClient()
    const toast = useToast()

    return useMutation({
        mutationFn: (payload: CreatePrototypePayload) => prototypesApi.create(payload),
        onSuccess: (prototype) => {
            queryClient.invalidateQueries({ queryKey: prototypesKeys.list() })
            onSuccess?.(prototype)
            toast.success({ detail: 'Prototype created successfully' })
        },
        onError: () => {
            toast.error({ detail: 'Failed to create prototype' })
        },
    })
}
