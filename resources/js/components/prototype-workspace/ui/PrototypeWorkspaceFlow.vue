<script setup lang="ts">
import { computed } from 'vue'
import { VueFlow } from '@vue-flow/core'
import type { Node, Edge, NodeMouseEvent } from '@vue-flow/core'
import '@vue-flow/core/dist/style.css'
import type { IPrototypePage } from '@/types/prototype.types'
import PrototypeScreenNode from './PrototypeScreenNode.vue'

const props = defineProps<{
    pages: IPrototypePage[]
}>()

const emit = defineEmits<{
    (event: 'page:click', value: IPrototypePage): void
}>()

const nodes = computed<Node[]>(() => {
    const countByDepth: Record<number, number> = {}

    return props.pages.map((page) => {
        const depth = page.deep_index ?? 0
        const rowIndex = countByDepth[depth] ?? 0
        countByDepth[depth] = rowIndex + 1

        return {
            id: page.id,
            type: 'PrototypeScreen',
            position: {
                x: depth * 500,
                y: rowIndex * 200,
            },
            data: page,
        }
    })
})

const edges = computed<Edge[]>(() => {
    return props.pages
        .filter((page) => !!page.parent_page_id)
        .map((page) => ({
            id: `${page.parent_page_id}-${page.id}`,
            source: page.parent_page_id as string,
            target: page.id,
            animated: true,
        }))
})

function onNodeClick({ node }: NodeMouseEvent) {
    emit('page:click', node.data as IPrototypePage)
}
</script>

<template>
    <div class="dotted-background flex h-full w-full overflow-hidden">
        <VueFlow :nodes="nodes" :edges="edges" fit-view-on-init class="rounded-lg flex-1" @node-click="onNodeClick">
            <template #node-PrototypeScreen="{ id, data }">
                <PrototypeScreenNode :id="id" :data="data" />
            </template>
        </VueFlow>
    </div>
</template>
