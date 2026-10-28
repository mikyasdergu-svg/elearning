<script setup lang="ts">
import { Head, Form, Link } from '@inertiajs/vue3';

const props = defineProps<{ pendingTeachers: Array<Record<string, any>> }>();
</script>

<template>
    <Head title="Pending Teacher Applications" />

    <div class="space-y-6 p-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-border bg-card p-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold">Pending teacher applications</h1>
                <p class="mt-2 text-sm text-muted-foreground">Approve or reject teacher registrations.</p>
            </div>
            <Link :href="route('admin.dashboard')" class="rounded-full bg-secondary px-5 py-3 text-sm">Back to dashboard</Link>
        </div>

        <div v-if="props.pendingTeachers.length === 0" class="rounded-3xl border border-border bg-card p-6 text-center text-muted-foreground">
            No pending teacher applications.
        </div>

        <div class="grid gap-4">
            <div v-for="teacher in props.pendingTeachers" :key="teacher.id" class="rounded-3xl border border-border bg-card p-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">{{ teacher.name }}</h2>
                        <p class="text-sm text-muted-foreground">{{ teacher.email }}</p>
                        <p class="text-xs text-muted-foreground">Applied: {{ new Date(teacher.created_at).toLocaleDateString() }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <Form :action="route('admin.teachers.approve', { user: teacher.id })" method="post" class="flex items-center gap-2">
                            <input type="hidden" name="_method" value="PUT" />
                            <button type="submit" class="rounded-full bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-white min-w-[120px]">Approve</button>
                        </Form>
                        <Form :action="route('admin.teachers.reject', { user: teacher.id })" method="post" class="grid gap-2 md:w-[320px]">
                            <input type="hidden" name="_method" value="PUT" />
                            <input type="text" name="reason" placeholder="Rejection reason" class="rounded-xl border border-input px-4 py-3" required />
                            <button type="submit" class="rounded-full bg-red-600 hover:bg-red-700 px-4 py-2 text-white min-w-[120px]">Reject</button>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
