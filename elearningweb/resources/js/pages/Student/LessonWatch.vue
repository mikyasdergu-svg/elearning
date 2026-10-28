<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { namedRoute as route } from '@/lib/namedRoute';

const props = defineProps<{
    course: Record<string, any>;
    lesson: Record<string, any>;
    enrollment: Record<string, any>;
    isCompleted: boolean;
}>();
</script>

<template>
    <Head :title="`Watch ${props.lesson.title}`" />

    <div class="mx-auto max-w-4xl space-y-6 p-6">
        <div class="rounded-3xl border border-border bg-card p-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold">{{ props.lesson.title }}</h1>
                    <p class="text-sm text-muted-foreground">{{ props.course.title }}</p>
                </div>
                <Link :href="route('courses.show', { course: props.course.id })" class="rounded-full bg-secondary px-4 py-2 text-sm">Back to course</Link>
            </div>
        </div>

        <div class="rounded-3xl border border-border bg-card p-6">
            <div v-if="props.lesson.video_type === 'youtube'" class="aspect-video overflow-hidden rounded-3xl bg-black">
                <iframe
                    class="h-full w-full"
                    :src="props.lesson.video_url.replace('watch?v=', 'embed/').replace('youtu.be/', 'youtube.com/embed/')"
                    frameborder="0"
                    allowfullscreen
                ></iframe>
            </div>
            <div v-else class="aspect-video overflow-hidden rounded-3xl bg-black">
                <video controls class="h-full w-full">
                    <source :src="`/storage/${props.lesson.video_url}`" type="video/mp4" />
                    Your browser does not support video playback.
                </video>
            </div>
        </div>

        <div class="rounded-3xl border border-border bg-card p-6">
            <p class="text-sm leading-7">{{ props.lesson.description }}</p>
            <Form
                v-if="!props.isCompleted"
                :action="route('courses.lessons.complete', { course: props.course.id, lesson: props.lesson.id })"
                method="post"
                class="mt-6"
            >
                <label class="inline-flex items-center gap-3 text-sm">
                    <input type="checkbox" class="h-4 w-4 rounded border-input" @change="($event.target as HTMLInputElement).form?.requestSubmit()" />
                    <span>Mark this lesson as complete</span>
                </label>
            </Form>
            <p v-else class="mt-6 inline-flex items-center gap-2 text-sm text-success">
                <input type="checkbox" checked disabled class="h-4 w-4 rounded border-input" />
                Lesson completed
            </p>
            <div v-if="props.lesson.assessment" class="mt-6 rounded-3xl border border-border bg-card p-6">
                <h2 class="text-lg font-semibold">Assessment</h2>
                <p class="mt-2 text-sm text-muted-foreground">If this lesson includes a quiz, exam, or project, review it and submit your work.</p>
                <Link
                    :href="route('courses.lessons.assessment.show', { course: props.course.id, lesson: props.lesson.id })"
                    class="mt-4 inline-flex rounded-full bg-primary px-5 py-3 text-sm text-white"
                >
                    Open assessment
                </Link>
            </div>
        </div>
    </div>
</template>
