<script setup lang="ts">
import { Head, Form, Link } from '@inertiajs/vue3';
import { namedRoute as route } from '@/lib/namedRoute';

const props = defineProps<{ users: Array<Record<string, any>> }>();
</script>

<template>
    <Head title="Manage Users" />

    <div class="space-y-6 p-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-border bg-card p-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold">Manage users</h1>
                <p class="mt-2 text-sm text-muted-foreground">Edit user roles, emails, and delete accounts.</p>
            </div>
            <Link :href="route('admin.dashboard')" class="rounded-full bg-secondary px-5 py-3 text-sm">Back to dashboard</Link>
        </div>

        <div class="space-y-4">
            <div v-for="user in props.users" :key="user.id" class="rounded-3xl border border-border bg-card p-6">
                <div class="mb-4 text-sm text-muted-foreground">
                    Registered: {{ new Date(user.created_at).toLocaleDateString() }}
                </div>
                <div class="grid gap-4 md:grid-cols-4 md:items-end">
                    <Form :action="route('admin.users.update', { user: user.id })" method="put" class="grid gap-4 md:col-span-3 md:grid-cols-3 md:items-end">
                        <label class="grid gap-2 text-sm">
                            <span>Name</span>
                            <input type="text" name="name" :value="user.name" class="rounded-xl border border-input px-4 py-3" required />
                        </label>
                        <label class="grid gap-2 text-sm">
                            <span>Email</span>
                            <input type="email" name="email" :value="user.email" class="rounded-xl border border-input px-4 py-3" required />
                        </label>
                        <label class="grid gap-2 text-sm">
                            <span>Role</span>
                            <select name="role" class="rounded-xl border border-input px-4 py-3" required>
                                <option value="student" :selected="user.role === 'student'">Student</option>
                                <option value="teacher" :selected="user.role === 'teacher'">Teacher</option>
                                <option value="admin" :selected="user.role === 'admin'">Admin</option>
                            </select>
                        </label>
                        <div class="flex flex-wrap gap-2 md:col-span-3">
                            <button type="submit" class="rounded-full bg-primary px-4 py-3 text-white">Save</button>
                        </div>
                    </Form>
                    <Form :action="route('admin.users.destroy', { user: user.id })" method="delete" class="flex items-end">
                        <button type="submit" class="rounded-full bg-destructive px-4 py-3 text-white">Delete</button>
                    </Form>
                </div>
            </div>
        </div>
    </div>
</template>
