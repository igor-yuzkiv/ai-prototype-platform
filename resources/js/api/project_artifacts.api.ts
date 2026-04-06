import { httpClient } from '@/api/http.client';
import type {
    PaginationParams,
    PaginatedCollectionResponse,
    ResourceResponse,
} from '@/types/api.types';
import type {
    CreateProjectArtifactPayload,
    ProjectArtifact,
    UpdateProjectArtifactPayload,
} from '@/types/project_artifact.types';

function unwrapResource<TResource>(response: ResourceResponse<TResource>): TResource {
    return response.data;
}

export const projectArtifactsApi = {
    async list(params?: PaginationParams): Promise<PaginatedCollectionResponse<ProjectArtifact>> {
        const response = await httpClient.get<PaginatedCollectionResponse<ProjectArtifact>>(
            '/project-artifacts',
            {
                params,
            },
        );

        return response.data;
    },

    async getById(projectArtifactId: number | string): Promise<ProjectArtifact> {
        const response = await httpClient.get<ResourceResponse<ProjectArtifact>>(
            `/project-artifacts/${projectArtifactId}`,
        );

        return unwrapResource(response.data);
    },

    async create(payload: CreateProjectArtifactPayload): Promise<ProjectArtifact> {
        const response = await httpClient.post<ResourceResponse<ProjectArtifact>>(
            '/project-artifacts',
            payload,
        );

        return unwrapResource(response.data);
    },

    async update(
        projectArtifactId: number | string,
        payload: UpdateProjectArtifactPayload,
    ): Promise<ProjectArtifact> {
        const response = await httpClient.patch<ResourceResponse<ProjectArtifact>>(
            `/project-artifacts/${projectArtifactId}`,
            payload,
        );

        return unwrapResource(response.data);
    },

    async delete(projectArtifactId: number | string): Promise<void> {
        await httpClient.delete(`/project-artifacts/${projectArtifactId}`);
    },
};
