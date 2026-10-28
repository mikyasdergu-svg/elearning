<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { namedRoute as route } from '@/lib/namedRoute';

const props = defineProps<{
    sales: Array<Record<string, any>>;
    revenueByCourse: Array<Record<string, any>>;
    totalRevenue: number;
    start: string;
    end: string;
}>();

const presets = computed(() => {
    const today = new Date();
    const format = (date: Date) => date.toISOString().slice(0, 10);

    return [
        {
            label: 'Last 7 days',
            start: format(new Date(today.getTime() - 6 * 24 * 60 * 60 * 1000)),
            end: format(today),
        },
        {
            label: 'Last 30 days',
            start: format(new Date(today.getTime() - 29 * 24 * 60 * 60 * 1000)),
            end: format(today),
        },
    ];
});
</script>

<template>
    <Head title="Sales Reports" />

    <div class="space-y-6 p-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-border bg-card p-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold">Sales reports</h1>
                <p class="mt-2 text-sm text-muted-foreground">View revenue and enrollment performance for your courses.</p>
            </div>
            <Link :href="route('admin.dashboard')" class="rounded-full bg-secondary px-5 py-3 text-sm">Back to dashboard</Link>
        </div>

        <div class="rounded-3xl border border-border bg-card p-6">
            <div class="flex flex-wrap gap-3">
                <Link
                    v-for="preset in presets"
                    :key="preset.label"
                    :href="route('admin.sales.index', {}, { query: { start: preset.start, end: preset.end } })"
                    class="rounded-full bg-secondary px-4 py-2 text-sm"
                >
                    {{ preset.label }}
                </Link>
            </div>

            <form class="mt-4 flex flex-col gap-3 md:flex-row md:items-end" method="get" :action="route('admin.sales.index')">
                <label class="grid gap-2 text-sm">
                    <span>Start date</span>
                    <input type="date" name="start" :value="props.start" class="rounded-xl border border-input px-4 py-3" />
                </label>
                <label class="grid gap-2 text-sm">
                    <span>End date</span>
                    <input type="date" name="end" :value="props.end" class="rounded-xl border border-input px-4 py-3" />
                </label>
                <button type="submit" class="rounded-full bg-primary px-5 py-3 text-white">Apply custom range</button>
            </form>
        </div>

        <div class="grid gap-4 rounded-3xl border border-border bg-card p-6 md:grid-cols-2">
            <div>
                <p class="text-sm text-muted-foreground">Date range</p>
                <div class="mt-2">{{ props.start }} — {{ props.end }}</div>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Total revenue</p>
                <div class="mt-2 text-2xl font-semibold">${{ props.totalRevenue.toFixed(2) }}</div>
            </div>
        </div>

        <div class="rounded-3xl border border-border bg-card p-6">
            <h2 class="text-xl font-semibold">Revenue by course</h2>
            <div class="mt-4 space-y-4">
                <div v-for="item in props.revenueByCourse" :key="item.course.id" class="rounded-2xl border border-border p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold">{{ item.course.title }}</h3>
                            <p class="text-sm text-muted-foreground">{{ item.enrollments }} paid enrollments</p>
                        </div>
                        <div class="font-semibold">${{ Number(item.revenue ?? 0).toFixed(2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-border bg-card p-6">
            <h2 class="text-xl font-semibold">Recent sales</h2>
            <div class="mt-4 space-y-3">
                <div v-for="sale in props.sales" :key="sale.id" class="rounded-2xl border border-border p-4">
                    <div class="flex items-center justify-between">
                        <span>{{ sale.student.name }}</span>
                        <span class="font-semibold">${{ Number(sale.price ?? 0).toFixed(2) }}</span>
                    </div>
                    <div class="text-xs text-muted-foreground">{{ sale.course.title }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
