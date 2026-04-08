export type IPrototype = {
    id: string
    name: string
    requirements: string
    formatted_requirements: string | null
    prototype_url: string
    created_at: string
    updated_at: string
}

export interface CreatePrototypePayload {
    name?: string
    requirements: string
}
