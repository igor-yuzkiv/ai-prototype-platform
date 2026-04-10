import { Maybe } from '@/shared/types/result.types'

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
    icon?: string | null
}

export type PrototypeStatus = 'new' | 'planned' | 'implemented' | 'published'

export type IPrototypeSummary = {
    id: string
    name: string
    status: PrototypeStatus | null
    prototype_url: string | null
    project_overview: string | null
    created_at: string
    updated_at: string
}

export type IPrototype = IPrototypeSummary & {
    initial_requirements: string
    formatted_requirements: string | null
    global_rules: string | null
    pages: IPrototypePage[]
}

export interface CreatePrototypePayload {
    name?: string
    initial_requirements: string
    formatted_requirements: string
}

export interface NormalizePrototypeRequirementsPayload {
    initial_requirements: string
}

export type PrototypeStatusChangedEventPayload = {
    id: string
    status: Maybe<PrototypeStatus>
}
