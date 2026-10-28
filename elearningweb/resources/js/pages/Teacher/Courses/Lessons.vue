<script setup lang="ts">
import { Form, Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { namedRoute as route } from '@/lib/namedRoute';

const props = defineProps<{ course: Record<string, any> }>();
const editingLessonId = ref<number | null>(null);

function toDatetimeLocal(value: string): string {
    const date = new Date(value);
    const offset = date.getTimezoneOffset();
    const local = new Date(date.getTime() - offset * 60 * 1000);
    return local.toISOString().slice(0, 16);
}
</script>

<template>
    <Head title="Manage Lessons" />

    <div class="mx-auto max-w-4xl space-y-6 p-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-border bg-card p-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold">{{ props.course.title }}</h1>
                <p class="text-sm text-muted-foreground">Manage lessons and release dates.</p>
            </div>
            <Link :href="route('teacher.dashboard')" class="rounded-full bg-secondary px-4 py-2 text-sm">Back to dashboard</Link>
        </div>

        <div class="space-y-4">
            <div v-for="(lesson, index) in props.course.lessons" :key="lesson.id" class="rounded-2xl border border-border p-4">
                <div v-if="editingLessonId !== lesson.id" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="font-semibold">{{ lesson.title }}</h3>
                        <p class="text-sm text-muted-foreground">Release: {{ new Date(lesson.release_date).toLocaleString() }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" class="rounded-full bg-secondary px-4 py-2 text-sm" @click="editingLessonId = lesson.id">
                            Edit
                        </button>
                        <Link
                            :href="`/teacher/courses/${props.course.id}/lessons/${lesson.id}/submissions`"
                            class="rounded-full bg-primary px-4 py-2 text-sm text-white"
                        >
                            {{ lesson.assessment ? 'View submissions' : 'Configure assessment' }}
                        </Link>
                        <Form
                            :action="route('teacher.courses.lessons.destroy', { course: props.course.id, lesson: lesson.id })"
                            method="delete"
                        >
                            <button type="submit" class="rounded-full bg-destructive px-4 py-2 text-sm text-white">Delete</button>
                        </Form>
                    </div>
                </div>

                <Form
                    v-else
                    :action="route('teacher.courses.lessons.update', { course: props.course.id, lesson: lesson.id })"
                    method="put"
                    enctype="multipart/form-data"
                    class="space-y-4"
                >
                    <label class="grid gap-2 text-sm">
                        <span>Lesson title</span>
                        <input type="text" name="title" :value="lesson.title" class="w-full rounded-xl border border-input px-4 py-3" required />
                    </label>
                    <label class="grid gap-2 text-sm">
                        <span>Description</span>
                        <textarea name="description" class="w-full rounded-xl border border-input px-4 py-3" required>{{ lesson.description }}</textarea>
                    </label>
                    <label class="grid gap-2 text-sm">
                        <span>Video source</span>
                        <select name="video_type" class="w-full rounded-xl border border-input px-4 py-3" required>
                            <option value="youtube" :selected="lesson.video_type === 'youtube'">YouTube URL</option>
                            <option value="upload" :selected="lesson.video_type === 'upload'">Upload file</option>
                        </select>
                    </label>
                    <label class="grid gap-2 text-sm">
                        <span>YouTube URL</span>
                        <input type="url" name="video_url" :value="lesson.video_type === 'youtube' ? lesson.video_url : ''" class="w-full rounded-xl border border-input px-4 py-3" />
                    </label>
                    <label class="grid gap-2 text-sm">
                        <span>Replace uploaded video (optional)</span>
                        <input type="file" name="video_upload" accept="video/mp4,video/quicktime,video/x-msvideo" class="rounded-xl border border-input px-4 py-3" />
                        <p class="text-xs text-muted-foreground">MP4, MOV, or AVI files up to 200MB.</p>
                    </label>

                    <div class="rounded-3xl border border-border bg-muted/10 p-4">
                        <h3 class="text-base font-semibold">Assessment settings</h3>
                        <p class="text-sm text-muted-foreground">Add a quiz, exam, or project for students to submit after this lesson.</p>
                        <label class="grid gap-2 text-sm pt-4">
                            <span>Assessment type</span>
                            <select name="assessment_type" class="w-full rounded-xl border border-input px-4 py-3">
                                <option value="none">None</option>
                                <option value="quiz" :selected="lesson.assessment?.type === 'quiz'">Quiz</option>
                                <option value="exam" :selected="lesson.assessment?.type === 'exam'">Exam</option>
                                <option value="project" :selected="lesson.assessment?.type === 'project'">Project</option>
                            </select>
                        </label>
                        <label class="grid gap-2 text-sm">
                            <span>Assessment instructions</span>
                            <textarea name="assessment_instructions" class="w-full rounded-xl border border-input px-4 py-3">{{ lesson.assessment?.instructions || '' }}</textarea>
                        </label>
                        <label class="grid gap-2 text-sm">
                            <span>Assessment due date</span>
                            <input type="datetime-local" name="assessment_due_date" :value="lesson.assessment?.due_date ? toDatetimeLocal(lesson.assessment.due_date) : ''" class="w-full rounded-xl border border-input px-4 py-3" />
                        </label>
                    </div>

                    <label class="grid gap-2 text-sm">
                        <span>Release date</span>
                        <input
                            type="datetime-local"
                            name="release_date"
                            :value="toDatetimeLocal(lesson.release_date)"
                            class="w-full rounded-xl border border-input px-4 py-3"
                            required
                        />
                    </label>
                    <div class="flex gap-2">
                        <button type="submit" class="rounded-full bg-primary px-6 py-3 text-white">Save lesson</button>
                        <button type="button" class="rounded-full bg-secondary px-6 py-3" @click="editingLessonId = null">Cancel</button>
                    </div>
                </Form>
            </div>
        </div>

        <div class="rounded-3xl border border-border bg-card p-6">
            <h2 class="text-xl font-semibold">Add lesson</h2>
            <Form :action="route('teacher.courses.lessons.store', { course: props.course.id })" method="post" enctype="multipart/form-data" class="mt-4 space-y-4">
                <label class="grid gap-2 text-sm">
                    <span>Lesson title</span>
                    <input type="text" name="title" class="w-full rounded-xl border border-input px-4 py-3" required />
                </label>
                <label class="grid gap-2 text-sm">
                    <span>Description</span>
                    <textarea name="description" class="w-full rounded-xl border border-input px-4 py-3" required></textarea>
                </label>
                <label class="grid gap-2 text-sm">
                    <span>Video source</span>
                    <select name="video_type" class="w-full rounded-xl border border-input px-4 py-3" required>
                        <option value="youtube">YouTube URL</option>
                        <option value="upload">Upload file</option>
                    </select>
                </label>
                <label class="grid gap-2 text-sm">
                    <span>YouTube URL</span>
                    <input type="url" name="video_url" class="w-full rounded-xl border border-input px-4 py-3" />
                </label>
                <label class="grid gap-2 text-sm">
                    <span>Upload video</span>
                    <input type="file" name="video_upload" accept="video/mp4,video/quicktime,video/x-msvideo" class="rounded-xl border border-input px-4 py-3" />
                </label>
                <label class="grid gap-2 text-sm">
                    <span>Assessment type</span>
                    <select name="assessment_type" class="w-full rounded-xl border border-input px-4 py-3">
                        <option value="none">None</option>
                        <option value="quiz">Quiz</option>
                        <option value="exam">Exam</option>
                        <option value="project">Project</option>
                    </select>
                </label>
                <label class="grid gap-2 text-sm">
                    <span>Assessment instructions</span>
                    <textarea name="assessment_instructions" class="w-full rounded-xl border border-input px-4 py-3"></textarea>
                </label>
                <label class="grid gap-2 text-sm">
                    <span>Assessment due date</span>
                    <input type="datetime-local" name="assessment_due_date" class="w-full rounded-xl border border-input px-4 py-3" />
                </label>
                <label class="grid gap-2 text-sm">
                    <span>Release date</span>
                    <input type="datetime-local" name="release_date" class="w-full rounded-xl border border-input px-4 py-3" required />
                </label>
                <button type="submit" class="rounded-full bg-primary px-6 py-3 text-white">Add lesson</button>
            </Form>
        </div>
    </div>
</template>
