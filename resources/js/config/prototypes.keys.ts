export const prototypesKeys = {
    all: ['prototypes'] as const,
    list: () => [...prototypesKeys.all, 'list'] as const,
    detail: (id: string) => [...prototypesKeys.all, id] as const,
}
