<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { dashboard, login, register } from '@/routes';

const page = usePage();

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Omishtu Joy Academy" />

    <div class="min-h-screen bg-background text-foreground">
        <div class="mx-auto flex min-h-screen max-w-7xl flex-col justify-center gap-12 px-6 py-12 lg:px-8">
            <header class="flex items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="rounded-3xl bg-primary/10 p-3 text-primary shadow-sm ring-1 ring-primary/15">
                        <AppLogoIcon class="h-8 w-8" />
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.4em] text-primary">Omishtu Joy Academy</p>
                        <p class="text-sm text-muted-foreground">Minimal learning for modern students</p>
                    </div>
                </div>
                <nav class="flex items-center gap-4 text-sm font-medium text-foreground">
                    <Link
                        v-if="page.props.auth.user"
                        :href="dashboard()"
                        class="transition hover:text-primary"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="login()" class="transition hover:text-primary">Log in</Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="rounded-full border border-border bg-card px-4 py-2 transition hover:border-primary hover:text-primary"
                        >
                            Register
                        </Link>
                    </template>
                </nav>
            </header>

            <main class="grid gap-12 lg:grid-cols-[1.3fr_0.9fr] lg:items-center">
                <section class="space-y-8">
                    <div class="space-y-5">
                        <span class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-sm font-semibold text-primary">
                            Learn with clarity
                        </span>
                        <h1 class="max-w-3xl text-4xl font-semibold tracking-tight text-foreground sm:text-5xl">
                            Empower your next step with thoughtfully designed online courses.
                        </h1>
                        <p class="max-w-2xl text-base leading-8 text-muted-foreground">
                            Omishtu Joy Academy brings modern lessons, trusted teachers, and a calm learning experience together in one place.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <Link
                            href="/"
                            class="inline-flex items-center justify-center rounded-full bg-primary px-6 py-3 text-sm font-semibold text-white transition hover:bg-primary/90"
                        >
                            Browse courses
                        </Link>
                        <a
                            href="mailto:hello@omishtujoy.academy"
                            class="inline-flex items-center justify-center rounded-full border border-border bg-card px-6 py-3 text-sm font-semibold text-foreground transition hover:border-primary hover:text-primary"
                        >
                            Contact admissions
                        </a>
                    </div>

                    <div class="grid gap-4 rounded-3xl border border-border bg-card p-6 shadow-sm">
                        <div>
                            <p class="text-sm font-semibold text-foreground">Why Omishtu Joy Academy?</p>
                        </div>
                        <ul class="space-y-3 text-sm leading-7 text-muted-foreground">
                            <li>Structured course paths optimized for focus and skill growth.</li>
                            <li>Trusted educators sharing practical knowledge.</li>
                            <li>Calm interface built for effortless learning.</li>
                        </ul>
                    </div>
                </section>

                <section class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-[#eef4ff] via-[#f8f5ff] to-[#ffffff] p-8 shadow-xl dark:from-[#0f172a] dark:via-[#111827] dark:to-[#0b1224]">
                    <div class="absolute -left-12 top-8 h-28 w-28 rounded-full bg-primary/20 blur-3xl"></div>
                    <div class="absolute right-8 top-16 h-24 w-24 rounded-full bg-accent/25 blur-3xl"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.12),_transparent_45%)]"></div>
                    <div class="relative grid gap-6">
                        <div class="grid gap-4 rounded-[1.5rem] bg-white/90 p-6 shadow-lg dark:bg-white/5">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-foreground">Featured course</span>
                                <span class="rounded-full bg-secondary px-3 py-1 text-xs font-semibold text-secondary-foreground">New</span>
                            </div>
                            <div class="space-y-3">
                                <h2 class="text-2xl font-semibold">Design thinking for learners</h2>
                                <p class="text-sm leading-6 text-muted-foreground">Build practical skills through short lessons and steady momentum.</p>
                            </div>
                        </div>
                        <div class="rounded-[1.5rem] bg-[#f8fafc] p-6 dark:bg-[#111827]">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-foreground">Ongoing enrollment</span>
                                <span class="text-sm font-medium text-primary">Open now</span>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-muted-foreground">Join flexible cohorts, continue at your own pace, and keep progress in one dashboard.</p>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</template>
