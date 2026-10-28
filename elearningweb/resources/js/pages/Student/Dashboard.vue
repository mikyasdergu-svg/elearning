<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { namedRoute as route } from '@/lib/namedRoute';

const props = defineProps<{ enrollments: Array<Record<string, any>> }>();

const totalCourses = props.enrollments.length;
const averageProgress = totalCourses
    ? Math.round(
          props.enrollments.reduce(
              (sum, enrollment) => sum + Number(enrollment.progress ?? 0),
              0,
          ) / totalCourses,
      )
    : 0;
</script>

<template>
    <Head title="Student Dashboard" />

    <div class="space-y-6 p-6">
        <div class="grid gap-4 xl:grid-cols-[1.4fr_0.9fr]">
            <div class="rounded-3xl border border-border bg-card p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h1 class="text-3xl font-semibold">Welcome back</h1>
                        <p class="mt-2 text-sm text-muted-foreground">Continue your learning path with curated lessons from Omishtu Joy Academy.</p>
                    </div>
                    <div class="grid gap-3 rounded-3xl bg-primary/5 p-4 text-sm text-primary sm:grid-cols-3">
                        <div class="rounded-3xl bg-white/90 p-4 shadow-sm">
                            <p class="text-3xl font-semibold">{{ totalCourses }}</p>
                            <p class="text-xs text-muted-foreground">Active courses</p>
                        </div>
                        <div class="rounded-3xl bg-white/90 p-4 shadow-sm">
                            <p class="text-3xl font-semibold">{{ averageProgress }}%</p>
                            <p class="text-xs text-muted-foreground">Average progress</p>
                        </div>
                        <div class="rounded-3xl bg-white/90 p-4 shadow-sm">
                            <p class="text-3xl font-semibold">{{ props.enrollments.filter((entry) => entry.paid).length }}</p>
                            <p class="text-xs text-muted-foreground">Full access</p>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="rounded-3xl border border-border bg-card p-6">
                <h2 class="text-xl font-semibold">Academy highlights</h2>
                <p class="mt-2 text-sm text-muted-foreground">Small steps every day help you build confidence and finish courses faster.</p>
                <div class="mt-6 space-y-4">
                    <div class="rounded-3xl border border-border bg-muted p-4">
                        <p class="text-sm font-semibold">Project-based practice</p>
                        <p class="mt-2 text-sm text-muted-foreground">Turn lessons into work-ready skills with clear, hands-on projects.</p>
                    </div>
                    <div class="rounded-3xl border border-border bg-muted p-4">
                        <p class="text-sm font-semibold">Study at your pace</p>
                        <p class="mt-2 text-sm text-muted-foreground">Pick lessons that fit your schedule and stay motivated with progress tracking.</p>
                    </div>
                    <div class="rounded-3xl border border-border bg-muted p-4">
                        <p class="text-sm font-semibold">Trusted instructors</p>
                        <p class="mt-2 text-sm text-muted-foreground">Learn from experienced teachers who guide you through every step.</p>
                    </div>
                </div>
            </aside>
        </div>

        <div class="grid gap-4 xl:grid-cols-[1fr_0.95fr]">
            <div class="space-y-4">
                <div class="rounded-3xl border border-border bg-card p-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold">Your courses</h2>
                            <p class="mt-2 text-sm text-muted-foreground">Track progress, resume lessons, and keep building new skills.</p>
                        </div>
                        <Link
                            :href="route('home')"
                            class="rounded-full bg-primary px-5 py-3 text-sm font-semibold text-white"
                        >
                            Browse courses
                        </Link>
                    </div>
                </div>

                <div class="grid gap-4">
                    <div v-if="props.enrollments.length === 0" class="rounded-3xl border border-border bg-card p-6 text-center">
                        <p class="text-lg font-semibold">You are not enrolled in any courses yet.</p>
                        <p class="mt-2 text-sm text-muted-foreground">Explore the catalog and find your next course.</p>
                        <Link :href="route('home')" class="mt-4 inline-flex rounded-full bg-primary px-5 py-3 text-white">Browse courses</Link>
                    </div>

                    <div v-for="enrollment in props.enrollments" :key="enrollment.course.id" class="rounded-3xl border border-border bg-card p-6">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div class="space-y-2">
                                <h3 class="text-xl font-semibold">{{ enrollment.course.title }}</h3>
                                <p class="text-sm text-muted-foreground">
                                    {{ enrollment.completed_count }}/{{ enrollment.lesson_count }} lessons • {{ enrollment.progress }}%
                                </p>
                                <div class="h-2 w-full max-w-xs rounded-full bg-muted">
                                    <div
                                        class="h-2 rounded-full bg-primary transition-all"
                                        :style="{ width: `${enrollment.progress}%` }"
                                    />
                                </div>
                                <p class="text-sm">
                                    <span v-if="enrollment.paid" class="text-success">Paid — full access</span>
                                    <span v-else-if="enrollment.has_pending_payment" class="text-yellow-700">Payment pending verification</span>
                                    <span v-else-if="enrollment.has_access" class="text-muted-foreground">Trial active</span>
                                    <span v-else class="text-destructive">Payment required</span>
                                </p>
                                <p v-if="!enrollment.paid && enrollment.trial_ends_at" class="text-xs text-muted-foreground">
                                    Trial ends: {{ new Date(enrollment.trial_ends_at).toLocaleDateString() }}
                                </p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <Link
                                    v-if="enrollment.next_lesson_id && enrollment.has_access"
                                    :href="route('courses.lessons.watch', { course: enrollment.course.id, lesson: enrollment.next_lesson_id })"
                                    class="rounded-full bg-primary px-4 py-2 text-center text-sm text-white"
                                >
                                    Resume lesson
                                </Link>
                                <Link
                                    v-if="!enrollment.has_access && !enrollment.paid && !enrollment.has_pending_payment"
                                    :href="route('checkout.show', { enrollment: enrollment.id })"
                                    class="rounded-full bg-warning px-4 py-2 text-center text-sm text-white"
                                >
                                    Pay to continue
                                </Link>
                                <Link :href="route('courses.show', { course: enrollment.course.id })" class="rounded-full bg-secondary px-4 py-2 text-center text-sm">
                                    View course
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="rounded-3xl border border-border bg-card p-6">
                <h2 class="text-xl font-semibold">Learning support</h2>
                <p class="mt-2 text-sm text-muted-foreground">Need a boost? These resources help you stay on track.</p>
                <div class="mt-6 space-y-4">
                    <div class="rounded-3xl border border-border bg-muted p-4">
                        <h3 class="font-semibold">Weekly progress checks</h3>
                        <p class="mt-2 text-sm text-muted-foreground">Review your recent activity and see where to focus next.</p>
                    </div>
                    <div class="rounded-3xl border border-border bg-muted p-4">
                        <h3 class="font-semibold">Stay consistent</h3>
                        <p class="mt-2 text-sm text-muted-foreground">Spend 15 minutes a day on lessons to make steady progress.</p>
                    </div>
                    <div class="rounded-3xl border border-border bg-muted p-4">
                        <h3 class="font-semibold">Reach out anytime</h3>
                        <p class="mt-2 text-sm text-muted-foreground">If you need help, open the support page from the sidebar.</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</template>
