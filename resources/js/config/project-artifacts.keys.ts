export const projectArtifactsKeys = {
    all: ['project-artifacts'] as const,
    list: (projectId: number) => [...projectArtifactsKeys.all, 'list', { project_id: projectId }] as const,
}
