import { computed, onScopeDispose, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useCreatePrototypeMutation, useNormalizePrototypeRequirementsMutation } from '@/mutation'
import { useToast } from '@/shared/composables'

export function useCreatePrototypeDialog(options?: { onCreated?: () => void }) {
    const router = useRouter()
    const toast = useToast()

    const visible = ref(false)
    const rawRequirements = ref('')
    const normalizedRequirements = ref('')
    const abortController = ref<AbortController | null>(null)

    const createPrototypeMutation = useCreatePrototypeMutation((prototype) => {
        options?.onCreated?.()
        resetState()
        router.push(`/prototypes/${prototype.id}`)
    })

    const normalizeRequirementsMutation = useNormalizePrototypeRequirementsMutation()

    const isNormalizing = computed(() => normalizeRequirementsMutation.isPending.value)
    const isGenerating = computed(() => createPrototypeMutation.isPending.value)
    const isBusy = computed(() => isNormalizing.value || isGenerating.value)
    const canGenerate = computed(
        () => normalizedRequirements.value.trim().length > 0 && !isNormalizing.value && !isGenerating.value
    )

    async function open(initialRequirements: string) {
        rawRequirements.value = initialRequirements
        normalizedRequirements.value = ''
        visible.value = true

        await normalize(initialRequirements)
    }

    async function normalize(initialRequirements = rawRequirements.value) {
        abortController.value?.abort()

        const controller = new AbortController()
        abortController.value = controller
        normalizedRequirements.value = ''

        try {
            await normalizeRequirementsMutation.mutateAsync({
                initial_requirements: initialRequirements,
                signal: controller.signal,
                onChunk: (chunk) => {
                    normalizedRequirements.value += chunk
                },
            })
        } catch (error) {
            if (controller.signal.aborted) {
                return
            }

            toast.error({ detail: error instanceof Error ? error.message : 'Failed to normalize requirements' })
        } finally {
            if (abortController.value === controller) {
                abortController.value = null
            }
        }
    }

    async function generate() {
        if (!canGenerate.value) {
            return
        }

        await createPrototypeMutation.mutateAsync({
            initial_requirements: rawRequirements.value,
            formatted_requirements: normalizedRequirements.value,
        })
    }

    function close() {
        abortController.value?.abort()
        resetState()
    }

    function resetState() {
        visible.value = false
        rawRequirements.value = ''
        normalizedRequirements.value = ''
        abortController.value = null
        normalizeRequirementsMutation.reset()
        createPrototypeMutation.reset()
    }

    onScopeDispose(() => abortController.value?.abort())

    return {
        visible,
        normalizedRequirements,
        isNormalizing,
        isGenerating,
        isBusy,
        canGenerate,
        open,
        close,
        generate,
    }
}
