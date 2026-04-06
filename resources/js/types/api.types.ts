export interface ResourceResponse<TResource> {
    data: TResource;
}

export interface PaginationParams {
    page?: number;
    per_page?: number;
}

export interface PaginationMeta {
    current_page: number;
    from: number | null;
    last_page: number;
    per_page: number;
    to: number | null;
    total: number;
}

export interface PaginatedCollectionResponse<TResource> {
    data: TResource[];
    meta: PaginationMeta;
}
