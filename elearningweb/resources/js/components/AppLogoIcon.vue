<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 64 64"
        :class="className"
        v-bind="$attrs"
    >
        <rect width="64" height="64" rx="16" fill="currentColor" opacity="0.16" />
        <path
            d="M20 20h24a4 4 0 0 1 4 4v6H16v-6a4 4 0 0 1 4-4Z"
            fill="currentColor"
        />
        <path
            d="M16 32h32v18a4 4 0 0 1-4 4H20a4 4 0 0 1-4-4V32Z"
            fill="currentColor"
        />
        <path d="M27 24h4v24h-4z" fill="white" />
        <path
            d="M36 24h4v16c0 2.21-1.79 4-4 4s-4-1.79-4-4v-8"
            fill="white"
        />
    </svg>
</template>
