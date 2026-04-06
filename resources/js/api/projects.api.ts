import { httpClient } from '@/api/http.client';
import type {
    PaginationParams,
    PaginatedCollectionResponse,
    ResourceResponse,
} from '@/types/api.types';
import type {
    CreateProjectPayload,
    Project,
    UpdateProjectPayload,
} from '@/types/project.types';

function unwrapResource<TResource>(response: ResourceResponse<TResource>): TResource {
    return response.data;
}

export const projectsApi = {
    async list(params?: PaginationParams): Promise<PaginatedCollectionResponse<Project>> {
        const response = await httpClient.get<PaginatedCollectionResponse<Project>>('/projects', {
            params,
        });

        return response.data;
    },

    async getById(projectId: number | string): Promise<Project> {
        const response = await httpClient.get<ResourceResponse<Project>>(`/projects/${projectId}`);

        return unwrapResource(response.data);
    },

    async create(payload: CreateProjectPayload): Promise<Project> {
        const response = await httpClient.post<ResourceResponse<Project>>('/projects', payload);

        return unwrapResource(response.data);
    },

    async update(projectId: number | string, payload: UpdateProjectPayload): Promise<Project> {
        const response = await httpClient.patch<ResourceResponse<Project>>(
            `/projects/${projectId}`,
            payload,
        );

        return unwrapResource(response.data);
    },

    async delete(projectId: number | string): Promise<void> {
        await httpClient.delete(`/projects/${projectId}`);
    },
};
