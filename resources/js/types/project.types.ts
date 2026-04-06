import type { ProjectArtifactSummary } from '@/types/project_artifact.types';

export interface ProjectSummary {
    id: number;
    slug: string;
    name: string;
    description: string;
    created_at: string;
    updated_at: string;
}

export interface Project extends ProjectSummary {
    artifacts?: ProjectArtifactSummary[];
}

export interface CreateProjectPayload {
    name: string;
    description: string;
}

export interface UpdateProjectPayload {
    name?: string;
    description?: string;
}
