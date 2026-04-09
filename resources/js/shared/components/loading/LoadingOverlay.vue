<script setup lang="ts">
withDefaults(
    defineProps<{
        message?: string
    }>(),
    {
        message: 'Loading',
    }
)
</script>
<template>
    <div class="inset-0 bg-black/60 backdrop-blur-md fixed z-[9999] flex items-center justify-center">
        <div class="gap-6 flex flex-col items-center">
            <!--
                Wrench outline drawn sequentially, tracing the contour:
                cap → handle top → top jaw → [gap/opening] → bottom jaw → handle bottom
            -->
            <svg class="wrench" viewBox="0 0 115 42" width="176" height="64">
                <path
                    class="cap"
                    d="M10,27 A6,6,0,0,1,10,15"
                    fill="none"
                    stroke-width="2.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <line
                    class="handle-top"
                    x1="10"
                    y1="15"
                    x2="80"
                    y2="15"
                    fill="none"
                    stroke-width="2.5"
                    stroke-linecap="round"
                />
                <path
                    class="jaw-top"
                    d="M80,15 L80,4 L110,4 L110,18"
                    fill="none"
                    stroke-width="2.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    class="jaw-bot"
                    d="M110,24 L110,38 L80,38 L80,27"
                    fill="none"
                    stroke-width="2.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <line
                    class="handle-bot"
                    x1="80"
                    y1="27"
                    x2="10"
                    y2="27"
                    fill="none"
                    stroke-width="2.5"
                    stroke-linecap="round"
                />
            </svg>

            <span class="text-white uppercase"> {{ message }} </span>
        </div>
    </div>
</template>

<style scoped>
.wrench {
    overflow: visible;
}

.cap,
.handle-top,
.jaw-top,
.jaw-bot,
.handle-bot {
    stroke: white;
}

/* ─── Cap  (arc length ≈ π × 6 ≈ 19) ─────────────────── */
.cap {
    stroke-dasharray: 19;
    animation: draw-cap 3.8s ease-in-out infinite;
}
@keyframes draw-cap {
    0% {
        stroke-dashoffset: 19;
        opacity: 1;
    }
    9% {
        stroke-dashoffset: 0;
        opacity: 1;
    }
    90% {
        stroke-dashoffset: 0;
        opacity: 1;
    }
    97% {
        stroke-dashoffset: 0;
        opacity: 0;
    }
    100% {
        stroke-dashoffset: 19;
        opacity: 0;
    }
}

/* ─── Handle top  (length = 70) ───────────────────────── */
.handle-top {
    stroke-dasharray: 70;
    animation: draw-handle-top 3.8s ease-in-out infinite;
}
@keyframes draw-handle-top {
    0% {
        stroke-dashoffset: 70;
        opacity: 0;
    }
    9% {
        stroke-dashoffset: 70;
        opacity: 0;
    }
    10% {
        stroke-dashoffset: 70;
        opacity: 1;
    }
    32% {
        stroke-dashoffset: 0;
        opacity: 1;
    }
    90% {
        stroke-dashoffset: 0;
        opacity: 1;
    }
    97% {
        stroke-dashoffset: 0;
        opacity: 0;
    }
    100% {
        stroke-dashoffset: 70;
        opacity: 0;
    }
}

/* ─── Top jaw  (11 + 30 + 14 = 55) ───────────────────── */
.jaw-top {
    stroke-dasharray: 55;
    animation: draw-jaw-top 3.8s ease-in-out infinite;
}
@keyframes draw-jaw-top {
    0% {
        stroke-dashoffset: 55;
        opacity: 0;
    }
    32% {
        stroke-dashoffset: 55;
        opacity: 0;
    }
    33% {
        stroke-dashoffset: 55;
        opacity: 1;
    }
    53% {
        stroke-dashoffset: 0;
        opacity: 1;
    }
    90% {
        stroke-dashoffset: 0;
        opacity: 1;
    }
    97% {
        stroke-dashoffset: 0;
        opacity: 0;
    }
    100% {
        stroke-dashoffset: 55;
        opacity: 0;
    }
}

/* ─── Bottom jaw  (14 + 30 + 11 = 55) ────────────────── */
/*     brief pause after top jaw to visually show the gap */
.jaw-bot {
    stroke-dasharray: 55;
    animation: draw-jaw-bot 3.8s ease-in-out infinite;
}
@keyframes draw-jaw-bot {
    0% {
        stroke-dashoffset: 55;
        opacity: 0;
    }
    56% {
        stroke-dashoffset: 55;
        opacity: 0;
    }
    57% {
        stroke-dashoffset: 55;
        opacity: 1;
    }
    74% {
        stroke-dashoffset: 0;
        opacity: 1;
    }
    90% {
        stroke-dashoffset: 0;
        opacity: 1;
    }
    97% {
        stroke-dashoffset: 0;
        opacity: 0;
    }
    100% {
        stroke-dashoffset: 55;
        opacity: 0;
    }
}

/* ─── Handle bottom  (length = 70) ───────────────────── */
.handle-bot {
    stroke-dasharray: 70;
    animation: draw-handle-bot 3.8s ease-in-out infinite;
}
@keyframes draw-handle-bot {
    0% {
        stroke-dashoffset: 70;
        opacity: 0;
    }
    74% {
        stroke-dashoffset: 70;
        opacity: 0;
    }
    75% {
        stroke-dashoffset: 70;
        opacity: 1;
    }
    90% {
        stroke-dashoffset: 0;
        opacity: 1;
    }
    97% {
        stroke-dashoffset: 0;
        opacity: 0;
    }
    100% {
        stroke-dashoffset: 70;
        opacity: 0;
    }
}

/* ─── Label ───────────────────────────────────────────── */
.label {
    animation: label-fade 3.8s ease-in-out infinite;
}
@keyframes label-fade {
    0%,
    60% {
        opacity: 0.3;
    }
    82% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    97% {
        opacity: 0;
    }
    100% {
        opacity: 0;
    }
}
</style>
