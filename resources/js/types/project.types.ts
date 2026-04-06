import type { ProjectArtifactSummary } from '@/types/project_artifact.types'

export interface IProjectSummary {
    id: number
    name: string
    description: string
    prototype_url: string
    created_at: string
    updated_at: string
}

export interface IProject extends IProjectSummary {
    artifacts?: ProjectArtifactSummary[]
}

export interface CreateProjectPayload {
    name: string
    description: string
}

export interface UpdateProjectPayload {
    name?: string
    description?: string
}
