import { useQuery } from '@tanstack/vue-query'
import { prototypesApi } from '@/api/prototypes.api'
import { prototypesKeys } from '@/config'
import { computed } from 'vue'
import { IPrototypeSummary } from '@/types/prototype.types'

export function usePrototypesListQuery() {
    const {data} = useQuery({
        queryKey: prototypesKeys.list(),
        queryFn: () => prototypesApi.list(),
    })

    const prototypes = computed<IPrototypeSummary[]>(() => {
        return data.value?.data || []
    })

    return {
        data,
        prototypes,
    }
}
