<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { namedRoute as route } from '@/lib/namedRoute';

const props = defineProps<{ courses: Array<Record<string, any>>; search: string | null }>();
const search = ref(props.search ?? '');
</script>

<template>
    <Head title="Courses" />

    <div class="space-y-6 p-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold">All courses</h1>
                <p class="text-sm text-muted-foreground">Explore curated lessons from trusted teachers and start your learning journey.</p>
            </div>
            <form class="w-full md:w-1/2" method="get">
                <input
                    v-model="search"
                    type="search"
                    name="search"
                    class="w-full rounded-xl border border-input px-4 py-3"
                    placeholder="Search courses by title"
                />
            </form>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="course in props.courses"
                :key="course.id"
                class="rounded-3xl border border-border bg-card p-6 shadow-sm"
            >
                <div class="mb-4 h-40 overflow-hidden rounded-2xl bg-muted">
                    <img v-if="course.thumbnail" :src="course.thumbnail.startsWith('http') ? course.thumbnail : `/storage/${course.thumbnail}`" alt="Thumbnail" class="h-full w-full object-cover" />
                </div>
                <div class="space-y-2">
                    <h2 class="text-xl font-semibold">{{ course.title }}</h2>
                    <p class="text-sm text-muted-foreground">{{ course.teacher.name }}</p>
                    <p class="text-sm line-clamp-2">{{ course.description }}</p>
                    <div class="flex items-center justify-between pt-4">
                        <span class="text-base font-semibold">${{ Number(course.price ?? 0).toFixed(2) }}</span>
                        <Link :href="route('courses.show', { course: course.id })" class="rounded-full bg-primary px-4 py-2 text-white">View</Link>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-border overflow-hidden shadow-sm">
            <div class="grid gap-4 md:grid-cols-2">
                <img src="/images/codingimage.png" alt="Developer coding" class="h-64 w-full object-cover" />
                <img src="/images/PHPCodingimage.jpg" alt="PHP development" class="h-64 w-full object-cover" />
            </div>
        </div>
    </div>
</template>
