export type IPrototype = {
    id: string
    name: string
    initial_requirements: string
    formatted_requirements: string | null
    project_overview: string | null
    global_rules: string | null
    prototype_url: string
    created_at: string
    updated_at: string
}

export interface CreatePrototypePayload {
    name?: string
    initial_requirements: string
}
