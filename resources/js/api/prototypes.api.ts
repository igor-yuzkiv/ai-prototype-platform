import { httpClient } from '@/api/http.client'
import type { PaginationParams, PaginatedCollectionResponse, ResourceResponse } from '@/shared/types/pagination.types'
import { EventStreamContentType, fetchEventSource } from '@microsoft/fetch-event-source'
import type {
    CreatePrototypePayload,
    IPrototype,
    IPrototypeSummary,
    NormalizePrototypeRequirementsPayload,
} from '@/types/prototype.types'

type StreamEventPayload = {
    type?: string
    delta?: string
    message?: string
    errorText?: string
}

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

    async normalizeRequirements(
        payload: NormalizePrototypeRequirementsPayload,
        onChunk: (chunk: string) => void,
        signal?: AbortSignal
    ): Promise<void> {
        await fetchEventSource(httpClient.getUri({ url: '/prototypes/normalize-requirements' }), {
            method: 'POST',
            body: JSON.stringify(payload),
            signal,
            credentials: 'include',
            openWhenHidden: true,
            headers: {
                Accept: EventStreamContentType,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            async onopen(response) {
                if (response.ok) {
                    return
                }

                throw new Error(`Request failed with status ${response.status}.`)
            },
            onmessage(message) {
                if (message.data === '[DONE]') {
                    return
                }

                let payload: StreamEventPayload

                try {
                    payload = JSON.parse(message.data) as StreamEventPayload
                } catch {
                    return
                }

                if (payload.type === 'error') {
                    throw new Error(payload.errorText ?? payload.message ?? 'Failed to normalize requirements.')
                }

                if ((payload.type === 'text_delta' || payload.type === 'text-delta') && payload.delta) {
                    onChunk(payload.delta)
                }
            },
            onerror(error) {
                throw error
            },
        })
    },

    async delete(prototypeId: string): Promise<void> {
        await httpClient.delete(`/prototypes/${prototypeId}`)
    },

    async generatePlan(prototypeId: string): Promise<IPrototype> {
        const response = await httpClient.post<ResourceResponse<IPrototype>>(`/prototypes/${prototypeId}/generate-plan`)

        return unwrapResource(response.data)
    },

    async publish(prototypeId: string): Promise<IPrototype> {
        const response = await httpClient.post<ResourceResponse<IPrototype>>(`/prototypes/${prototypeId}/publish`)

        return unwrapResource(response.data)
    },
}
