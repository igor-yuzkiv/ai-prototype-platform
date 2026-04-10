import { useMutation } from '@tanstack/vue-query'
import { prototypesApi } from '@/api/prototypes.api'
import type { NormalizePrototypeRequirementsPayload } from '@/types/prototype.types'

export type NormalizePrototypeRequirementsMutationPayload = NormalizePrototypeRequirementsPayload & {
    onChunk: (chunk: string) => void
    signal?: AbortSignal
}

export function useNormalizePrototypeRequirementsMutation() {
    return useMutation({
        mutationFn: ({ initial_requirements, onChunk, signal }: NormalizePrototypeRequirementsMutationPayload) =>
            prototypesApi.normalizeRequirements({ initial_requirements }, onChunk, signal),
    })
}
