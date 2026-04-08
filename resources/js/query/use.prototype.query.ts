import { useQuery } from '@tanstack/vue-query'
import { computed, toValue } from 'vue'
import type { MaybeRefOrGetter } from 'vue'
import { prototypesApi } from '@/api/prototypes.api'
import { prototypesKeys } from '@/config'

export function usePrototypeQuery(prototypeId: MaybeRefOrGetter<string>) {
    return useQuery({
        queryKey: computed(() => prototypesKeys.detail(toValue(prototypeId))),
        queryFn: () => prototypesApi.getById(toValue(prototypeId)),
        enabled: computed(() => !!toValue(prototypeId)),
    })
}
