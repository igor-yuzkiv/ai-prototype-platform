import { useQuery } from '@tanstack/vue-query'
import { prototypesApi } from '@/api/prototypes.api'
import { prototypesKeys } from '@/config'

export function usePrototypesListQuery() {
    return useQuery({
        queryKey: prototypesKeys.list(),
        queryFn: () => prototypesApi.list(),
    })
}
