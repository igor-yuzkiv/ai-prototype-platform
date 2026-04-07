export interface IProjectSummary {
    id: number
    name: string
    requirements: string
    prototype_url: string
    created_at: string
    updated_at: string
}

export type IProject = IProjectSummary

export interface CreateProjectPayload {
    name?: string
    requirements: string
}

export interface UpdateProjectPayload {
    name?: string
    requirements?: string
}
