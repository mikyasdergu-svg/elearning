<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { namedRoute as route } from '@/lib/namedRoute';

const props = defineProps<{ enrollment: Record<string, any>; stripePublicKey: string | null; stripeMode: boolean }>();
</script>

<template>
    <Head title="Checkout" />

    <div class="mx-auto max-w-3xl space-y-6 p-6">
        <div class="rounded-3xl border border-border bg-card p-8">
            <h1 class="text-3xl font-semibold">Secure payment</h1>
            <p class="mt-2 text-sm text-muted-foreground">Complete your order to unlock full course access.</p>

            <div class="mt-6 space-y-4 rounded-3xl bg-muted p-6">
                <div class="flex items-center justify-between">
                    <span>Course</span>
                    <span class="font-semibold">{{ props.enrollment.course.title }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Amount</span>
                    <span class="font-semibold">${{ Number(props.enrollment.price ?? 0).toFixed(2) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm text-muted-foreground">
                    <span>Stripe mode</span>
                    <span>{{ props.stripeMode ? 'Test' : 'Live' }}</span>
                </div>
            </div>

            <Form method="post" :action="route('checkout.pay', { enrollment: props.enrollment.id })" class="mt-6">
                <button type="submit" class="rounded-full bg-primary px-6 py-3 text-white">Pay now</button>
            </Form>
        </div>
    </div>
</template>
