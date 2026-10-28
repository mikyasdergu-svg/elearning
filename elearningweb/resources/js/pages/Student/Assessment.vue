<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { namedRoute as route } from '@/lib/namedRoute';

const props = defineProps<{
    course: Record<string, any>;
    lesson: Record<string, any>;
    assessment: Record<string, any>;
    submission: Record<string, any> | null;
}>();

const assessmentTitle = props.assessment.type
    ? `${props.assessment.type.charAt(0).toUpperCase()}${props.assessment.type.slice(1)}`
    : 'Assessment';
</script>

<template>
    <Head :title="`Assessment for ${props.lesson.title}`" />

    <div class="mx-auto max-w-4xl space-y-6 p-6">
        <div class="rounded-3xl border border-border bg-card p-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold">{{ assessmentTitle }} Assessment</h1>
                    <p class="text-sm text-muted-foreground">{{ props.lesson.title }} · {{ props.course.title }}</p>
                </div>
                <Link :href="route('courses.lessons.watch', { course: props.course.id, lesson: props.lesson.id })" class="rounded-full bg-secondary px-4 py-2 text-sm">Back to lesson</Link>
            </div>
        </div>

        <div class="rounded-3xl border border-border bg-card p-6 space-y-4">
            <div>
                <h2 class="text-xl font-semibold">Instructions</h2>
                <p class="mt-2 text-sm leading-7 text-muted-foreground">
                    {{ props.assessment.instructions || 'Complete the assessment below and submit your response or supporting file.' }}
                </p>
            </div>
            <div class="grid gap-2 text-sm text-muted-foreground">
                <p><strong>Type:</strong> {{ props.assessment.type }}</p>
                <p v-if="props.assessment.due_date"><strong>Due date:</strong> {{ new Date(props.assessment.due_date).toLocaleString() }}</p>
            </div>
        </div>

        <div class="rounded-3xl border border-border bg-card p-6">
            <Form :action="route('courses.lessons.assessment.submit', { course: props.course.id, lesson: props.lesson.id })" method="post" enctype="multipart/form-data" class="space-y-4">
                <label class="grid gap-2 text-sm">
                    <span>Your answer</span>
                    <textarea name="response" class="w-full rounded-xl border border-input px-4 py-3" rows="6">{{ props.submission?.response || '' }}</textarea>
                </label>

                <label class="grid gap-2 text-sm">
                    <span>Upload supporting file</span>
                    <input type="file" name="submission_file" accept=".pdf,image/*,.zip,.doc,.docx" class="rounded-xl border border-input px-4 py-3" />
                    <p class="text-xs text-muted-foreground">PDF, image, ZIP, or document files up to 3MB.</p>
                </label>

                <button type="submit" class="rounded-full bg-primary px-6 py-3 text-white">Submit assessment</button>
            </Form>

            <div v-if="props.submission" class="rounded-3xl border border-border bg-muted p-4 text-sm">
                <p class="font-semibold">Last submission</p>
                <p>Status: {{ props.submission.status }}</p>
                <p v-if="props.submission.feedback">Feedback: {{ props.submission.feedback }}</p>
                <p v-if="props.submission.file_path" class="mt-2">
                    <a :href="`/storage/${props.submission.file_path}`" class="text-primary underline">Download uploaded file</a>
                </p>
            </div>
        </div>
    </div>
</template>
