<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

const props = defineProps<{ course: Record<string, any>; students: Array<Record<string, any>> }>();
</script>

<template>
    <Head title="Student Progress" />

    <div class="mx-auto max-w-5xl space-y-6 p-6">
        <div class="rounded-3xl border border-border bg-card p-6">
            <h1 class="text-3xl font-semibold">Progress for {{ props.course.title }}</h1>
            <p class="mt-2 text-sm text-muted-foreground">See enrollment and completion status for each student.</p>
        </div>

        <div class="rounded-3xl border border-border bg-card p-6">
            <table class="min-w-full divide-y divide-border text-left text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3">Student</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Enrolled</th>
                        <th class="px-4 py-3">Paid</th>
                        <th class="px-4 py-3">Completion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    <tr v-for="student in props.students" :key="student.student.id">
                        <td class="px-4 py-3">{{ student.student.name }}</td>
                        <td class="px-4 py-3">{{ student.student.email }}</td>
                        <td class="px-4 py-3">{{ new Date(student.enrolled_at).toLocaleDateString() }}</td>
                        <td class="px-4 py-3">{{ student.paid ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">{{ student.completion_percentage }}%</td>
                    </tr>
                    <tr v-for="student in props.students" :key="`${student.student.id}-details`" class="bg-muted">
                        <td colspan="5" class="px-4 py-3">
                            <div class="space-y-2">
                                <div class="text-sm font-semibold">Completed lessons</div>
                                <div v-if="student.completed_lessons.length === 0" class="text-sm text-muted-foreground">No lessons completed yet.</div>
                                <ul v-else class="space-y-1 text-sm">
                                    <li v-for="lesson in student.completed_lessons" :key="lesson.id">• {{ lesson.title }}</li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
