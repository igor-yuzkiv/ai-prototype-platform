export type IPrototypePage = {
    id: string
    file_name: string
    title: string
    description: string
    implementation: string | null
    created_at: string
    updated_at: string
    parent_page_id?: string | null
    deep_index?: number
}

export type IPrototypeSummary = {
    id: string
    name: string
    project_overview: string | null
    created_at: string
    updated_at: string
}

export type IPrototype = IPrototypeSummary & {
    initial_requirements: string
    formatted_requirements: string | null
    global_rules: string | null
    prototype_url: string
    pages: IPrototypePage[]
}

export interface CreatePrototypePayload {
    name?: string
    initial_requirements: string
}
