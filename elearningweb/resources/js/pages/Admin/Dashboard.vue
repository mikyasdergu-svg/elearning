<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { namedRoute as route } from '@/lib/namedRoute';

const props = defineProps<{ totalRevenue: number; totalUsers: number; totalCourses: number; teacherCount: number; pendingTeachers: number; pendingPayments: number }>();
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="space-y-6 p-6">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-3xl border border-border bg-card p-6">
                <p class="text-sm text-muted-foreground">Total revenue</p>
                <div class="mt-3 text-3xl font-semibold">${{ Number(props.totalRevenue ?? 0).toFixed(2) }}</div>
            </div>
            <div class="rounded-3xl border border-border bg-card p-6">
                <p class="text-sm text-muted-foreground">Users</p>
                <div class="mt-3 text-3xl font-semibold">{{ props.totalUsers }}</div>
            </div>
            <div class="rounded-3xl border border-border bg-card p-6">
                <p class="text-sm text-muted-foreground">Courses</p>
                <div class="mt-3 text-3xl font-semibold">{{ props.totalCourses }}</div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-3xl border border-border bg-card p-6 flex flex-col gap-4">
                <div>
                    <p class="text-sm text-muted-foreground">Pending teacher registrations</p>
                    <div class="mt-2 text-2xl font-semibold">{{ props.pendingTeachers }}</div>
                </div>
                <Link :href="route('admin.teachers.pending')" class="rounded-full bg-primary px-5 py-3 text-white">Review applications</Link>
            </div>
            <div class="rounded-3xl border border-border bg-card p-6 flex flex-col gap-4">
                <div>
                    <p class="text-sm text-muted-foreground">Pending payment verifications</p>
                    <div class="mt-2 text-2xl font-semibold">{{ props.pendingPayments }}</div>
                </div>
                <Link :href="route('admin.payments.index')" class="rounded-full bg-primary px-5 py-3 text-white">Review pending payments</Link>
            </div>
        </div>

        <div class="rounded-3xl border border-border bg-card p-6 flex flex-wrap gap-4">
            <Link :href="route('admin.courses.index')" class="rounded-full bg-secondary px-5 py-3 text-sm">Manage courses</Link>
            <Link :href="route('admin.users.index')" class="rounded-full bg-secondary px-5 py-3 text-sm">Manage users</Link>
            <Link :href="route('admin.sales.index')" class="rounded-full bg-secondary px-5 py-3 text-sm">View sales reports</Link>
        </div>
    </div>
</template>
