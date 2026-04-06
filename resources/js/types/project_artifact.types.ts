export interface ProjectArtifactSummary {
    id: number
    project_id: number
    name: string
    content: string
    created_at: string
    updated_at: string
}

export interface ProjectArtifact extends ProjectArtifactSummary {}

export interface CreateProjectArtifactPayload {
    project_id: number
    name: string
    content: string
}

export interface UpdateProjectArtifactPayload {
    project_id?: number
    name?: string
    content?: string
}
