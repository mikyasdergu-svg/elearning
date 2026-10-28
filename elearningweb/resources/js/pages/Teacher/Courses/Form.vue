<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { namedRoute as route } from '@/lib/namedRoute';

const props = defineProps<{ course: Record<string, any> | null }>();

const isEditing = computed(() => !!props.course);
const action = computed(() => (isEditing.value ? route('teacher.courses.update', { course: props.course!.id }) : route('teacher.courses.store')));
const method = computed(() => (isEditing.value ? 'put' : 'post'));
</script>

<template>
    <Head :title="isEditing ? 'Edit course' : 'Create course'" />

    <div class="mx-auto max-w-3xl p-6">
        <div class="rounded-3xl border border-border bg-card p-8">
            <h1 class="text-3xl font-semibold">{{ isEditing ? 'Edit course' : 'Create course' }}</h1>

            <Form :action="action" :method="method" class="mt-6 space-y-6" enctype="multipart/form-data">
                <div class="grid gap-4">
                    <label class="grid gap-2 text-sm">
                        <span>Course title</span>
                        <input name="title" type="text" :value="props.course?.title" class="w-full rounded-xl border border-input px-4 py-3" required maxlength="100" />
                    </label>
                    <label class="grid gap-2 text-sm">
                        <span>Description</span>
                        <textarea name="description" class="w-full rounded-xl border border-input px-4 py-3" required minlength="50">{{ props.course?.description }}</textarea>
                    </label>
                    <label class="grid gap-2 text-sm">
                        <span>Price</span>
                        <input name="price" type="number" step="0.01" min="0.01" :value="props.course?.price" class="w-full rounded-xl border border-input px-4 py-3" required />
                    </label>
                    <label class="grid gap-2 text-sm">
                        <span>Status</span>
                        <select name="status" class="w-full rounded-xl border border-input px-4 py-3" required>
                            <option value="draft" :selected="props.course?.status === 'draft'">Draft</option>
                            <option value="published" :selected="props.course?.status === 'published'">Published</option>
                        </select>
                    </label>
                    <label class="grid gap-2 text-sm">
                        <span>Thumbnail image</span>
                        <input type="file" name="thumbnail" accept="image/*" class="rounded-xl border border-input px-4 py-3" />
                    </label>
                </div>
                <button type="submit" class="rounded-full bg-primary px-6 py-3 text-white">{{ isEditing ? 'Save changes' : 'Create course' }}</button>
            </Form>
        </div>
    </div>
</template>
