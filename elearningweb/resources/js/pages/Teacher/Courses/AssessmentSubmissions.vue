<script setup lang="ts">
import { Head, Link, Form } from '@inertiajs/vue3';

const props = defineProps<{
    course: Record<string, any>;
    lesson: Record<string, any>;
    assessment: Record<string, any>;
    submissions: Array<Record<string, any>>;
}>();
</script>

<template>
    <Head title="Assessment Submissions" />

    <div class="mx-auto max-w-4xl space-y-6 p-6">
        <div class="rounded-3xl border border-border bg-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Submissions — {{ props.lesson.title }}</h1>
                    <p class="text-sm text-muted-foreground">{{ props.course.title }}</p>
                </div>
                <Link :href="`/teacher/courses/${props.course.id}/lessons`" class="rounded-full bg-secondary px-4 py-2 text-sm">Back to lessons</Link>
            </div>
        </div>

        <div class="rounded-3xl border border-border bg-card p-6 space-y-4">
            <div v-if="props.submissions.length === 0" class="text-sm text-muted-foreground">
                No submissions yet.
            </div>

            <div v-for="s in props.submissions" :key="s.id" class="border rounded p-4 space-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-semibold">{{ s.student.name }} ({{ s.student.email }})</div>
                        <div class="text-sm text-muted-foreground">Status: {{ s.status }}</div>
                    </div>
                    <div>
                        <a v-if="s.file_path" :href="`/storage/${s.file_path}`" class="text-primary underline">Download file</a>
                    </div>
                </div>

                <div class="text-sm">
                    <p class="font-medium">Response</p>
                    <p class="text-muted-foreground" v-if="s.response">{{ s.response }}</p>
                    <p v-else class="text-muted-foreground italic">No text response provided.</p>
                </div>

                <Form :action="`/teacher/courses/${props.course.id}/lessons/${props.lesson.id}/submissions/${s.id}`" method="post" class="space-y-2">
                    <input type="hidden" name="_method" value="PUT" />
                    <label class="grid gap-2 text-sm">
                        <span>Feedback</span>
                        <textarea name="feedback" class="w-full rounded-xl border border-input px-4 py-3">{{ s.feedback || '' }}</textarea>
                    </label>
                    <label class="grid gap-2 text-sm">
                        <span>Mark</span>
                        <select name="status" class="w-full rounded-xl border border-input px-4 py-3">
                            <option value="submitted" :selected="s.status === 'submitted'">Submitted</option>
                            <option value="graded" :selected="s.status === 'graded'">Graded</option>
                            <option value="needs_revision" :selected="s.status === 'needs_revision'">Needs revision</option>
                        </select>
                    </label>
                    <div class="flex gap-2">
                        <button type="submit" class="rounded-full bg-primary px-4 py-2 text-white">Save</button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
