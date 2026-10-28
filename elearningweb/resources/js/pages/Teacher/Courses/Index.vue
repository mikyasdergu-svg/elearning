<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';

const props = defineProps<{ courses: Array<Record<string, any>> }>();
</script>

<template>
    <Head title="My Courses" />

    <div class="space-y-6 p-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold">My courses</h1>
                <p class="text-sm text-muted-foreground">Edit, publish, or delete your courses.</p>
            </div>
            <div class="flex gap-2">
                <Link :href="route('teacher.dashboard')" class="rounded-full bg-secondary px-5 py-3 text-sm">Dashboard</Link>
                <Link :href="route('teacher.courses.create')" class="rounded-full bg-primary px-5 py-3 text-white">New course</Link>
            </div>
        </div>

        <div class="grid gap-4">
            <div v-for="course in props.courses" :key="course.id" class="rounded-3xl border border-border bg-card p-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">{{ course.title }}</h2>
                        <p class="text-sm text-muted-foreground">{{ course.status }} · {{ course.enrollments_count }} enrollments</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <Link :href="route('teacher.courses.edit', { course: course.id })" class="rounded-full bg-secondary px-4 py-2 text-sm">Edit</Link>
                        <Link :href="route('teacher.courses.lessons.index', { course: course.id })" class="rounded-full bg-secondary px-4 py-2 text-sm">Lessons</Link>
                        <Link :href="route('teacher.courses.progress', { course: course.id })" class="rounded-full bg-secondary px-4 py-2 text-sm">Progress</Link>
                        <Form
                            v-if="course.enrollments_count === 0"
                            :action="route('teacher.courses.destroy', { course: course.id })"
                            method="delete"
                        >
                            <button type="submit" class="rounded-full bg-destructive px-4 py-2 text-sm text-white">Delete</button>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
