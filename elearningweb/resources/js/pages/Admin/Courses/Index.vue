<script setup lang="ts">
import { Head, Link, Form } from '@inertiajs/vue3';
import { namedRoute as route } from '@/lib/namedRoute';

const props = defineProps<{ courses: Array<Record<string, any>> }>();
</script>

<template>
    <Head title="Manage Courses" />

    <div class="space-y-6 p-6">
        <div class="rounded-3xl border border-border bg-card p-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold">Manage courses</h1>
                    <p class="mt-2 text-sm text-muted-foreground">View published and draft courses, delete any course as admin.</p>
                </div>
                <Link :href="route('admin.dashboard')" class="rounded-full bg-secondary px-5 py-3 text-sm">Back to dashboard</Link>
            </div>
        </div>

        <div class="space-y-4">
            <div v-for="course in props.courses" :key="course.id" class="rounded-3xl border border-border bg-card p-6">
                <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-center">
                    <div>
                        <h2 class="text-xl font-semibold">{{ course.title }}</h2>
                        <p class="text-sm text-muted-foreground">Teacher: {{ course.teacher.name }}</p>
                        <p class="text-sm">Status: {{ course.status }}</p>
                        <p class="text-sm">Price: ${{ Number(course.price).toFixed(2) }}</p>
                        <p class="text-sm">Enrollments: {{ course.enrollments_count }}</p>
                    </div>
                    <Form :action="route('admin.courses.destroy', { course: course.id })" method="delete" class="text-right">
                        <button type="submit" class="rounded-full bg-destructive px-4 py-3 text-white">Delete course</button>
                    </Form>
                </div>
            </div>
        </div>
    </div>
</template>
