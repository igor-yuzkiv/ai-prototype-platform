<script setup lang="ts">
import { computed } from 'vue'
import { VueFlow } from '@vue-flow/core'
import type { Node, Edge, NodeMouseEvent } from '@vue-flow/core'
import '@vue-flow/core/dist/style.css'
import type { IPrototype, IPrototypePage } from '@/types/prototype.types'
import PrototypeScreenNode from './PrototypeScreenNode.vue'
import TextBlockNode from './TextBlockNode.vue'
import { MiniMap } from '@vue-flow/minimap'

const props = defineProps<{
    prototype: IPrototype
    pages: IPrototypePage[]
}>()

const emit = defineEmits<{
    (event: 'page:click', value: IPrototypePage): void
}>()

const textNodes = computed<Node[]>(() => {
    const nodes: Node[] = [
        {
            id: 'text-initial-requirements',
            type: 'TextScreen',
            position: { x: -800, y: 0 },
            width: 400,
            height: 400,
            data: {
                title: 'Initial Requirements',
                content: props.prototype.initial_requirements,
            },
        },
    ]

    if (props.prototype.formatted_requirements) {
        nodes.push({
            id: 'text-formatted-requirements',
            type: 'TextScreen',
            position: { x: -800, y: 500 },
            width: 400,
            height: 400,
            data: {
                title: 'Formatted Requirements',
                content: props.prototype.formatted_requirements,
            },
        })
    }

    return nodes
})

const pageNodes = computed<Node[]>(() => {
    const countByDepth: Record<number, number> = {}

    return props.pages.map((page) => {
        const depth = page.deep_index ?? 0
        const rowIndex = countByDepth[depth] ?? 0
        countByDepth[depth] = rowIndex + 1

        return {
            id: page.id,
            type: 'PrototypeScreen',
            width: 1280,
            height: 883,
            position: {
                x: depth * 1400,
                y: rowIndex * 1000,
            },
            data: page,
        }
    })
})

const nodes = computed<Node[]>(() => [...textNodes.value, ...pageNodes.value])

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
    if (node.type === 'PrototypeScreen') {
        emit('page:click', node.data as IPrototypePage)
    }
}
</script>

<template>
    <div class="flex h-full w-full overflow-hidden">
        <VueFlow :nodes="nodes" :edges="edges" fit-view-on-init class="rounded-lg flex-1" @node-click="onNodeClick">
            <MiniMap />

            <template #node-PrototypeScreen="{ id, data }">
                <PrototypeScreenNode :id="id" :data="data" />
            </template>
            <template #node-TextScreen="{ id, data }">
                <TextBlockNode :id="id" :data="data" />
            </template>
        </VueFlow>
    </div>
</template>
