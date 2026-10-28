<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { namedRoute as route } from '@/lib/namedRoute';

const props = defineProps<{ payments: Array<Record<string, any>> }>();
</script>

<template>
    <Head title="Pending Payments" />

    <div class="space-y-6 p-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-border bg-card p-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold">Pending payments</h1>
                <p class="mt-2 text-sm text-muted-foreground">Approve or reject payments before granting full course access.</p>
            </div>
            <Link :href="route('admin.dashboard')" class="rounded-full bg-secondary px-5 py-3 text-sm">Back to dashboard</Link>
        </div>

        <div v-if="props.payments.length === 0" class="rounded-3xl border border-border bg-card p-6 text-center text-muted-foreground">
            There are no pending payments at the moment.
        </div>

        <div class="grid gap-4">
            <div v-for="payment in props.payments" :key="payment.id" class="rounded-3xl border border-border bg-card p-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">{{ payment.enrollment.course.title }}</h2>
                        <p class="text-sm text-muted-foreground">Student: {{ payment.enrollment.student.name }} ({{ payment.enrollment.student.email }})</p>
                        <p class="text-sm text-muted-foreground">Amount: ${{ Number(payment.amount ?? 0).toFixed(2) }}</p>
                        <p class="text-sm text-muted-foreground">Submitted: {{ new Date(payment.created_at).toLocaleString() }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <Form :action="route('admin.payments.approve', { payment: payment.id })" method="post" class="inline-flex">
                            <input type="hidden" name="_method" value="PUT" />
                            <button type="submit" class="rounded-full bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700">Approve</button>
                        </Form>
                        <Form :action="route('admin.payments.reject', { payment: payment.id })" method="post" class="grid gap-2">
                            <input type="hidden" name="_method" value="PUT" />
                            <input type="text" name="reason" placeholder="Rejection reason" class="rounded-xl border border-input px-4 py-3 text-sm" required />
                            <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-700">Reject</button>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
