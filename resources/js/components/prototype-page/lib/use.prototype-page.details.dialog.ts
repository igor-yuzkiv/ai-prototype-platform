import { ref } from 'vue'
import { IPrototypePage } from '@/types/prototype.types'
import { Maybe } from '@/shared/types/result.types'

export function usePrototypePageDetailsDialog() {
    const currentPage = ref<Maybe<IPrototypePage>>(null)
    const visible = ref(false)

    function open(page: IPrototypePage) {
        currentPage.value = page
        visible.value = true
    }

    function close() {
        visible.value = false
        currentPage.value = null
    }

    return {
        currentPage,
        visible,
        open,
        close,
    }
}
