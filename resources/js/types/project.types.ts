export type IProject = {
    id: string
    name: string
    requirements: string
    formatted_requirements: string | null
    prototype_url: string
    created_at: string
    updated_at: string
}

export interface CreateProjectPayload {
    name?: string
    requirements: string
}

export interface UpdateProjectPayload {
    name?: string
    requirements?: string
}
