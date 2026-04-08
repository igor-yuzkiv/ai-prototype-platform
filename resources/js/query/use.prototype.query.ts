import { useQuery } from '@tanstack/vue-query'
import { computed, toValue } from 'vue'
import type { MaybeRefOrGetter } from 'vue'
import { prototypesApi } from '@/api/prototypes.api'
import { prototypesKeys } from '@/config'
import { IPrototypePage } from '@/types/prototype.types'

export function usePrototypeQuery(prototypeId: MaybeRefOrGetter<string>) {
    const { data, isLoading, refetch } = useQuery({
        queryKey: computed(() => prototypesKeys.detail(toValue(prototypeId))),
        queryFn: () => prototypesApi.getById(toValue(prototypeId)),
        enabled: computed(() => !!toValue(prototypeId)),
    })

    const pages = computed<IPrototypePage[]>(() => data.value?.pages || [])

    return {
        prototype: data,
        pages,
        isLoading,
        refetch,
    }
}
