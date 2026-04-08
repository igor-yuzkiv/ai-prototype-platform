export type IPrototype = {
    id: string
    name: string
    initial_requirements: string
    formatted_requirements: string | null
    prototype_url: string
    created_at: string
    updated_at: string
}

export interface CreatePrototypePayload {
    name?: string
    initial_requirements: string
}
