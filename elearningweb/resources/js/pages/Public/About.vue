<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="About Omishtu Joy Academy" />

    <div class="space-y-8 p-6 md:p-10">
        <section class="grid gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
            <div class="space-y-6">
                <p class="inline-flex rounded-full bg-primary/10 px-4 py-2 text-sm font-semibold text-primary">About Omishtu Joy Academy</p>
                <h1 class="max-w-3xl text-4xl font-semibold tracking-tight text-foreground sm:text-5xl">A calm, modern learning space built for busy students and practical skill growth.</h1>
                <p class="max-w-2xl text-base leading-8 text-muted-foreground">
                    Omishtu Joy Academy brings trusted teachers, clear course paths, and a supportive learning experience together in one place.
                    We help learners focus on progress instead of distraction, with short lessons, real projects, and flexible access.
                </p>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl border border-border bg-card p-6">
                        <h2 class="text-xl font-semibold">Structured learning</h2>
                        <p class="mt-2 text-sm text-muted-foreground">Every course is designed to help you move forward with simple milestones and clear outcomes.</p>
                    </div>
                    <div class="rounded-3xl border border-border bg-card p-6">
                        <h2 class="text-xl font-semibold">Trusted instructors</h2>
                        <p class="mt-2 text-sm text-muted-foreground">Our teachers share practical experience, with lessons that feel helpful from day one.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] bg-gradient-to-br from-[#eef4ff] via-[#f8f5ff] to-[#ffffff] p-6 shadow-xl ring-1 ring-border">
                <img src="/images/omishtu joy.png" alt="Omishtu Joy Academy" class="mx-auto h-full w-full rounded-[1.5rem] object-cover" />
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-3xl border border-border bg-card p-6">
                <h2 class="text-xl font-semibold">Our mission</h2>
                <p class="mt-3 text-sm leading-7 text-muted-foreground">To make online learning intentional, welcoming, and effective for students who want meaningful progress without overwhelm.</p>
            </div>
            <div class="rounded-3xl border border-border bg-card p-6">
                <h2 class="text-xl font-semibold">What we offer</h2>
                <p class="mt-3 text-sm leading-7 text-muted-foreground">Curated courses, progress tracking, teacher support, and a learning environment designed for follow-through.</p>
            </div>
            <div class="rounded-3xl border border-border bg-card p-6">
                <h2 class="text-xl font-semibold">Why it matters</h2>
                <p class="mt-3 text-sm leading-7 text-muted-foreground">We believe better learning happens when students feel guided, confident, and able to apply what they learn quickly.</p>
            </div>
        </section>

        <section class="space-y-6">
            <div class="rounded-3xl border border-border bg-card p-8">
                <h2 class="text-3xl font-semibold">Built by students from Debremarkos University</h2>
                <p class="mt-3 text-sm text-muted-foreground">Our team of developers created Omishtu Joy Academy to improve online learning experiences.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-3xl border border-border bg-card p-6 text-center">
                    <h3 class="font-semibold">Mikyas Dergu</h3>
                    <p class="mt-2 text-sm text-muted-foreground">Developer</p>
                    <p class="mt-3 text-xs text-primary font-medium">0918186422</p>
                </div>
                <div class="rounded-3xl border border-border bg-card p-6 text-center">
                    <h3 class="font-semibold">Solomon Jember</h3>
                    <p class="mt-2 text-sm text-muted-foreground">Developer</p>
                    <p class="mt-3 text-xs text-primary font-medium">0964041993</p>
                </div>
                <div class="rounded-3xl border border-border bg-card p-6 text-center">
                    <h3 class="font-semibold">Gebremelak Emshaw</h3>
                    <p class="mt-2 text-sm text-muted-foreground">Developer</p>
                    <p class="mt-3 text-xs text-primary font-medium">0968573673</p>
                </div>
                <div class="rounded-3xl border border-border bg-card p-6 text-center">
                    <h3 class="font-semibold">Tiku Gezu</h3>
                    <p class="mt-2 text-sm text-muted-foreground">Developer</p>
                    <p class="mt-3 text-xs text-primary font-medium">0962725670</p>
                </div>
            </div>

            <div class="rounded-3xl border border-border bg-card p-6 text-center">
                <h3 class="text-lg font-semibold">Contact the team</h3>
                <p class="mt-2 text-sm text-muted-foreground">Have questions or feedback? Reach out to us.</p>
                <a href="mailto:dnmikyasdergu@gmail.com" class="mt-4 inline-flex rounded-full bg-primary px-6 py-3 text-white font-medium">Email us</a>
            </div>
        </section>
    </div>
</template>
