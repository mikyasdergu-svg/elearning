<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Support" />

    <div class="mx-auto max-w-5xl space-y-8 p-6 md:p-10">
        <div class="rounded-3xl border border-border bg-card p-8">
            <h1 class="text-4xl font-semibold">Support</h1>
            <p class="mt-3 max-w-2xl text-sm leading-7 text-muted-foreground">
                Need help with courses or your account? Reach out to our support team and we’ll get back to you quickly.
            </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-3xl border border-border bg-card p-6">
                <h2 class="text-xl font-semibold">Contact us</h2>
                <p class="mt-3 text-sm text-muted-foreground">For general questions, billing, or course guidance, reach out to our team.</p>
                <div class="mt-6 space-y-3">
                    <a href="mailto:dnmikyasdergu@gmail.com" class="inline-flex rounded-full bg-primary px-6 py-3 text-white">Email: dnmikyasdergu@gmail.com</a>
                    <div class="text-sm text-muted-foreground">
                        <p class="font-semibold text-foreground">Developer phone numbers:</p>
                        <ul class="mt-2 space-y-1">
                            <li>Mikyas Dergu: 0918186422</li>
                            <li>Solomon Jember: 0964041993</li>
                            <li>Gebremelak Emshaw: 0968573673</li>
                            <li>Tiku Gezu: 0962725670</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="rounded-3xl border border-border bg-card p-6">
                <h2 class="text-xl font-semibold">Need help with learning?</h2>
                <p class="mt-3 text-sm leading-7 text-muted-foreground">If you’re stuck on a lesson or want help choosing your next course, we’re happy to help you stay on track.</p>
                <ul class="mt-6 space-y-3 text-sm text-muted-foreground">
                    <li>Course access and enrollment issues</li>
                    <li>Teacher or lesson questions</li>
                    <li>Account, billing, and payment support</li>
                </ul>
            </div>
        </div>
    </div>
</template>
