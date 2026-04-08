import { httpClient } from '@/api/http.client'
import type { PaginationParams, PaginatedCollectionResponse, ResourceResponse } from '@/types/api.types'
import type { CreatePrototypePayload, IPrototype, IPrototypeSummary } from '@/types/prototype.types'

function unwrapResource<TResource>(response: ResourceResponse<TResource>): TResource {
    return response.data
}

export const prototypesApi = {
    async list(params?: PaginationParams): Promise<PaginatedCollectionResponse<IPrototypeSummary>> {
        const response = await httpClient.get<PaginatedCollectionResponse<IPrototype>>('/prototypes', {
            params,
        })

        return response.data
    },

    async getById(prototypeId: string): Promise<IPrototype> {
        const response = await httpClient.get<ResourceResponse<IPrototype>>(`/prototypes/${prototypeId}`)

        return unwrapResource(response.data)
    },

    async create(payload: CreatePrototypePayload): Promise<IPrototype> {
        const response = await httpClient.post<ResourceResponse<IPrototype>>('/prototypes', payload)

        return unwrapResource(response.data)
    },

    async delete(prototypeId: string): Promise<void> {
        await httpClient.delete(`/prototypes/${prototypeId}`)
    },

    async generatePlan(prototypeId: string): Promise<IPrototype> {
        const response = await httpClient.post<ResourceResponse<IPrototype>>(`/prototypes/${prototypeId}/generate-plan`)

        return unwrapResource(response.data)
    },
}
