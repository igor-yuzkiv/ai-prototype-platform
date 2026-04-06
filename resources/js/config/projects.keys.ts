export const projectsKeys = {
    all: ['projects'] as const,
    list: () => [...projectsKeys.all, 'list'] as const,
    detail: (id: number) => [...projectsKeys.all, id] as const,
}
