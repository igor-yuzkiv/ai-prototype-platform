<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const step = ref(0)
let timer: ReturnType<typeof setInterval>

onMounted(() => {
    timer = setInterval(() => {
        step.value = (step.value + 1) % 3
    }, 900)
})

onUnmounted(() => clearInterval(timer))
</script>

<template>
    <div class="layout-animation" :class="`step-${step}`">
        <div class="col col-left">
            <div class="block block-a" />
            <div class="block block-b" />
        </div>
        <div class="col col-right">
            <div class="block block-c" />
        </div>
    </div>
</template>

<style scoped>
.layout-animation {
    display: flex;
    flex-direction: row;
    gap: 12px;
    width: 100%;
    height: 100%;
    padding: 20px;
    box-sizing: border-box;
}

.col {
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: flex 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.col-left  { flex: 1; }
.col-right { flex: 1; }

.block {
    border-radius: 8px;
    background: #e2e8f0;
    transition: flex 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    flex: 1;
}

.dark .block {
    background: #1e293b;
}

/* Step 0 — block-a active */
.step-0 .col-left  { flex: 1.4; }
.step-0 .col-right { flex: 0.8; }
.step-0 .block-a   { flex: 2; }
.step-0 .block-b   { flex: 1; }
.step-0 .block-c   { flex: 1; }

/* Step 1 — block-b active */
.step-1 .col-left  { flex: 1.4; }
.step-1 .col-right { flex: 0.8; }
.step-1 .block-a   { flex: 1; }
.step-1 .block-b   { flex: 2; }
.step-1 .block-c   { flex: 1; }

/* Step 2 — block-c active */
.step-2 .col-left  { flex: 0.8; }
.step-2 .col-right { flex: 1.4; }
.step-2 .block-a   { flex: 1; }
.step-2 .block-b   { flex: 1; }
.step-2 .block-c   { flex: 1; }
</style>
