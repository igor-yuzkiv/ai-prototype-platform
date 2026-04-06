import { httpClient } from '@/api/http.client'
import type { PaginationParams, PaginatedCollectionResponse, ResourceResponse } from '@/types/api.types'
import type { CreateProjectPayload, IProject, UpdateProjectPayload } from '@/types/project.types'

function unwrapResource<TResource>(response: ResourceResponse<TResource>): TResource {
    return response.data
}

export const projectsApi = {
    async list(params?: PaginationParams): Promise<PaginatedCollectionResponse<IProject>> {
        const response = await httpClient.get<PaginatedCollectionResponse<IProject>>('/projects', {
            params,
        })

        return response.data
    },

    async getById(projectId: number | string): Promise<IProject> {
        const response = await httpClient.get<ResourceResponse<IProject>>(`/projects/${projectId}`)

        return unwrapResource(response.data)
    },

    async create(payload: CreateProjectPayload): Promise<IProject> {
        const response = await httpClient.post<ResourceResponse<IProject>>('/projects', payload)

        return unwrapResource(response.data)
    },

    async update(projectId: number | string, payload: UpdateProjectPayload): Promise<IProject> {
        const response = await httpClient.patch<ResourceResponse<IProject>>(`/projects/${projectId}`, payload)

        return unwrapResource(response.data)
    },

    async delete(projectId: number | string): Promise<void> {
        await httpClient.delete(`/projects/${projectId}`)
    },
}
