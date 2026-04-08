import { useMutation } from '@tanstack/vue-query'
import { useRouter } from 'vue-router'
import { prototypesApi } from '@/api/prototypes.api'
import { useToast } from '@/composables'
import type { CreatePrototypePayload } from '@/types/prototype.types'

export function useCreatePrototypeMutation(onSuccess?: () => void) {
    const router = useRouter()
    const toast = useToast()

    return useMutation({
        mutationFn: (payload: CreatePrototypePayload) => prototypesApi.create(payload),
        onSuccess: (prototype) => {
            onSuccess?.()
            router.push(`/prototypes/${prototype.id}`)
            toast.success({ detail: 'Prototype created successfully' })
        },
        onError: () => {
            toast.error({ detail: 'Failed to create prototype' })
        },
    })
}
