<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { namedRoute as route } from '@/lib/namedRoute';

const props = defineProps<{
    course: Record<string, any>;
    enrollment: Record<string, any> | null;
    completedLessonIds: number[];
}>();

const isEnrolled = computed(() => !!props.enrollment);
const isPaid = computed(() => props.enrollment?.paid ?? false);
const hasAccess = computed(() => props.enrollment?.has_access ?? false);
const hasPendingPayment = computed(() => props.enrollment?.has_pending_payment ?? false);
const trialEnds = computed(() => props.enrollment?.trial_ends_at);

function isLessonLocked(lesson: Record<string, any>): boolean {
    return new Date(lesson.release_date) > new Date();
}

function canWatchLesson(lesson: Record<string, any>): boolean {
    return isEnrolled.value && hasAccess.value && !isLessonLocked(lesson);
}

function isLessonCompleted(lessonId: number): boolean {
    return props.completedLessonIds.includes(lessonId);
}
</script>

<template>
    <Head :title="props.course.title" />

    <div class="space-y-6 p-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold">{{ props.course.title }}</h1>
                <p class="text-sm text-muted-foreground">By {{ props.course.teacher.name }}</p>
            </div>
            <div class="space-y-2 rounded-3xl border border-border bg-card p-6">
                <div class="text-lg font-semibold">${{ Number(props.course.price).toFixed(2) }}</div>
                <Form v-if="!isEnrolled" method="post" :action="route('courses.enroll', { course: props.course.id })">
                    <button type="submit" class="w-full rounded-full bg-primary px-4 py-3 text-white">Start free trial</button>
                </Form>
                <template v-else-if="!isPaid">
                    <div v-if="hasPendingPayment" class="rounded-full bg-yellow-50 px-4 py-3 text-center text-sm text-yellow-900">
                        Payment is pending admin verification. Full access will be granted once it is approved.
                    </div>
                    <div v-else-if="hasAccess" class="rounded-full bg-success/15 px-4 py-3 text-center text-sm text-success">
                        Trial active — full access until {{ new Date(trialEnds!).toLocaleDateString() }}
                    </div>
                    <div v-else class="rounded-full bg-destructive/15 px-4 py-3 text-center text-sm text-destructive">
                        Trial ended — pay to continue
                    </div>
                    <Link
                        v-if="!hasPendingPayment"
                        :href="route('checkout.show', { enrollment: props.enrollment!.id })"
                        class="inline-flex w-full justify-center rounded-full bg-warning px-4 py-3 text-white"
                    >
                        Pay ${{ Number(props.enrollment!.price).toFixed(2) }} to continue
                    </Link>
                </template>
                <div v-else class="rounded-full bg-success px-4 py-3 text-center text-white">Paid access granted</div>
                <div v-if="trialEnds && !isPaid" class="text-xs text-muted-foreground">
                    Trial ends: {{ new Date(trialEnds).toLocaleString() }}
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
            <div class="space-y-4 rounded-3xl border border-border bg-card p-6">
                <h2 class="text-xl font-semibold">About this course</h2>
                <p class="text-sm leading-7">{{ props.course.description }}</p>
            </div>
            <div class="space-y-4 rounded-3xl border border-border bg-card p-6">
                <h2 class="text-xl font-semibold">Lessons</h2>
                <ul class="space-y-3">
                    <li
                        v-for="lesson in props.course.lessons"
                        :key="lesson.id"
                        class="rounded-2xl border border-border p-4 transition"
                        :class="{
                            'opacity-50': isLessonLocked(lesson),
                            'bg-muted/40': isLessonLocked(lesson),
                        }"
                    >
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="font-semibold">{{ lesson.title }}</h3>
                                <p class="text-xs text-muted-foreground">
                                    Release date: {{ new Date(lesson.release_date).toLocaleDateString() }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    v-if="isLessonCompleted(lesson.id)"
                                    class="rounded-full bg-success/15 px-3 py-1 text-xs text-success"
                                >
                                    Completed
                                </span>
                                <span
                                    v-else-if="isLessonLocked(lesson)"
                                    class="rounded-full bg-muted px-3 py-1 text-xs text-muted-foreground"
                                >
                                    Locked
                                </span>
                                <div class="flex items-center gap-2">
                                    <Link
                                        v-if="canWatchLesson(lesson)"
                                        :href="route('courses.lessons.watch', { course: props.course.id, lesson: lesson.id })"
                                        class="rounded-full bg-primary px-3 py-1 text-xs text-white"
                                    >
                                        Watch
                                    </Link>
                                    <Link
                                        v-if="canWatchLesson(lesson) && lesson.assessment"
                                        :href="route('courses.lessons.assessment.show', { course: props.course.id, lesson: lesson.id })"
                                        class="rounded-full bg-secondary px-3 py-1 text-xs"
                                    >
                                        Assessment
                                    </Link>
                                </div>
                                <div v-if="lesson.assessment" class="mt-2 inline-flex items-center gap-2 text-xs text-muted-foreground">
                                    <span class="rounded-full bg-secondary/20 px-2 py-1">Assessment ready</span>
                                </div>
                                <span v-else class="rounded-full bg-secondary px-3 py-1 text-xs">Available</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
