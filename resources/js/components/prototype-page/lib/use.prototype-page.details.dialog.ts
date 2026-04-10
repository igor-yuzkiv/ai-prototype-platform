import { ref, watch } from 'vue'
import { IPrototypePage } from '@/types/prototype.types'
import { Maybe } from '@/shared/types/result.types'

export function usePrototypePageDetailsDialog() {
    const currentPage = ref<Maybe<IPrototypePage>>(null)
    const visible = ref(false)

    const implementation = ref<string>('')

    function open(page: IPrototypePage) {
        currentPage.value = page
        implementation.value = page.implementation || ''
        visible.value = true
    }

    function close() {
        visible.value = false
        currentPage.value = null
    }

    watch(visible, (newValue) => {
        if (!newValue) {
            close()
        }
    })

    return {
        currentPage,
        implementation,
        visible,
        open,
        close,
    }
}
