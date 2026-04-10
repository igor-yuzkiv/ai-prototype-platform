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

const TEXT_W = 400
const TEXT_H = 400
const TEXT_GAP = 60
const NODE_W = 1280
const NODE_H = 883
const X_STEP = NODE_W + 120
const Y_STEP = NODE_H + 60

const textNodes = computed<Node[]>(() => {
    const hasFormatted = !!props.prototype.formatted_requirements
    const totalTextH = hasFormatted ? TEXT_H * 2 + TEXT_GAP : TEXT_H
    const startY = -totalTextH / 2
    const textX = -(TEXT_W + 120)

    const nodes: Node[] = [
        {
            id: 'text-initial-requirements',
            type: 'TextScreen',
            position: { x: textX, y: startY },
            width: TEXT_W,
            height: TEXT_H,
            data: {
                title: 'Initial Requirements',
                content: props.prototype.initial_requirements,
            },
        },
    ]

    if (hasFormatted) {
        nodes.push({
            id: 'text-formatted-requirements',
            type: 'TextScreen',
            position: { x: textX, y: startY + TEXT_H + TEXT_GAP },
            width: TEXT_W,
            height: TEXT_H,
            data: {
                title: 'Formatted Requirements',
                content: props.prototype.formatted_requirements,
            },
        })
    }

    return nodes
})

const pageNodes = computed<Node[]>(() => {
    if (props.pages.length === 0) return []

    const pageMap = new Map(props.pages.map((p) => [p.id, p]))
    const childrenMap = new Map<string | null, string[]>()

    props.pages.forEach((page) => {
        const parentId = page.parent_page_id ?? null
        ;(childrenMap.get(parentId) ?? childrenMap.set(parentId, []).get(parentId)!).push(page.id)
    })

    // Count leaf nodes in subtree to allocate vertical space
    function subtreeLeaves(id: string): number {
        const children = childrenMap.get(id) ?? []
        if (children.length === 0) return 1
        return children.reduce((sum, cid) => sum + subtreeLeaves(cid), 0)
    }

    const positions = new Map<string, { x: number; y: number }>()

    function layout(id: string, depth: number, centerY: number) {
        positions.set(id, { x: depth * X_STEP, y: centerY - NODE_H / 2 })

        const children = childrenMap.get(id) ?? []
        if (children.length === 0) return

        const totalLeaves = children.reduce((sum, cid) => sum + subtreeLeaves(cid), 0)
        const totalH = totalLeaves * Y_STEP - (Y_STEP - NODE_H)
        let currentY = centerY - totalH / 2 + NODE_H / 2

        children.forEach((cid) => {
            const leaves = subtreeLeaves(cid)
            const childH = leaves * Y_STEP - (Y_STEP - NODE_H)
            const childCenterY = currentY + childH / 2 - NODE_H / 2
            layout(cid, depth + 1, childCenterY)
            currentY += childH + (Y_STEP - NODE_H)
        })
    }

    // Find roots (pages without parent)
    const roots = childrenMap.get(null) ?? []
    const totalRootLeaves = roots.reduce((sum, id) => sum + subtreeLeaves(id), 0)
    const totalRootH = totalRootLeaves * Y_STEP - (Y_STEP - NODE_H)
    let currentY = -totalRootH / 2 + NODE_H / 2

    roots.forEach((rootId) => {
        const leaves = subtreeLeaves(rootId)
        const rootH = leaves * Y_STEP - (Y_STEP - NODE_H)
        layout(rootId, 0, currentY + rootH / 2 - NODE_H / 2)
        currentY += rootH + (Y_STEP - NODE_H)
    })

    return props.pages.map((page) => {
        const pos = positions.get(page.id) ?? { x: 0, y: 0 }
        return {
            id: page.id,
            type: 'PrototypeScreen',
            width: NODE_W,
            height: NODE_H,
            position: pos,
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
