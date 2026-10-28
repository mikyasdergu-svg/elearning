<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

const props = defineProps<{ status: string; rejectionReason: string | null }>();
</script>

<template>
    <Head title="Teacher Application" />

    <div class="mx-auto max-w-3xl p-6">
        <div class="rounded-3xl border border-border bg-card p-8 text-center">
            <h1 class="text-3xl font-semibold">Teacher registration pending</h1>
            <p class="mt-4 text-sm text-muted-foreground">Your teacher application is being reviewed. You will receive access once approved.</p>
            <div v-if="props.status === 'rejected'" class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-left text-sm text-red-700">
                <strong>Rejection reason:</strong>
                <p>{{ props.rejectionReason }}</p>
            </div>
        </div>
    </div>
</template>
